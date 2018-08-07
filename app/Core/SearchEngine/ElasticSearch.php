<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 12/10/15
 * Time: 10:17
 */

namespace App\Core\SearchEngine;

use App\Core\Contracts\SearchEngine;
use App\Exceptions\IndexerErrorException;
use Elasticsearch\ClientBuilder;
use Elasticsearch\Common\Exceptions\Missing404Exception;

class ElasticSearch implements SearchEngine
{
    /** @var \Elasticsearch\Client */
    private $client;

    /**
     * ElasticSearch constructor.
     */
    public function __construct()
    {
        $hosts = config('database.fulltext_search.elastic.hosts');
        $this->client = ClientBuilder::create()->setHosts($hosts)->build();
    }

    private function setting(){
        $this->client->get();
    }


    /**
     * Tìm kiếm theo keyword, yêu cầu match bất kỳ từ nào trong keyword
     * @param $index
     * @param array $query
     * @return mixed
     */
    public function search($index, Array $query)
    {
        list($index, $type) = explode('.', $index);
        $search_query = [
            'index' => $index,
            'type' => $type,
            'body' => [
                'query' => $query
            ],
        ];
        $result = $this->client->search($search_query);
        return $result;
    }

    /**
     * Tìm kiếm theo keyword, yêu cầu match tất cả từ trong keyword
     * @param $index
     * @param $keyword
     * @return mixed
     */
    public function searchAll($index, $keyword)
    {
        // TODO: Implement searchAll() method.
    }

    /**
     * Thêm 1 record vào index
     * @param $index
     * @param $body
     * @param null $id
     * @return bool
     */
    public function add($index, Array $body, $id = null)
    {
        list($index, $type) = explode('.', $index);
        $should_index = [
            'index' => $index,
            'type' => $type,
            'body' => $body
        ];
        if(!empty($id)){
            $should_index['id'] = $id;
        }
        $index_result = $this->client->index($should_index);
        if($index_result['created'] == 1){
            return true;
        }else{
            return false;
        }
    }

    /**
     * @param $index
     * @param array $body
     * @return int
     * @throws IndexerErrorException
     */
    public function addBulk($index, Array $body)
    {
        list($index, $type) = explode('.', $index);
        $should_index = ['body' => []];
        foreach($body as $a_body){
            $header = [
                'index' => [
                    '_index' => $index,
                    '_type' => $type
                ]
            ];

            if(!empty($a_body['id'])){
                $header['index']['_id'] = $a_body['id'];
            }

            $a_body = array_except($a_body, 'id');

            $should_index['body'][] = $header;
            $should_index['body'][] = $a_body;
        }
        $indexed = $this->client->bulk($should_index);
        if($indexed['errors']){
            \Log::error($indexed['errors']);
            throw new IndexerErrorException(var_export($indexed['errors'], true));
        }
        $indexed_count = 0;
        foreach($indexed['items'] as $_indexed){
            if($_indexed['index']['status'] == 201){
                $indexed_count++;
            }
        }
        return $indexed_count;
    }


    /**
     * Thay thế 1 record bằng thông tin mới
     * @param $index
     * @param $id
     * @param $body gồm toàn bộ thông tin của record như khi thêm mới
     * @return mixed
     * @deprecated Không dùng
     */
    public function replace($index, $id, $body)
    {
//        list($index, $type) = explode('.', $index);
//        $should_index = [
//            'index' => $index,
//            'type' => $type,
//            'body' => $body
//        ];
//        if(!empty($id)){
//            $should_index['id'] = $id;
//        }
//        $index_result = $this->client->update($should_index);return($index_result);
//        if($index_result['created'] == 1){
//            return true;
//        }else{
//            return false;
//        }
    }

    /**
     * Sửa thông tin của 1 hoặc nhiều record
     * @param $index
     * @param int|array $id
     * @param $body gồm các thông tin cần thay đổi
     * @return mixed
     */
    public function update($index, $id, $body)
    {
        list($index, $type) = explode('.', $index);
        $should_index = [
            'index' => $index,
            'type' => $type,
            'body' => [
                'doc' => $body
                ]
        ];
        if(!empty($id)){
            $should_index['id'] = $id;
        }
        try{
            $index_result = $this->client->update($should_index);
            if($index_result['_shards']['successful'] > 0){
                return true;
            }else{
                return false;
            }
        }catch (\Exception $ex){
            \Log::error($ex->getMessage());
        }

    }

    /**
     * Xóa 1 hoặc nhiều record khỏi index
     * @param $index
     * @param mixed|array $ids
     * @return mixed
     */
    public function delete($index, $ids)
    {
        list($index, $type) = explode('.', $index);
        $ids = is_array($ids) ? $ids : [$ids];
        $deleted = 0;
        foreach($ids as $id){
            try {
                $response = $this->client->delete([
                    'index' => $index,
                    'type' => $type,
                    'id' => $id
                ]);
                if($response['found'] == true && $response['_shards']['successful'] > 0){
                    $deleted++;
                }
            }catch (Missing404Exception $ex){
                // Xóa bản ghi không tồn tại
            }catch (\Exception $ex){
                \Log::error($ex->getMessage());
            }
        }
        return $deleted;
    }


    public function deleteIndex($index)
    {
        $index_type = explode('.', $index);
        try{
            $_index = [
                'index' => $index_type[0],
                ];
            if(count($index_type) > 1){
                $_index['type'] = $index_type[1];
                $this->client->indices()->deleteMapping($_index);
            }else{
                $this->client->indices()->delete($_index);
            }
            return true;
        }catch (\Exception $ex){
            \Log::error($ex->getMessage());
            return false;
        }
    }

    public function getIndexInfo($index)
    {
        list($index, $type) = explode('.', $index);
        $response = $this->client->indices()->stats([
            'index' => $index,
            'types' => $type,
            'metric' => ['docs'],
        ]);
        $count = array_get($response, 'indices.' . $index . '.total.docs');
        // setting
        $response = $this->client->indices()->getSettings([
            'index' => $index
        ]);
        $settings = array_get($response, $index . '.settings');
        // mapping
        $response = $this->client->indices()->getMapping([
            'index' => $index,
            'type' => $type,
        ]);
        $mappings = array_get($response, $index . '.mappings.' . $type . '.properties');

        return [
            'count' => $count,
            'settings' => $settings,
            'mappings' => $mappings,
        ];
    }

    /**
     * Kiểm tra xem index hiện tại đã có trong search engine chưa
     * @param $index
     * @return mixed
     */
    public function checkIndexExist($index)
    {
        $index_array = explode('.', $index);
        if(count($index_array) == 2){
            list($index, $type) = explode('.', $index);
        }else{
            $type = null;
        }
        if(empty($type)){
            $check = $this->client->indices()->exists([
                'index' => $index
            ]);
        }else{
            $check = $this->client->indices()->existsType([
                'index' => $index,
                'type' => $type
            ]);
        }
        return $check;
    }

    /**
     * Kiểm tra index hiện tại đã có chưa, nếu chưa có thì tạo ra. Cuối cùng có index trả về true, ko tạo được là false
     * @param $index
     * @param bool $re_create
     * @return mixed
     */
    public function forceExist($index, $re_create = false)
    {
        if($this->checkIndexExist($index)){
            return true;
        }
        return $this->createIndex($index);
    }

    /**
     * Tạo mới index, chú ý config của các engine khác nhau
     * @param $index
     * @param bool $check
     * @return mixed
     */
    public function createIndex($index, $check = false)
    {
        list($_index, $_type) = explode('.', $index);
        if($check && $this->checkIndexExist($index)){
            return true;
        }

        // get index setting
        $settings = config('elastic.indexes.' . $_index . '.settings');
        $settings = array_merge_recursive($settings, config('elastic.indexes.default_settings'));
        if(!$this->checkIndexExist($_index)){
            $response = $this->client->indices()->create([
                    'index' => $_index,
                    'body' => [
                        'settings' => $settings
                    ]
                ]);
            if(!isset($response['acknowledged'])){
                \Log::error("Không tạo được index '" . $_index . "'");
                return false;
            }
        }
        // get type mapping
        $default_mappings = config('elastic.indexes.' . $_index . '.default_mapping');
        $type_mappings = array_merge_recursive($default_mappings, config('elastic.indexes.' . $_index . '.types.' . $_type, []));
        //dd($type_mappings);
        if(!empty($type_mappings)){
            $response = $this->client->indices()->putMapping([
                'index' => $_index,
                'type' => $_type,
                'body' => [
                    $_type => $type_mappings
                ]
            ]);
            if(!isset($response['acknowledged'])){
                \Log::error("Không tạo được index '" . $index . "'");
                return false;
            }
        }

        return true;

    }

    public function createIndexInfo($index){
        list($index, $type) = explode('.', $index);
        try{
            $this->client->indices()->create([
                'index' => $index
            ]);
        }catch (\Exception $ex){
            \Log::error($ex->getMessage());
        }
    }
}