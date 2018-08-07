<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 1/11/16
 * Time: 13:55
 */

namespace App\Http\Controllers\Backend;


use App\Core\Money\Utils\OrderManager;
use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Models\MobileCard;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class MoneyController extends Controller
{
    public function revenueReport()
    {
        $revenue_acc_info = config('money.' . config('app.id') . '.revenue_account');
        \Log::alert("HOCVT13042016");
        \Log::alert($revenue_acc_info);
        $revenue_acc = User::where('id', $revenue_acc_info['id'])
            ->where('email', $revenue_acc_info['email'])
            ->first();
        if(!$revenue_acc){
            throw new GeneralException("Chưa cài đặt đúng tài khoản doanh thu");
        }

        $system_users = [];
        $card_users_info = config('money.'.config("app.id").'.card_account');
        foreach($card_users_info as $_info){
            $system_users[] = User::where('email', $_info['email'])->first();
        }
        $bank_card_users_info = config('money.'.config("app.id").'.bank_card');
        foreach($bank_card_users_info as $_info){
            $system_users[] = User::where('email', $_info['email'])->first();
        }
        $bank_exchange_users_info = config('money.'.config("app.id").'.bank_exchange');
        foreach($bank_exchange_users_info as $_info){
            $system_users[] = User::where('email', $_info['email'])->first();
        }

        // 10 last orders
        $orders = Order::with(['buyingUser', 'sellingUser'])->orderBy('created_at', 'desc')->limit(10)->get();

        // 20 last transaction
        $transactions = Transaction::with(['fromUser', 'toUser'])->orderBy('created_at', 'desc')->limit(20)->get();

        $data = [
            'revenue_acc' => $revenue_acc,
            'system_users' => $system_users,
            'orders' => $orders,
            'transactions' => $transactions,

        ];
        return view('backend.money.revenue_report', $data);

    }

    public function mobileCards(){
        $cards = MobileCard::limit(10)->orderBy('created_at', 'desc')->get();
        $data = [
            'cards' => $cards,
        ];
        return view('backend.money.mobile_cards', $data);
    }

    /**
     * Danh sách các order(khi bán khóa học, nạp thẻ, ...) phát sinh giao dịch tiền
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function orderList(Request $request){
        $order_builder = Order::query()->with('sellingUser');

        /** filters by item */
        $payment_item = $request->query('payment_item', '');
        if(!empty($payment_item)){
            $payment_item = 'App\Models\\' . $payment_item;
            $order_builder = $order_builder->where('item_type', $payment_item);
        }
        /** filters by code */
        $code = $request->query('code', '');
        $code = intval(str_replace(Order::CODE_PREFIX, '', $code));
        if($code > 0){
            $order_builder->where('id', $code);
        }
        /** filters by status */
        $status = $request->query('status', null);
        if($status != null){
            $order_builder->where('status', $status);
        }
        $orders = $order_builder->orderBy('created_at', 'desc')->simplePaginate(20);
        return view('backend.money.order_list', compact('orders'));
    }

    /**
     * Danh sách các giao dịch phát sinh nội bộ(tiền từ các tìa khoản khác nhau)
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function innerTransactionList(Request $request){
        $transaction_builder = Transaction::query();

        //|||||| filter
        // theo mã hóa đơn
        $code = $request->query('code', '');
        $code = intval(str_replace(Order::CODE_PREFIX, '', $code));
        if($code > 0){
            $transaction_builder->where('order_id', $code);
        }
        $id_email = $request->query('id_email', null);
        if($id_email != null){
            $user = User::where(function($query) use($id_email){
                return $query->where('id', $id_email)->orWhere('email',$id_email);
            })->first();
            if($user){
                $user_id = $user->id;
            }else{
                $user_id = intval($id_email);
            }
            $transaction_builder->where(function($query) use($user_id){
                return $query->where('to_acc', $user_id)->orWhere('from_acc',$user_id);
            });
        }
        $wallet_type = $request->query('wallet_type', '');
        if($wallet_type != ''){
            $transaction_builder->where('acc_type', $wallet_type);
        }

        //||||| sắp xếp
        $key = $request->get('key', 'created_at');
        $sort = $request->get('sort', 'desc');
        switch($key){
            case 'order_code':
                $key = 'order_id';
                break;
            case 'wallet_type':
                $key = 'acc_type';
                break;
        }
        $transaction_builder->orderBy($key, $sort);



        //||||| transaction

        $transactions = $transaction_builder->orderBy('created_at', 'desc')->simplePaginate(20);
        return view('backend.money.transaction_list', compact('transactions'));
    }

    public function orderDetail(Request $request, $order_id, $action = 'view'){
        /** @var Order $order */
        $order = Order::find($order_id);
        if(!$order){
            abort(404);
        }

        if($request->isMethod('post')){
            if($action == 'approve'){
                $this->validate($request, [
                    'transaction_id' => 'required',
                ]);
                $order_manager = new OrderManager();
                $res = $order_manager->processOrder($order, $request->all());
                $action = '';
            }elseif($action == 'reject'){
                $order_manager = new OrderManager();
                $res = $order_manager->cancelOrder($order);
                $action = '';
            }else{
                $note = $request->get('note');
                $order->note = $note;
                if($order->save()){
                    return redirect()->route('backend.money.orders.list')->withFlashSuccess("Lưu thông tin thành công!");
                }
                $action = '';

            }

        }

        return view('backend.money.order_detail', compact('order', 'action'));
    }

    public function manualProcessOrder(Request $request){
        $order_manager = new OrderManager();
        if($request->get('action') == 'approve'){

        }
    }
}