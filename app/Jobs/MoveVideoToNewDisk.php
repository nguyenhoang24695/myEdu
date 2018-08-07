<?php

namespace App\Jobs;

use App\Core\MyStorage;
use App\Jobs\Job;
use App\Models\Video;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use League\Flysystem\FileExistsException;
use League\Flysystem\FileNotFoundException;

class MoveVideoToNewDisk extends Job implements SelfHandling, ShouldQueue
{
    /** @var  Video */
    private $video;
    private $new_disk;

    public function __construct($video_id, $new_disk = '')
    {
        //
//        $video = Video;
//        if($new_disk == 'tmp'){
//
//        }
        $this->video = Video::find($video_id);
        if(!$this->video && $this->attemps() < 10){
            throw new FileNotFoundException("Video file không tồn tại, video_id = " . $video_id);
        }
        $this->new_disk = $new_disk;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \Log::alert("Moving....");
        if($this->new_disk == $this->video->video_disk)return;
        //
        $old_disk = MyStorage::getDisk($this->video->video_disk);
        try{
            $new_disk = MyStorage::getDisk($this->new_disk);
        }catch (\Exception $ex){
            \Log::error($ex->getMessage());
            $new_disk = MyStorage::getDefaultDisk();
            $this->new_disk = config('flysystem.default_video', config('flysystem.default'));
        }
        if(!$old_disk->has($this->video->video_file_path)){
            \Log::error("Không tồn tại tập tin : " . $this->video->video_file_path);
            return;
        }

        if($old_disk->get($this->video->video_file_path)->isDir()){
            \Log::error("Không thể di chuyển thư mục, chỉ áp dụng cho tập tin. Path: " . $this->video->video_file_path);
            return;
        }
        try{
            $copied = $new_disk->putStream($this->video->video_file_path, $old_disk->readStream($this->video->video_file_path));
            if($copied){
                $this->video->video_disk = $this->new_disk;
                if($this->video->save()){
                    $old_disk->delete($this->video->video_file_path);
                }else{
                    throw new \Exception("Không di chuyển được tập tin sang ổ đĩa mới : '".$this->new_disk."'");
                }
            }
        }catch (FileExistsException $ex){
            $this->video->video_disk = $this->new_disk;
            if($this->video->save()){
                $old_disk->delete($this->video->video_file_path);
            }else{
                throw new \Exception("Không di chuyển được tập tin sang ổ đĩa mới : '".$this->new_disk."'");
            }
        }catch (FileNotFoundException $ex){
            \Log::error($ex->getMessage());
        }catch(\Exception $ex){
            if($this->attemps() < 10){
                throw $ex;
            }
        }

    }
}
