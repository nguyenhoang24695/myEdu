<?php

namespace App\Http\Controllers\Backend;

use App\Events\Frontend\ReviewChangedEvent;
use App\Models\Reviews;
use App\Repositories\Backend\Reviews\ReviewsContract;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ReviewsController extends Controller
{
    protected $reviews;

    public function __construct(ReviewsContract $reviews)
    {
        $this->reviews = $reviews;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $list_reviews = $this->reviews->getListReviews();

//        var_dump( $list_reviews);
//        dd();
//        return view('backend.reviews.index',compact('list_reviews'));
        return view('backend.reviews.index',compact('list_reviews'));
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
        //
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

    public function active($id){
        $reviewbyid   =  $this->reviews->getById($id);
        $value      =  abs($reviewbyid->rev_active - 1);
        $action = $value == 1 ? "activate" : "deactivate";
        if($this->reviews->update(["rev_active" => $value],["id" => $id])){
            event(new ReviewChangedEvent($reviewbyid, $action));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        /** @var Reviews $reviewbyid */
        $reviewbyid   =  $this->reviews->getById($id);
        if(!$reviewbyid){
            abort(404);
        }
        if($this->reviews->deleteCondition(["id" => $id])){
            if($reviewbyid->rev_active){
                event(new ReviewChangedEvent($reviewbyid, 'deactivate'));
            }
        }
        return redirect()->route('reviews.list')->withFlashSuccess('Xóa bản ghi thành công');
    }
}
