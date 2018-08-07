<?php namespace App\Http\Controllers\Frontend;

use App\Core\Money\BaoKimApi\BaoKimPaymentPro;
use App\Core\Money\BaoKimApi\Payment;
use App\Core\Money\Exceptions\UnSupportedProviderException;
use App\Core\Money\Utils\Constant;
use App\Core\Money\Utils\InnerTransactionManager;
use App\Core\Money\Utils\TransactionManager;
use App\Core\PromoCode\PromoCodeManager;
use App\Events\Frontend\SendEmailNotificationEvent;
use App\Events\Frontend\SendNotificationEvent;
use App\Http\Controllers\Controller;
use App\Models\NotificationSetting;
use App\Models\Partner;
use App\Models\PromoCode;
use App\Repositories\Frontend\Course\Cods\CourseCodsContract;
use App\Repositories\Frontend\Notification\NotificationContract;
use App\Models\BankPayment;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Validation\ValidationException;
use Illuminate\Http\Exception\HttpResponseException;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Requests\Frontend\User\Becometeacher;
use App\Repositories\Frontend\User\UserContract;
use App\Core\MyStorage;
use App\Http\Controllers\Api\V1\Resource\ResourceController;
use Image;
use Input;
use Redirect;

/**
 * Class DashboardController
 * @package App\Http\Controllers\Frontend
 */
class DashboardController extends Controller {

	/** @var UserContract  */
	private $user;
	protected $arr_resize = ["larg"=>["W"=>200,"H"=>200],"medium"=>["W"=>100,"H"=>100]];
    protected $path       = "users/";
	protected $notify;
    public $course_cod;
    private $inner_transaction_manager;
    protected $promo_code;

	public function __construct(UserContract $user,NotificationContract $notificationContract, CourseCodsContract $courseCodsContract)
	{
		\SEO::setTitle(trans('meta.dashboard_title'));
		$this->user		=	$user;
		$this->notify 	= 	$notificationContract;
		$this->course_cod = $courseCodsContract;
        $this->inner_transaction_manager = new InnerTransactionManager();
        $this->promo_code                = new PromoCodeManager();
	}

	public function index()
	{
		if(\Access::hasRole(config('access.role_list.teacher'))){
			return redirect()->intended('/teacher/my_courses');
		} else {
			return redirect()->intended('/course/studying');
		}
	}

	public function module($module,Request $request){
		//Mảng chứa module cần dùng
		$arr_module		=	['notification','notification_setting','setting','notification_detail'];
		$user_id		=	auth()->user()->id;
		$user 			=	$this->user->findOrThrowException($user_id);
		$notify_size	=	20;
		$notifications  =	[];
		$notify_detail	= 	[];
		$notify_setting	=	[];

		switch($module){
			case 'setting':
				\SEO::setTitle(trans('meta.dashboard.setting'));
				break;
			case 'notification':
				\SEO::setTitle(trans('meta.dashboard.notification'));
				$notifications = $user->notifications()->orderby('id','DESC')->paginate($notify_size);
				break;
			case 'notification_setting':
				\SEO::setTitle(trans('meta.dashboard.notification_setting'));
				$notify_setting = $user->notificationSetting;
				break;
			case 'notification_detail':
				\SEO::setTitle(trans('meta.dashboard.notification'));
				$notify_id 		= $request->get('id');
				$notify_detail  = $this->notify->isMarkReadNotification($notify_id);
				$arr_key		= ['/{name}/'];
				$arr_val		= ['<b>'.$user->name.'</b>'];
				$body_detail 	= preg_replace($arr_key, $arr_val, $notify_detail->body);
				break;
			default:
				abort(404);
				break;
		}
		return view('frontend.user.dashboard',compact('module','arr_module','notifications','notify_detail','body_detail','notify_setting'))
			->withUser(auth()->user());
	}

	public function financial(){

        javascript()->put([
            'user_financial_report_link' => route('user.financial.transaction_report')
        ]);

		$user = auth()->user();
		$wait_process_orders = Order::where('seller', $user->id)
			->where('status', Constant::PENDING_ORDER)
			->orderBy('created_at', 'desc')
			->get();
		$code_info		=	PromoCode::where('user_id',$user->id)->first();
		//Kiểm tra partner
		$partner 		=	new Partner();
		$check_partner 	= 	$partner->check($user->id);
		$data = [
			'user' => $user,
			'check_partner'	=> $check_partner,
			'wait_process_orders' => $wait_process_orders,
			'code_info'	=> $code_info
		];
		return view('frontend.user.financial', $data );
	}

    public function transaction_report(Request $request){
        $transaction_builder = Transaction::query()->with('order');
        $user_id = auth()->user()->id;
        $transaction_builder->where(function($query) use($user_id){
            $query->where('from_acc', $user_id)
                  ->orWhere('to_acc', $user_id);
        });

        if($request->has('primary')){
            $transaction_builder->where('acc_type', 'primary');
        }else if($request->has('secondary')){
            $transaction_builder->where('acc_type', 'secondary');
        }

        $transactions = $transaction_builder->orderBy('created_at', 'desc')
			->simplePaginate();

        return response()->json([
            'success' => true,
            'html' => view('frontend.user.includes.transaction_table', [
                'transactions' => $transactions,
                'my_id' => $user_id,
            ])->render()
        ]);

    }

	public function recharge(Request $request){

		if($request->isMethod('post')){
			try{

				$rules = [
					'recharge_by' => 'required'
				];

				/*if(!config('app.debug') || 1){
					$rules['g-recaptcha-response'] = 'required|captcha';
				}*/

				if(\Session::get('captcha_ok', false) && env('APP_ENV') != 'local'){
					$this->validate($request, $rules);
				}

				\Session::set('captcha_ok', true);

				$recharge_by = $request->get('recharge_by');

				if($recharge_by == 'mobile_card'){
					$return = $this->rechargeByMobileCard($request);
				}elseif($recharge_by == 'bank_card'){
					$return =  $this->rechargeByBankCard($request);
				}elseif($recharge_by == 'bank_exchange'){
					$return =  $this->rechargeByBankExchange($request);
                }elseif($recharge_by == 'COD'){
                    $return =  $this->rechargeByCOD($request);
				}
				return $return;
			}catch (\Exception $ex){
				if($ex instanceof HttpResponseException){
					throw $ex;
				}
				return response()->json([
					'success' => false,
					'message' => $ex->getMessage()
				]);
			}

		}else{
			\Session::set('captcha_ok', false);
		}

		javascript()->put([
			'recharge_by_mobile_card_link' => route('user.financial.recharge_by_card'),
			'recharge_by_bank_card_link' => route('user.financial.recharge_by_bank_card'),
			'recharge_by_bank_exchange_link' => route('user.financial.recharge_by_bank_exchange'),
            'recharge_by_COD_link' => route('user.financial.recharge_by_COD'),
			'recharge_post_link' => route('user.financial.recharge'),
		]);

		$data = [
			'user' => auth()->user(),
		];

		return view('frontend.user.recharge', $data );
	}

	/**
	 * @param Request $request
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
	 * @throws UnSupportedProviderException
	 */
    public function rechargeByMobileCard(Request $request){
		if($request->isMethod('post')){
			$this->validate($request, [
				'mobile_card_pin' => 'required|digits_between:10,15',
				'mobile_card_serial' => 'required|digits_between:10,15',
				'mobile_card_provider' => 'required',
			]);
			$transaction_manager = new TransactionManager();
			$res = $transaction_manager->rechargeByMobileCard(auth()->user(),
				$request->get('mobile_card_pin'),
				$request->get('mobile_card_serial'),
				$request->get('mobile_card_provider'));
			if($res['success'] == true){
				return response()->json([
					'success' => true,
					'message' => $res['message'],
					'next_link' => route('user.financial.review'),
				]);
			}else{
				return response()->json([
					'success' => false,
					'message' => $res['message']
				]);
			}
		}elseif($request->isMethod('get')){
			$data = [
				'user' => auth()->user(),
			];

			return view('frontend.user.recharge_by_mobile_card', $data );
		}

    }

	public function rechargeByBankCard(Request $request){

		if($request->isMethod('post')){
			$this->validate($request, [
				'bank_direct.amount' => 'required|numeric|min:10000',
				'bank_direct.name' => 'required',
				'bank_direct.email' => 'required|email',
				'bank_direct.phone' => 'required|digits_between:9,13',
				'bank_direct.address' => 'required',
				//'bk_bank_id' => 'required|numeric',
				//'bk_bank_name' => 'required',
				//'bk_id' => 'required|numeric',
			]);

			$bank_info = [
				'bank_gate' => '1_pay',
				/*'bank_id' => $request->get('bk_id'),
				'bank_name' => $request->get('bk_bank_name', ''),
				'bank_payment_method' => 'direct',// thanh toán trực tiếp online
				'bank_payment_method_id' => $request->get('bk_id'),*/

                'bank_payment_method' => 'direct',// thanh toán trực tiếp online
				'payer_name' => $request->get('bank_direct[name]', null, true),
				'payer_email' => $request->get('bank_direct[email]', null, true),
				'payer_phone_no' => $request->get('bank_direct[phone]', null, true),
				'payer_address' => $request->get('bank_direct[address]', null, true),
				//'back_link' 	=> $request->get('back_link', route('home')),
			];

			$amount = $request->get('bank_direct[amount]', null, true);
			$tran_manager = new TransactionManager();
			$res = $tran_manager->rechargeByBankATM(auth()->user(), $amount, $bank_info);
			//return response()->json($res);
            return response()->json([
                'success' => true,
                'message' => 'Redirecting...',
                'next_link' => $res,
            ]);

		}elseif($request->isMethod('get')){

			/*$by_local_card = 1;// thanh toan bang the noi dia
			$by_credit_card = 2; // thanh toan bang the quoc te
			$atm_transfer = 4;
			// Thank toán qua Bảo Kim
			$bk_pro = new BaoKimPaymentPro();
			$bank_list = $bk_pro->get_seller_info();*/
			$data = [
				'user' => auth()->user(),
				/*'local_cards' => $bk_pro->filterBankList($bank_list, $by_local_card),
				'credit_cards' => $bk_pro->filterBankList($bank_list, $by_credit_card),
				'atm_transfer' => $bk_pro->filterBankList($bank_list, $atm_transfer)*/
			];
			return view('frontend.user.recharge_by_bank_card', $data );
		}
	}

	public function rechargeByBankExchange(Request $request){
		if($request->isMethod('post')){

			// save thong tin
//			bk_amount
//bk_bank_id
//110
//bk_bank_name
//Ngân hàng Sài Gòn Thương Tín
//bk_id
//98

			//[bank_id, bank_gate, bank_name, bank_short_name,
//			*                    bank_account_name, bank_account_number, bank_payment_method]

//			'name' => 'UNIBEE',
//            'account' => '1232432543525431543',
//            'logo' => 'https://www.baokim.vn/application/uploads/banks/35_1258746886.jpg',
//            'bank_name' => 'Ngân hàng Công thương Việt Nam'

			$this->validate($request, [
				'bank_exchange.amount' => 'required|numeric|min:10000',
				'bank_exchange.name' => 'required',
				'bank_exchange.email' => 'required|email',
				'bank_exchange.phone' => 'required|digits_between:9,13',
				'bank_exchange.address' => 'required',
				'my_bank_card' => 'required',
			]);

			$my_bank_card = config('money.'.config("app.id").'.bank_cards.' . $request->get('my_bank_card'), null);
			if($my_bank_card == null){
				return response()->json([
					'success' => false,
					'message' => 'Không thể thực hiện thanh toán do chưa cấu hình nhận qua tài khoản đã chọn. Mã lỗi EMPTY_MY_CARD:'
						. $request->get('my_bank_card'),
				]);
			}
			$bank_info = [
				'bank_gate' => 'manual',// cổng thanh toán
				'bank_payment_method' => 'guide',// hiển thị hướng dẫn thanh toán chuyển khoản
				'bank_payment_method_id' => 0,//
				'bank_id' => '',// id ngân hàng nếu sử dụng bảo kim, là ngân hàng khách hàng lựa chọn để nạp tiền từ đó
				'bank_name' => $my_bank_card['bank_name']
					. " (chi nhánh " . $my_bank_card['agent'] . ")",// tên ngân hàng nhận tiền qua chuyển tiền
				'bank_short_name' => $request->get('my_bank_card'),// tên viết tắt của ngân hàng
				'bank_account_name' => $my_bank_card['name'],// tên chủ tk ngân hàng nhận tiền qua hình thức chuyển tiền
				'bank_account_number' => $my_bank_card['account'],// số tài khoản ngân hàng nhận tiền qua chuyển tiền
				// Thông tin người thanh toán
				'payer_name' => $request->get('bank_exchange[name]', null, true),
				'payer_email' => $request->get('bank_exchange[email]', null, true),
				'payer_phone_no' => $request->get('bank_exchange[phone]', null, true),
				'payer_address' => $request->get('bank_exchange[address]', null, true),
			];
			$amount = $request->get('bank_exchange[amount]', 0, true);
			$tran_manager = new TransactionManager();
			/** @var BankPayment $bank_payment */
			$res = $tran_manager->rechargeByBankATM(auth()->user(), $amount, $bank_info);
			return response()->json($res);
		}elseif($request->isMethod('get')){
			$data = [
				'user' => auth()->user(),
			];

			return view('frontend.user.recharge_by_bank_exchange', $data );
		}
	}

    public function rechargeByCOD(Request $request){
        if($request->isMethod('post')){
            $this->validate($request, [
                'COD.contact_name' => 'required',
                'COD.contact_phone' => 'required|digits_between:9,13',
                'COD.contact_email' => 'required|email',
                'COD.contact_address' => 'required',
            ]);

            $check = $this->course_cod->check_duplicate_order($request->course_id, auth()->user()->id);
            if(!$check)
            {
                $this->course_cod->create([
                    'course_id' => $request->course_id,
                    'user_id' => auth()->user()->id,
                    'contact_name' => $request->get('COD[contact_name]', null, true),
                    'contact_email' => $request->get('COD[contact_email]', null, true),
                    'contact_phone' => $request->get('COD[contact_phone]', null, true),
                    'contact_address' => $request->get('COD[contact_address]', null, true),
                    'active' => 0,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Đã order thành công',
                    'next_link' => route('user.financial.review'),
                ]);
            }
            else
            {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn đã order khóa học này',
                    'next_link' => redirect()->back(),
                ]);
            }
        }elseif($request->isMethod('get')){
            $data = [
                'user' => auth()->user(),
            ];
            return view('frontend.user.recharge_by_COD', $data );
        }
    }

	/**
	** Cập nhật thông tin trường học
	***/
	public function update(Request $request,ResourceController $valid,$module){
		$user_id	=	auth()->user()->id;

		if($user_id > 0){
			$user 	=	 $this->user->findOrThrowException($user_id);

			//Upload ảnh đại diện
		  if ($request->hasFile('avatar')) {

	      	// valid file
	         $file_uploaded = $request->file('avatar');
	         $valid_result = $valid->validUploadedFile($file_uploaded, 'image');
	         if($valid_result['valid'] == false){
	            return redirect('dashboard/'.$module)->withFlashDanger($valid_result['message']);
	         }

	         //save new image
        		$disk 		= MyStorage::getDisk('public');
        		$filename   = time() . '.' . $file_uploaded->getClientOriginalExtension();
        		$full_path  = $this->path.$filename;

        		$saved = $disk->writeStream($full_path,
            Image::make($request->file('avatar'))
                ->resize(1080, 1080, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->stream()
                ->detach());

        		if($saved){

        			// remove old image
	            try{
	                $old_disk = MyStorage::getDisk($user->avatar_disk);
	                if($old_disk && $user->avatar_path != '' &&$old_disk->has($user->avatar_path)){
	                    $old_disk->delete($user->avatar_path);
	                }
	            }catch (FileNotFoundException $e){
	                // do nothing
	            }

	            $saved = $user->update(['avatar_path' => $full_path, 'avatar_disk' => 'public']);

	            if($saved){
	                return redirect('dashboard/'.$module)->withFlashSuccess('Cập nhật ảnh dại diện thành công');
	            }

        		}

	      } else {
	      	  //Cập nhật thông tin không có ảnh đại diện
			  $this->user->UpdateInfo($user_id, $request->except(['files']));
			  return redirect('dashboard/'.$module)->withFlashSuccess('Cập nhật thông tin thành công');
	      }
		
		} else {
			return redirect('dashboard/'.$module)->withFlashDanger('Lỗi Cập nhật thông tin không thành công');
		}
	}

	//Trở thành giáo viên
	public function becomeTeacher(Request $request){
		$module = $request->get('module');
		if($module == 'add'){
			if(\Auth::guest())
			{
				return redirect('/');
			}
		}
		return view("frontend.user.register_teacher",compact('module'));
	}

	//Cài đặt nhận thông báo
	public function notificationSetting(Request $request)
	{
		$user_id	=	auth()->user()->id;
		if($request->ajax()){
			$nitify_type = $request->get('type');
			$status 	 = $request->get('status');
			$setting	 = NotificationSetting::where('notify_type',$nitify_type)
												->where('user_id',$user_id)
												->first();

			if(is_null($setting)){
				$setting = new NotificationSetting();
				$setting->user_id = $user_id;
				$setting->notify_type = $nitify_type;
				$setting->enable_profile = 0;
				$setting->enable_email = 0;
			} else {
				if($status	== "enable_profile"){
					$value  = 	abs($setting->enable_profile - 1);
					$setting->enable_profile = $value;
				} else {
					$value  = 	abs($setting->enable_email - 1);
					$setting->enable_email = $value;
				}
			}

			if($setting->save()){
				return response()->json(['message'=>'Đã lưu cài đặt','success'=>true]);
			} else {
				return response()->json(['message'=>'Cài đặt thất bại','success'=>false]);
			}
		}
	}

	public function postBecomeTeacher(Becometeacher $request,ResourceController $valid)
	{
		$name			=	$request->get("name");
		$unit_name		=	$request->get('unit_name');
		$position		=	$request->get('position');
		$status_text	=	$request->get('status_text');
		$achievement	=	$request->get('achievement');
		$user_id		=	auth()->user()->id;
		$user 			=	$this->user->findOrThrowException($user_id);

		if($user->become_teacher == 1){
			return redirect()->route('become.teacher',['module=add'])->withFlashDanger('Thông tin đăng ký của bạn đang được BQT duyệt');
		}

		//Upload ảnh đại diện
		if(Input::file())
		{
			// valid file
			$file_uploaded = $request->file('avatar');
			$valid_result = $valid->validUploadedFile($file_uploaded, 'image');
			if($valid_result['valid'] == false){
				return redirect()->route('become.teacher',['module=add'])->withFlashDanger($valid_result['message']);
			}

			$disk 		= MyStorage::getDisk('public');
			$filename   = time() . '.' . $file_uploaded->getClientOriginalExtension();
			$full_path  = $this->path.$filename;
			$saved 		= $disk->writeStream($full_path,
							Image::make($request->file('avatar'))
							->resize(1080, 1080, function ($constraint) {
								$constraint->aspectRatio();
							})
							->stream()
							->detach());
			if($saved)
			{
				// remove old image
				try{
					$old_disk = MyStorage::getDisk($user->avatar_disk);
					if($old_disk && $user->avatar_path != '' &&$old_disk->has($user->avatar_path)){
						$old_disk->delete($user->avatar_path);
					}
				}catch (FileNotFoundException $e){
					// do nothing
				}

				$user->avatar_path = $full_path;
				$user->avatar_disk = 'public';

			}
		}

		$user->name 			= $name;
		$user->unit_name 		= $unit_name;
		$user->position 		= $position;
		$user->status_text 		= $status_text;
		$user->achievement 		= $achievement;
		$user->become_teacher 	= 1;

		if($user->save()){

			//Gửi email,notify
			$obj_related		=  0;
			$obj_sender         =  $this->user->findOrThrowException(config('notification.obj_send.id'));
			$obj_user			=  $user;
			$data['type']       =  "message";
			$data['subject']    =  "Bạn đã đăng ký trở thành giảng viên tại ".config('app.name')." thành công. Chúng tôi sẽ duyệt yêu cầu của bạn trong 2 ngày làm việc.";
			$tem_type           =  config('notification.template.user.register_teacher.successful.key');
			$data['body']       =  view('emails.notification.template',compact('tem_type'))->render();
			$data['bodyMail']   =  view('emails.notification.email',compact('tem_type','obj_user'))->render();
			$data               =  json_decode(json_encode ($data), FALSE);
			event(new SendNotificationEvent($user,$obj_sender,$obj_related,$data));
			event(new SendEmailNotificationEvent($obj_user,$data));

			return redirect()->route('become.teacher',['module=success']);
		}
	}


	public function payment_guide($order_id){
		/** @var Order $order */
		$order = Order::find($order_id);//dd($order);
		if(!$order){
			abort(404);
		}
		if($order->item_type != BankPayment::class){
			abort(403);
		}
		/** @var User $loged_in */
		$loged_in = auth()->user();
		if(!$loged_in->hasRole(config('access.role_list.administrator')) && $order->seller != $loged_in->id){
			abort(401);
		}
//		dd($order->getItemObject());
		return view('frontend.user.payment_guide', [
			'order' => $order,
			'loged_in' => $loged_in,
			'bank_payment' => $order->getItemObject()
		]);
	}

	public function staticModule($module){
		switch($module){
			case "payment-guide":
				return view('frontend.'.config('app.id').'.static.payment_guide', compact('module'));
				break;
			case "chinh-sach-bao-mat-thong-tin":
				return view('frontend.'.config('app.id').'.static.policy', compact('module'));
				break;
			case "dieu-khoan-su-dung":
				return view('frontend.'.config('app.id').'.static.terms_of_use', compact('module'));
				break;
			case "quy-che-hoat-dong":
				return view('frontend.'.config('app.id').'.static.regulation', compact('module'));
				break;
			case "chinh-sach-hoan-hoc-phi":
				return view('frontend.'.config('app.id').'.static.refund_policy', compact('module'));
				break;
			default:
				return view('frontend.'.config('app.id').'.static.payment_guide', compact('module'));
		}
	}

    public function execPostRequest($url, $data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

	public function payment_bybank()
    {
        $trans_ref = isset($_GET["trans_ref"]) ? $_GET["trans_ref"] : NULL;
        $order_id = isset($_GET["order_id"]) ? $_GET["order_id"] : NULL;
        $response_code = isset($_GET["response_code"]) ? $_GET["response_code"] : NULL;
        $response_message = isset($_GET["response_message"]) ? $_GET["response_message"] : NULL;

        $card_name = isset($_GET["card_name"]) ? $_GET["card_name"] : NULL;
        $card_type = isset($_GET["card_type"]) ? $_GET["card_type"] : NULL;

        \Log::info("Den doan nay lan 1");
        $order = Order::findOrFail($order_id);

        \Log::info("Den doan nay lan 2");
        $bankPayment = BankPayment::where('transaction_id', "like", $trans_ref)->first();
        $seller = $order->sellingUser; // nguoi nap tien
        $buyer = $order->buyingUser; // tk nhan thanh toan cua he thong
        $revenue_acc = $this->getRevenueAccount();

        $access_key = "7acmsgf29lhy9ilryyf2";           // require your access key from 1pay
        $secret = "4doelco82ujyatyk21kn67gatmq2u8i2";               // require your secret key from 1pay

        if($response_code == "00")
        {
            \Log::info("Den doan nay lan 3");
            $command = "close_transaction";

            $data = "access_key=".$access_key."&command=".$command."&trans_ref=".$trans_ref;
            $signature = hash_hmac("sha256", $data, $secret);
            $data.= "&signature=" . $signature;

            $json_bankCharging = $this->execPostRequest('http://api.1pay.vn/bank-charging/service', $data);

            $decode_bankCharging=json_decode($json_bankCharging,true);  // decode json
            // Ex: {"amount":10000,"trans_status":"close","response_time": "2014-12-31T00:52:12Z","response_message":"Giao dịch thành công","response_code":"00","order_info":"test dich vu","order_id":"001","trans_ref":"44df289349c74a7d9690ad27ed217094", "request_time":"2014-12-31T00:50:11Z","order_type":"ND"}

            $response_message = $decode_bankCharging["response_message"];
            $response_code = $decode_bankCharging["response_code"];
            $amount = $decode_bankCharging["amount"];

            if($response_code == "00")
            {
                $bankPayment->bank_name = $card_name;
                $bankPayment->bank_short_name = $card_type;
                $bankPayment->save();
                $order->status = Constant::APPROVED_ORDER;
                $order->item_price = $bankPayment->price;
                $mobile_acc = $buyer->fresh();
                $revenue_acc = $revenue_acc->fresh();
                $user = $seller->fresh();
                \DB::beginTransaction();
                // chuyển từ tài khoản đối ứng sang tài khoản nhận thanh toán thẻ
                $this->inner_transaction_manager->transfer($mobile_acc, $revenue_acc, $bankPayment->price, $order, true);
                // cộng tk quyền mua cho người nạp
                $this->inner_transaction_manager->increaseSecondaryWallet($user, $bankPayment->price, $order);
                // cập nhật trạng thái order
                $order->save();
                \DB::commit();
                return redirect('dashboard/financial/review')->with('status', $response_message);
            }
            else
            {
                $order->status = Constant::REJECTED_ORDER;
                $order->save();
                $bankPayment->other_info = "Thẻ lỗi";
                $bankPayment->save();
                return redirect('dashboard/financial/review')->with('status', $response_message);
            }
        }
        \Log::info("Den doan nay lan 4");
        return redirect('dashboard/financial/review')->with('status', $response_message);
    }

    /**
     * @return User tài khoản doanh thu
     * @throws ConfigErrorException
     */
    private function getRevenueAccount(){
        $revenue_acc = User::where('id', config('money.'.config("app.id").'.revenue_account.id'))
            ->where('email', config('money.'.config("app.id").'.revenue_account.email'))
            ->first();
        if(!$revenue_acc){
            throw new ConfigErrorException("Lỗi thanh toán, liên hệ quản trị với mã lỗi ERROR_REVENUE_ACC");
        }
        return $revenue_acc;
    }
}