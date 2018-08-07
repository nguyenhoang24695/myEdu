<?php
/**
 * Lớp này thực hiện các giao dịch ở mức nhỏ, tức là chỉ đơn thuần là tiền từ tài khoản A sang tài khoản B
 * User: hocvt
 * Date: 1/10/16
 * Time: 23:23
 */

namespace App\Core\Money\Utils;


use App\Core\Money\Contracts\WalletContract;
use App\Core\Money\Exceptions\MinusWalletException;
use App\Core\Money\Exceptions\WrongOperationException;
use App\Models\Order;
use App\Models\Transaction;

class InnerTransactionManager
{
    /**
     * Chuyển tài khoản doanh thu(primary)
     * @param WalletContract $wallet_send
     * @param WalletContract $wallet_receipt
     * @param $amount
     * @param Order $order_id
     * @param bool|false $minus_able
     * @return bool
     * @throws MinusWalletException
     * @throws WrongOperationException
     */
    public function transfer(WalletContract $wallet_send, WalletContract $wallet_receipt, $amount, Order $order = null, $minus_able = false)
    {
        if($amount < 0){
            throw new WrongOperationException("Lượng tiền chuyển giao không được âm");
        }
        if(!$minus_able){
            if($wallet_send->primaryAmount() < $minus_able){
                throw new MinusWalletException("Tài khoản gửi không được âm sau khi chuyển khoản");
            }
        }
        $transaction = new Transaction();
        $transaction->from_acc = $wallet_send->id;
        $transaction->acc_type = "primary";
        $transaction->amount = $amount;
        $transaction->from_acc_remain = $wallet_send->primaryOutcome($amount);
        $transaction->order_id = $order->id;
        $transaction->to_acc = $wallet_receipt->id;
        $transaction->to_acc_remain = $wallet_receipt->primaryIncome($amount);
        $transaction->order_status = $order->status;
        $transaction->type = $order->type;
        return $transaction->save();

    }

    /**
     * @param WalletContract $walletContract
     * @param $amount
     * @param Order $order
     * @return bool
     * @throws WrongOperationException
     */
    public function increaseSecondaryWallet(WalletContract $walletContract, $amount, Order $order){
        if($amount < 0){
            throw new WrongOperationException("Lượng tiền chuyển giao không được âm");
        }
        $transaction = new Transaction();
        $transaction->from_acc = 0;
        $transaction->acc_type = "secondary";
        $transaction->amount = $amount;
        $transaction->from_acc_remain = 0;
        $transaction->order_id = $order->id;
        $transaction->to_acc = $walletContract->id;
        $transaction->to_acc_remain = $walletContract->secondaryIncome($amount);
        $transaction->order_status = $order->status;
        $transaction->type = $order->type;
        return $transaction->save();
    }

    public function decreaseSecondaryWallet(WalletContract $walletContract, $amount, Order $order){
        if($amount < 0){
            throw new WrongOperationException("Lượng tiền chuyển giao không được âm");
        }
        if($walletContract->secondaryAmount() < $amount){
            throw new WrongOperationException("Số dư nhỏ hơn số tiền cần thanh toán");
        }
        $transaction = new Transaction();
        $transaction->from_acc = $walletContract->id;
        $transaction->from_acc_remain = $walletContract->secondaryOutcome($amount);
        $transaction->acc_type = "secondary";
        $transaction->amount = $amount;
        $transaction->order_id = $order->id;
        $transaction->to_acc = 0;
        $transaction->to_acc_remain = 0;
        $transaction->order_status = $order->status;
        $transaction->type = $order->type;
        return $transaction->save();
    }
}