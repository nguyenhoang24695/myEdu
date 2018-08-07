<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 10/16/15
 * Time: 16:37
 */

namespace App\Jobs;


use App\Core\MyStorage;
use App\Models\Document;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use League\Flysystem\FileExistsException;

class MoveDocumentToNewDisk extends Job implements SelfHandling, ShouldQueue
{
    /** @var  Document */
    private $document;
    private $new_disk;

    public function __construct($document_id, $new_disk = '')
    {

        /** @var  Document document */
        $this->document = Document::find($document_id);
        if(!$this->document && $this->attemps() < 10){
            throw new FileNotFoundException("Video file không tồn tại, video_id = " . $document_id);
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
        if($this->new_disk == $this->document->doc_disk)return;
        //
        $old_disk = MyStorage::getDisk($this->document->doc_disk);
        try{
            $new_disk = MyStorage::getDisk($this->new_disk);
        }catch (\Exception $ex){
            \Log::error($ex->getMessage());
            $new_disk = MyStorage::getDefaultDisk();
            $this->new_disk = config('flysystem.default');
        }
        if($old_disk->get($this->document->doc_file_path)->isDir()){
            \Log::error("Không thể di chuyển thư mục, chỉ áp dụng cho tập tin. Path: " . $this->document->doc_file_path);
            return;
        }
        try{
            $copied = $new_disk->writeStream($this->document->doc_file_path, $old_disk->readStream($this->document->doc_file_path));
            if($copied){
                $this->document->doc_disk = $this->new_disk;
                if($this->document->save()){
                    $old_disk->delete($this->document->doc_file_path);
                }else{
                    throw new \Exception("Không di chuyển được tập tin sang ổ đĩa mới : '".$this->new_disk."'");
                }
            }
        }catch (FileExistsException $ex){
            $this->document->doc_disk = $this->new_disk;
            if($this->document->save()){
                $old_disk->delete($this->document->doc_file_path);
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