<?php
/**
 * Created by PhpStorm.
 * User: MyPC
 * Date: 25/04/2016
 * Time: 11:02 SA
 */

namespace App\Http\Controllers\Frontend;


use App\Core\PromoCode\ConfigCode;
use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Models\PromoCode;
use App\Models\TrackingLink;
use Illuminate\Http\Request;

class TrackingLinkController extends Controller
{

    public function create(Request $request)
    {
        $discount    =  $request->get('discount');
        $course_id   =	$request->get('course_id');
        $user_id	 =  $request->get('user_id');
        $link_public =  $request->get('link');
        $cou_title   =  $request->get('cou_title');
        $cou_summary =  $request->get('cou_summary');

        if($discount > 0){

            $result = TrackingLink::create([
                'discount'      => $discount,
                'course_id'     => $course_id,
                'user_id'       => $user_id
            ]);

            $link_share  =  $link_public.'?code='.$result->id;
            $html        =  view('includes.partials.create_social_link', compact('link_share', 'cou_title', 'cou_summary'))->render();
            return response()->json(['success' => true, 'link' => $link_share, 'html' => $html]);
        } else {
            return response()->json(['success' => false, 'mess' => 'Kiểm tra lại % chiết khấu']);
        }

    }

    public function listing(){
        $links  =   TrackingLink::where('user_id',\Access::user()->id)->get();
        return view('frontend.user.links.index',compact('links'));
    }

    public function delete($id){
        $code_link     = TrackingLink::findUUID($id);
        $code_link->forceDelete();
        return redirect()->route('frontend.link.listing')->withFlashSuccess('Xóa bản ghi thành công');
    }

}