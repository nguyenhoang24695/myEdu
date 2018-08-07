<?php

namespace App\Http\Controllers\Frontend;

use App\Events\Frontend\ReviewChangedEvent;
use App\Models\Reviews;
use App\Repositories\Frontend\Reviews\ReviewsContract;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Requests\Frontend\Reviews\StoreReviewsRequest;
use App\Http\Controllers\Controller;

class ReviewsController extends Controller
{
    /** @var ReviewsContract  */
    private $reviews;
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
        //
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
    public function store(StoreReviewsRequest $request)
    {
        $rev_content = $request->get("rev_content");
        $rating      = $request->get("rating");
        $rev_cou_id  = $request->get("rev_cou_id");
        $rev_active  = config('reviews.reviews_default_active');

        $data = Reviews::create([
                                          'rev_content'  => $rev_content
                                        , 'rating'       => $rating
                                        , 'rev_user_id'  => auth()->user()->id
                                        , 'rev_cou_id'   => $rev_cou_id
                                        , 'rev_active'   => $rev_active
                                        ]);
        if($rev_active > 0){
            event(new ReviewChangedEvent($data, 'activate'));
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

    public function genRatingWithCourse($cou_id){
        $count_rate = $this->reviews->getCountRating($cou_id);
        $avg_rate   = $this->reviews->getAvgRating($cou_id,$count_rate);
        $total_vote = '<span class="num-vote">('.$count_rate.')</span>';
        return $this->reviews->getHtmlRating($avg_rate).$total_vote;
    }
}
