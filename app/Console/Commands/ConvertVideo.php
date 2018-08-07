<?php

namespace App\Console\Commands;

use App\Core\MediaProcess\MyFFMpeg;
use App\Models\Video;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

class ConvertVideo extends Command
{
    const __RE_SD = -6; // cần convert lại
    const __RE_HD = -5; // cần convert lại
    const __GEN_INFO = -3; // cần gen lại info
    const __RE = -2; // cần convert lại
    const __ING = -1; // đang convert
    const __WAITING = 0; // chờ convert
    const __OK = 1; // convert thành công
    const __FAILS = 2; // convert lỗi


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'videos:convert
                            {--ids=all : cac id cach nhau dau , hoac all }
                            {--idmin=0 : id nho nhat can thao tac }
                            {--idmax=0 : id lon nhat can thao tac}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $ids =$this->option('ids');
        if($ids != 'all'){
            $ids = explode(',', $ids);
        }
        $idmin = intval($this->option('idmin'));
        $idmax = intval($this->option('idmax'));
        $idmax = $idmax > 0 ? $idmax : PHP_INT_MAX;

        /** @var Builder $video_builder */
        $video_builder = Video::query();

        if($ids == 'all'){
            $video_builder = $video_builder->where('id', '>=', $idmin);
            $video_builder = $video_builder->where('id', '<=', $idmax);
        }else{
            $video_builder = $video_builder->whereIn('id', $ids);
        }
        // video chưa nhập tiêu đề(mới upload chưa nhập thông tin) không convert
        $video_builder = $video_builder->whereRaw('updated_at != created_at');

        // convert toàn bộ các video chưa convert hoặc cần convert lại
        $video_builder = $video_builder->where('convert_status' , '<', 1);
        $video_builder->orderBy('updated_at', 'asc');
        $count = 0;
        $video_builder->chunk(1, function($videos) use($count){
            foreach($videos as $video){
                $count++;
                if(!self::should_convert($video)){
                    return;
                }
                self::convert($video);
            }
        });
        if($count){
            echo "\n[" . date( 'Y-m-d H:i:s' ) . "] Processed " . $count . " video(s)";
        }
    }

    /**
     * Kiểm tra xem video hiện tại đủ điều kiện để luồng hiện tại xử lý không
     *
     * @param Video $video
     *
     * @return bool
     */
    public static function should_convert( Video $video ) {
        //
        if($video->convert_status == self::__ING){
            // nếu video được đưa vào xử lý trước đó khoảng thời gian ít hơn timeout của ffmpeg thì không xử lý
            // cộng thêm 5s để PHP xử lý thao tác sau khi hết timeout của ffmpeg nếu video convert quá lâu
            if($video->updated_at->diffInSeconds() < config('ffmpeg.binaries.c_timeout') + 5){
                return false;
            }
        }
        return true;
    }

    public static function convert( Video $video ) {

        echo "\n[" . date('Y-m-d H:i:s') . "] Converting ... ==vid" . $video->id . "==";
        if($video->gen_info(true)){
            echo "\n[" . date('Y-m-d H:i:s') . "] Gen INFO OK ";
        }
        if($video->convert_status == self::__GEN_INFO){
            // chỉ gen lại info : cover, time
            $video->convert_status = self::__OK;
            $video->save();
            return;
        }
        echo $video->gen_other_formats();

    }
}
