<?php
/**
 * Created by PhpStorm.
 * User: MyPC
 * Date: 24/05/2016
 * Time: 2:17 CH
 */

namespace App\Core;


class YoutbeDownloader
{
    private static $endpoint = "http://www.youtube.com/get_video_info";
    private static $caption  = "https://www.youtube.com/api/timedtext";

    public static function getInstance()
    {
        static $instance = null;
        if (null === $instance) {
            $instance = new static();
        }

        return $instance;
    }

    function curlGetInfoVideo($URL)
    {
        $ch = curl_init();
        $timeout = 3;
        curl_setopt($ch, CURLOPT_URL, $URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $tmp = curl_exec($ch);
        curl_close($ch);
        return $tmp;
    }

    public function getLink($id)
    {
        $API_URL = self::$endpoint . "?&video_id=" . $id;
        $video_info = $this->curlGetInfoVideo($API_URL);

        $url_encoded_fmt_stream_map = '';
        parse_str($video_info);
        if(isset($reason))
        {
            return $reason;
        }
        if (isset($url_encoded_fmt_stream_map)) {
            $my_formats_array = explode(',', $url_encoded_fmt_stream_map);
        } else {
            return 'No encoded format stream found.';
        }
        if (count($my_formats_array) == 0) {
            return 'No format stream map found - was the video id correct?';
        }
        $avail_formats[] = '';
        $i = 0;
        $ipbits = $ip = $itag = $sig = $quality = $type = $url = '';
        $expire = time();
        foreach ($my_formats_array as $format) {
            parse_str($format);
            $avail_formats[$i]['itag'] = $itag;
            $avail_formats[$i]['quality'] = $quality;
            $type = explode(';', $type);
            $avail_formats[$i]['type'] = $type[0];
            $avail_formats[$i]['url'] = urldecode($url) . '&signature=' . $sig;
            parse_str(urldecode($url));
            $avail_formats[$i]['expires'] = date("G:i:s T", $expire);
            $avail_formats[$i]['ipbits'] = $ipbits;
            $avail_formats[$i]['ip'] = $ip;
            $i++;
        }
        return $avail_formats;
    }

    public function getCaption($id, $fmt = 'vtt',$lang = 'vi'){
        $API_URL        = self::$caption . "?v=" . $id . "&lang=" . $lang . "&fmt=" . $fmt;
        $caption_info   = file_get_contents($API_URL);
        return $caption_info;
    }

    //lấy thông tin title, mô tả video
    public function getSnippetVideo($id){
        $client = new \Google_Client();
        $client->setDeveloperKey(config('app.youtube_server_key'));
        $youtube = new \Google_Service_YouTube($client);

        $video = $youtube->videos->listVideos('snippet', [
            'id' => $id,
        ])->getItems()[0];

        return $video->getSnippet();
    }

    public function getTitle($id){
        $video  =   $this->getSnippetVideo($id);
        return array_get($video, 'title');
    }

    public function getDescription($id){
        $video  =   $this->getSnippetVideo($id);
        return array_get($video, 'description');
    }
}