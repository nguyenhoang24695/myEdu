<?php

namespace App\Http\Controllers\Backend;

use App\Core\PromoCode\InnerPromoCode;
use App\Models\PromoCode;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class PromoCodeController extends Controller
{
    protected $code;

    public function __construct(InnerPromoCode $code)
    {
        $this->code = $code;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($module,Request $request)
    {
        $code      =  $request->get('search');
        if($module == "list"){
            $param =  ['active' => 1, 'code' => ['operator' => 'LIKE','value' => "%%$code%%"]];
            $all_code  =  $this->code->getAllWithPaginate($param);
        } elseif ($module   == "pause") {
            $param =  ['active' => 0, 'code' => ['operator' => 'LIKE','value' => "%%$code%%"]];
            $all_code  =  $this->code->getAllWithPaginate($param);
        } elseif ($module == "deleted") {
            $all_code = $this->code->getAllWithOnlyTrashedPaginate();
        } else {
            return redirect()->route('backend.code.module',['module'=>'list']);
        }
        return view('backend.promo_code.index',compact('all_code','module'));
    }

    /**
     * Test tạo mã code ngẫu nhiên
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $code = $this->code->createCode(1);
        dd($code);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     *
     */
    public function active($id){
        $code_info   =  $this->code->getById($id);
        $value       =  abs($code_info->active - 1);
        $code_info->active = $value;
        $code_info->save();
        return response()->json(['a' => 'thành công', 'b' => 'Không thành công'],200);
    }

    /**
     * Remove the specified resource from storage.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $code_info   =  $this->code->getById($id);
        $code_info->delete();
        return redirect()->route('backend.code.module',['module'=>'list']);
    }

    public function restore($id)
    {
        $code_info   =  PromoCode::withTrashed()->find($id);
        $code_info->restore();
        return redirect()->route('backend.code.module',['module'=>'list'])->withFlashSuccess('Khôi phục mã code thành công');
    }
}
