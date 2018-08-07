<?php

namespace App\Http\Controllers\Frontend;

use App\Events\Frontend\NotifyWhenDiscussions;
use App\Events\Frontend\SendEmailNotificationEvent;
use App\Events\Frontend\SendNotificationEvent;
use App\Models\Course;
use App\Models\User;
use App\Repositories\Frontend\Discussion\DiscussionContract;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class DiscussionController extends Controller
{
    protected $discussion;
    public function __construct(DiscussionContract $discussion)
    {
        $this->discussion = $discussion;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $title   = $request->get('title');
        $content = $request->get('content');
        $cou_id  = $request->get('cou_id');
        $content_id  = $request->get('content_id');

        if($content == ""){
            return response()->json(['message' => 'Hãy nhập nội dung trước khi thảo luận'], 500);
        }

        $data = $this->discussion->create([
              'title'    => $title
            , 'content'  => $content
            , 'user_id'  => auth()->user()->id
            , 'cou_id'   => $cou_id
            , 'content_id'   => $content_id
            , 'active'   => 1
        ]);

        //Gửi notify
        event(new NotifyWhenDiscussions($data));

        if ($request->ajax()) {
            return \Response::json($data);
        }

        return $data;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeReply(Request $request){
        $content = $request->get('content');
        $cou_id  = $request->get('cou_id');
        $content_id  = $request->get('content_id');
        $parent  = $request->get('parent_id');
        $title   = "";

        if($content == ""){
            return response()->json(['message' => 'Hãy nhập nội dung trước khi thảo luận'], 500);
        }

        $dis_by_id = $this->discussion->getById($parent);
        if($dis_by_id->parent_id > 0){
            return response()->json(['message' => 'Lỗi rồi'], 500);
        }

        $data = $this->discussion->create([
              'title'    => $title
            , 'content'  => $content
            , 'user_id'  => auth()->user()->id
            , 'cou_id'   => $cou_id
            , 'content_id'   => $content_id
            , 'active'   => 1
            , 'parent_id'=> $parent
        ]);

        event(new NotifyWhenDiscussions($data,'reply'));

        if ($request->ajax()) {
            return \Response::json($data);
        }

        return $data;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeVoteUp(Request $request){
        $id = $request->get('id');
        $discussions = $this->discussion->getById($id);
        if($discussions->parent_id > 0){
            return response()->json(['message' => 'Lỗi rồi'], 500);
        }
        $data = $this->discussion->voteUp($id);
        $vote = $discussions->vote_up+1;
        $out  = ['vote' => $vote];

        //Notify
        $course             =  Course::find($discussions->cou_id);
        $obj_related		=  $course;
        $obj_sender         =  User::find(config('notification.obj_send.id'));
        $obj_user			=  User::find($discussions->user_id);
        $data_like['type']       =  "message";
        $data_like['subject']    =  "Bình luận <strong>".$discussions->content."</strong> của bạn được yêu thích";

        $tem_type                =  config('notification.template.discussions.like.key');
        $data_like['body']       =  view('emails.notification.template',compact('tem_type','course','discussions'))->render();
        $data_like['bodyMail']   =  view('emails.notification.email',compact('tem_type','obj_user','course','discussions'))->render();
        $data_like               =  json_decode(json_encode ($data_like), FALSE);

        event(new SendNotificationEvent($obj_user,$obj_sender,$obj_related,$data_like));
        event(new SendEmailNotificationEvent($obj_user,$data_like));

        if ($request->ajax()) {
            return \Response::json($out);
        }
        return $data;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeReport(Request $request){
        $id = $request->get('id');
        $dis_by_id = $this->discussion->getById($id);
        $data = $this->discussion->report($id);

        if ($request->ajax()) {
            return \Response::json(['message' => "Nội dung đang được kiểm duyệt"]);
        }
        return $data;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
