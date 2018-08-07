<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 10/6/15
 * Time: 08:44
 */

namespace App\Commands;


use App\Models\Video;

class VideoInfoGetting extends Command
{
    /**
     * Create a new command instance.
     *
     * @param Video $video
     * @param array $data
     */
    public function __construct(Video $video, $data = [])
    {
        //
        //$this->request = $request;
        $this->set_data($data);
    }

    /**
     * Execute the command.
     *
     * @throws SubActionNotSupportException
     */
    public function handle()
    {

    }

    private function genThumbnail(){

    }

    private function getTime(){

    }

    private function getResolution(){

    }
}