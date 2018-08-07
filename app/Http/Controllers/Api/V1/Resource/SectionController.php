<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 9/28/15
 * Time: 15:03
 */

namespace App\Http\Controllers\Api\V1\Resource;


use App\Http\Controllers\Api\V1\ApiController;
use App\Models\Section;
use Illuminate\Http\Request;

class SectionController extends ApiController
{
    public function addSection(){
        dd(Request::capture());
        $section = Section::create([
            'sec_title' => $request->input('data[sec_title]', '', true),
            'sec_title' => $request->input('data[sec_sub_title]', '', true),
        ]);
        return $section;
    }

    public function updateSection(Request $request){
        /** @var Section $section */
        $section = Section::find($request->input('data[id]', 0, true));
        if($section){
            $check_save = $section->update([
                'sec_title' => $request->input('data[sec_title]', '', true),
                'sec_title' => $request->input('data[sec_sub_title]', '', true),
            ]);
            if($check_save)return $section;
        }
        return false;
    }

    public function deleteSection(Request $request){
        /** @var Section $section */
        if(Section::whereId($request->input('data[id]', 0, true))->exists()){
            return true;
        }
        return false;
    }
}