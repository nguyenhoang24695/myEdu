<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 12/24/15
 * Time: 10:49
 */

Route::get('test_search', function(){
    echo "<pre>";
    print_r(\App\Core\MyIndexer::getTagIndexInfo(\App\Core\MySearchEngine::indexer('tag')));
    die();
    $tags = \Conner\Tagging\Model\Tag::limit(1000)->get();
    var_dump(\App\Core\SearchEngine\ElasticSearch::indexTags($tags));
});

Route::get('test_parser', function(){
    $string = file_get_contents(storage_path('test/abc.html'));
//    echo "<pre>";
    echo \App\Core\HtmlTools::purify($string, 'br_only');
//    echo "<pre>";
});

Route::get('test_indexer', function(){
//    $se = new \App\Core\SearchEngine\ElasticSearch();
//    $se->createIndex(\App\Core\MySearchEngine::TAGGABLE . ".course");
});

Route::get('get_sub', function(){
    $youtube_id   = "DoxlZWFBLeg";
    //$sub_content  = \App\Core\YoutbeDownloader::getInstance()->getCaption($youtube_id);
    //if($sub_content == ""){
        //$sub_content  = \App\Core\YoutbeDownloader::getInstance()->getCaption($youtube_id,'vtt','en');
    //}
    //echo $sub_content;

    $video = \App\Core\YoutbeDownloader::getInstance()->getSnippetVideo($youtube_id);
    $video_title = array_get($video, 'description');
    dd($video_title);
});

Route::get('add_a_course', function(){
    $start_time = microtime(true);
    $courses = \App\Models\Course::all();
    //dd(\App\Core\MyIndexer::indexTaggables($courses));
    //dd($courses);

    \App\Core\MyIndexer::indexTaggables($courses);
    echo "DONE " . $courses->count() . " record in ";
    echo (microtime(true) - $start_time) . "s";

//    foreach($courses as $course){
//        var_dump(\App\Core\MyIndexer::indexTaggable($course));
//    }
});


Route::get('add_card', function(){
//    $card1 = new \App\Models\MobileCard();
//    $card1->pin = '64643212533918';
//    $card1->serial = '36108200289536';
//    $card1->provider = 'VINA';
//    $card1->save();
    $card = \App\Models\MobileCard::take(1)->first();
    $bk_gate = new \App\Core\Money\BaoKimApi\Payment();
    echo "<pre>";
    $res = $bk_gate->processCard($card);
    echo "\n";
    print_r($res);
});

Route::get('reset_financial', function(){
    \DB::table('orders')->truncate();
    \DB::table('transactions')->truncate();
    \DB::table('transaction_notifications')->truncate();
    \DB::table('mobile_cards')->truncate();
    \App\Models\User::query()->update(['primary_wallet' => 0, 'secondary_wallet' => 0]);
    \App\Models\CourseStudent::where('order_id', '>', 0)->delete();
});

Route::get('seller_info', function(){
    $bk_pro = new \App\Core\Money\BaoKimApi\BaoKimPaymentPro();
    dd($bk_pro->get_seller_info());
});

Route::match(['get', 'post'], 'test_order', function(\Illuminate\Http\Request $request){
    echo "<pre>";
    var_dump($request->query());
    var_dump($request->all());
    die("");
});

//Mua khóa học
Route::get('buy_course', function(){

    $course_id      =   7;
    $course         =   \App\Models\Course::find($course_id);
    $code           =   'T8BSGY';

    $promo_code     =   new \App\Core\PromoCode\PromoCodeManager();
    $promote_code   =   $promo_code->processPromoCode($code,$course);
    dd($promote_code);
});

Route::get('abc/{id}-{title}/{testing?}', function($id, $title, $testing = null){
    dd($testing);
});//->where(['testing' => '^testing$']);

Route::get('video_info', function(){
    /** @var \App\Models\Video $video */
    $video = \App\Models\Video::find(5);
    //$ffprobe = \FFMpeg\FFProbe::create();
    //$video = $ffprobe->streams(\App\Core\MyStorage::getDisk($video->video_disk)->readStream($video->video_file_path));
 //   dd($video);
//    $video->gen_other_formats();
//    dd($video);

    //dd(\App\Core\MediaProcess\MyFFProbe::getMediaInfo($video->video_disk, $video->video_file_path));
    $video->gen_info();
    return "DD";
});


Route::get('refine_transaction/{user_id}', function($user_id){
    /** @var \App\Models\User $user */
    $user = \App\Models\User::findOrFail($user_id);
    $transaction_builder = \App\Models\Transaction::where('acc_type', 'primary');
    $transaction_builder->where(function($query) use($user_id){
        return $query->where('to_acc', $user_id)->orWhere('from_acc',$user_id);
    });
    $transaction_builder = $transaction_builder->orderBy('created_at', 'asc');
    $transactions = $transaction_builder->get();
    $before = 0;
    foreach($transactions as $transaction){
        /** @var \App\Models\Transaction $transaction */
        if($transaction->from_acc == $user_id){
            // nếu là người gửi
            $transaction->from_acc_remain = $before - $transaction->amount;
            $after = $transaction->from_acc_remain;
        }elseif($transaction->to_acc == $user_id){
            // nếu là người nhận
            $transaction->to_acc_remain = $before + $transaction->amount;
            $after = $transaction->to_acc_remain;
        }
        if(!$transaction->save()){
            throw new \Exception("Lỗi lưu");
        }
        $before = $after;
    }
    $user->primary_wallet = $before;
    $user->save();
});
