<?php
/**
 * Class áp dụng công nghệ DEFA video protect
 * User: hocvt
 * Date: 3/2/16
 * Time: 08:34
 */

namespace App\Core;


use Illuminate\Http\Request;
use League\Flysystem\Adapter\Local;

class Defa
{

    /** @var array */
    private $videos = [];
    /** @var string */
    private $secure_key;
    /** @var  Request */
    private $request;

    const WINDOW_PREFIX = 'qh_';
    const WINDOW_KEY = 'window';
    const URL_VAL = 'view_key';

    /**
     * Defa constructor.
     */
    public function __construct()
    {
        $this->secure_key = md5(config('app.key'));
    }

    /**
     * Thiết đặt request trong các trường hợp enable, ...
     * @param Request $request
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;

    }


    public function enable(Request $request)
    {
        if ($request->isMethod('post')) {
            $this->enableWindow();
        }
    }

    private function enableWindow(){
        $window = \Session::get(self::WINDOW_KEY);
        \Session::set(self::WINDOW_PREFIX . $window, true);
    }

    function getURL($video_disk, $video_path, $enable = false)
    {

        \Session::set(self::WINDOW_KEY, md5(time()));

        $video_id = $this->buildVideoId($video_disk, $video_path);

        $current_defa_val = \Session::get(self::URL_VAL, '');
        if ($current_defa_val == '') {
            \Session::set(self::URL_VAL, 1);
        } else {
            \Session::set(self::URL_VAL, $current_defa_val + 1);
            $current_defa_val++;
        }

        \Session::set('x' . $video_id . $current_defa_val, 0);
        \Session::set('defa' . $video_id . $current_defa_val, md5(time() . config('app.key')));
        \Session::set('imdefa' . $video_id, $this->secure_key . base64_encode(base64_encode($video_id)));
        \Session::set('x' . $video_id, 0);
        \Session::set('defa' . $video_id, md5(time() . config('app.key')));
        \Session::set('file' . $current_defa_val, $this->secure_key . base64_encode(base64_encode($video_id)));

        if($enable){
            //$this->enableWindow();
        }

        return route('defa.video_stream', [
            'window' => \Session::get(self::WINDOW_KEY),
            'view_key' => $current_defa_val
        ]);
    }

    public function getEnableLink()
    {
        return route('defa.enable');
    }
    public static function enableLink()
    {
        return route('defa.enable');
    }

    public function streamVideo($window, $view_key)
    {
        $window = addslashes(strip_tags($window));

        $md5defa = $this->secure_key;
        $t = (int)$view_key;

        $filedefa = str_replace($md5defa, '', \Session::get('file' . $t)); // lay lai video id
        $video_id = base64_decode(base64_decode($filedefa));

        $header = $this->http_response_code();
        $header2 = \Request::header();

        //\Log::alert(print_r($header2, true));

        if (\Session::get(self::WINDOW_PREFIX . $window, null) != null) {
            if ($header == 200
                && implode('',$header2['accept']) != ""
                && \Session::get('x' . $video_id . $t, null) == 0
                && $this->isMobile()
                || isset($_SERVER['HTTP_RANGE'])
            ) {

                \Session::set('x' . $video_id . $t, \Session::get('x' . $video_id . $t) + 1);
                //Written By Juthawong Naisanguansee at Ampare Engine
                if (isset($_SERVER['HTTP_RANGE'])) {
                    \Log::alert($_SERVER['HTTP_RANGE']);
                }


                list($video_disk, $video_path) = $this->extractVideoId($video_id);

                $video_storage = MyStorage::getDisk($video_disk);
                /** @var Local $storage_adapter */
                $storage_adapter = $video_storage->getAdapter();
                if($storage_adapter instanceof Local){
                    $file_path = $storage_adapter->applyPathPrefix($video_path);
                    \Log::alert('mở file ' . $file_path);
                }else{
                    \Log::alert("Không tim thay file");
                }

                $video_streamer = new VideoStreaming($file_path);
                $video_streamer->start();

                die();
            }
            die("Kho^ng support nha T.T !!!");
        }
        die("Co' gi` đo' sai sai");
    }

    private function buildVideoId($video_disk, $video_path)
    {
        return $video_disk . '|||' . $video_path;
    }

    private function extractVideoId($videoID){
        return explode('|||', $videoID);
    }

    /**
     * Kiểm tra trình duyệt điện thoại
     * @return int
     */
    private function isMobile()
    {
        return preg_match(
            "/(MSIE|Edge|android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i",
            $_SERVER["HTTP_USER_AGENT"]
        );
    }

    private function http_response_code($newcode = NULL)
    {
        static $code = 200;
        if ($newcode !== NULL) {
            header('X-PHP-Response-Code: ' . $newcode, true, $newcode);
            if (!headers_sent())
                $code = $newcode;
        }
        return $code;
    }

}