<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 2/17/16
 * Time: 15:52
 */

namespace App\Http\Controllers\Backend;


use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class WalletsController extends Controller
{
    private $default_sort_key = 'id';
    private $default_sort_direction = 'asc';

    public function index(Request $request)
    {
        $builder = User::query();
        // append filter
        if($code = $request->get('user_code')){
            $builder->where('id', $code);
        }
        if($email = $request->get('user_email')){
            $builder->where('email', $email);
        }
        if($phone = $request->get('user_phone')){
            $builder->where('phone', $phone);
        }
        if($key = $request->get('key', $this->default_sort_key)){
            $sort = $request->get('sort', $this->default_sort_direction);
            $builder->orderBy($key, $sort);
        }


        $wallets = $builder->simplePaginate();

//        var_dump($wallets);die();

        return view('backend.wallets.index', [
            'wallets' => $wallets,

        ]);

    }

    public function validate_wallet(Request $request, $user_id){
        $max_transaction = 1000;
        $start_time = 0;
        $end_time = 0;
        $user = User::find($user_id);
        if(!$user){
            abort(404);
        }
        $transaction_builder = Transaction::query();
        $transaction_builder->where(function($query) use($user_id){
            return $query->where('to_acc', $user_id)->orWhere('from_acc',$user_id);
        });
        if($start_time != 0){
            $transaction_builder = $transaction_builder->where('created_at', '>=', $start_time);
        }
        if($end_time != 0){
            $transaction_builder = $transaction_builder->where('created_at', '<=', $start_time);
        }
        $transaction_builder = $transaction_builder->orderBy('created_at', 'asc');
        if($transaction_builder->count() < $max_transaction){
            $transactions = $transaction_builder->get();
        }else{
            $transactions = [];
            throw new GeneralException("Chọn khoảng thời gian ngắn lại, chỉ xác thực cho tối đa " . $max_transaction . " transaction");
        }

        return view('backend.wallets.validate', [
            'transactions' => $transactions,
            'user' => $user,
        ]);

    }
}