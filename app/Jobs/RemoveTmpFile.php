<?php

namespace App\Jobs;

use App\Core\MyStorage;
use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use League\Flysystem\Plugin\ListWith;

class RemoveTmpFile extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $file_path;
    private $tmp_disk;
    private $time_out = 60;// 60s

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($file_path, $tmp_disk = 'tmp')
    {
        //
        $this->file_path = $file_path;
        $this->tmp_disk = $tmp_disk;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
//        //
//        if(config('queue.default') == 'sync'){
//            $this->removeOldFile();
//        }else{
//            $disk = MyStorage::getDisk($this->tmp_disk);
//            if($disk->has($this->file_path)){
//                $disk->delete($this->file_path);
//            }
//        }

    }

    private function removeOldFile(){
//        $disk = MyStorage::getDisk($this->tmp_disk);
//        $all_file = $disk->addPlugin(new ListWith())->listWith(['timestamp'], '', true);
//        $max_time = time() - config('queue.tmp_timeout'); // 1 tiếng trước
//        foreach($all_file as $k => $v){
//            /** $v File */
//            if($v['type'] == 'file' && $v['timestamp'] < $max_time)
//            {
//                $disk->delete($v['path']);
//            }
//        }
    }
}
