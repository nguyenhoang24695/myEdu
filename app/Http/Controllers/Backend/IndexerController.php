<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 12/14/15
 * Time: 08:20
 */

namespace App\Http\Controllers\Backend;


use App\Core\MyIndexer;
use App\Core\MySearchEngine;
use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\EsTag;
use App\Models\MarketingCourse;
use App\Models\User;
use App\Models\Video;
use Elasticquent\ElasticquentCollection;
use Illuminate\Http\Request;

class IndexerController extends Controller
{
    public function index(Request $request)
    {

        javascript()->put([
            'searchindex_info_link' => route('backend.searchindex.manipulate'),
        ]);
        return view('backend.searchindex.index');
    }



    public function index_manipulate( $type, $action = null, Request $request ) {
        switch($type){
            case 'videos':
                $class = Video::class;
                break;
            case 'courses':
                $class = Course::class;
                break;
            case 'tags':
                $class = EsTag::class;
                break;
            case 'users':
                $class = User::class;
                break;
            case 'marketing_courses':
                $class = MarketingCourse::class;
                break;
            case 'all':
                return $this->manipulate_index($action, $request);
        }
        return $this->manipulate_type($class, $action, $request);
    }

    private function manipulate_type($class, $action = null, Request $request){
        try{
            switch($action){
                case 'reindex':
                    $item_query_builder = $class::query();

                    $id_range = explode('-',$request->query('ids', 'all'));
//                    dd($id_range);
                    if(count($id_range) == 2){
                        if(intval($id_range[0]) > 0){
                            $item_query_builder= $item_query_builder->where('id', '>', intval($id_range[0]));
                        }
                        if(intval($id_range[1]) > 0){
                            $item_query_builder = $item_query_builder->where('id', '<', intval($id_range[1]));
                        }
                    }elseif($id_range[0] != 'all' && !empty($id_range[0])){
                        $item_query_builder = $item_query_builder->whereIn('id', explode(',', $id_range[0]));
                    }

                    $item_query_builder->chunk(100, function($ec){
                        /** @var ElasticquentCollection $ec */
                        $ec->addToIndex();
                    });
//                    $class::addAllToIndex();

                    break;
                case 'info':
                    $instant = new $class;
                    $index_name = $instant->getIndexName();
                    $type_name = $instant->getTypeName();
                    $client = $instant->getElasticSearchClient();
                    $info = $client->indices()->stats([
                        'index' => $index_name,
                        'types' => $type_name,
                    ]);
                    $msg = [
                        'type' => $type_name,
                        'total' => array_get($info, '_all.primaries.indexing.types.' . $type_name),
                    ];
                    return back()->withFlashSuccess("<pre>" . print_r($msg, true) . "</pre>");
                    break;
                default:
                    throw new GeneralException("Not supported");
            }
            return back()->withFlashSuccess("DONE");
        }catch (\Exception $ex){
            throw new GeneralException($ex->getMessage());
        }

    }

    private function manipulate_index($action, Request $request){
        if($action == 'reinit'){
            /** Xóa index */
            $model_instant = new Course();
            $check_exits = $model_instant->getElasticSearchClient()->indices()->exists([
                'index' => $model_instant->getIndexName(),
            ]);
            if($check_exits) {
                Course::deleteIndex();
            }
            /** Tạo lại index kèm các setting */
            Course::createIndex();
//            Course::addAllToIndex();
            /** Put các mapping khác */
            Video::putMapping(true);
            User::putMapping(true);
            EsTag::putMapping(true);
            MarketingCourse::putMapping(true);
        }else{
            throw new GeneralException("Action not supported");
        }
        return back()->withFlashSuccess("Reinitialize all type success");
    }

}