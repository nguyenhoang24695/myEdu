<?php 

return [

	//Thông tin người gửi áp dụng cho thông báo từ BQT
	'obj_send'	=> [
		'id'	=> env('OBJ_SEND_NOTIFICATION_ID') //ID ADMIN gửi thông báo
	],

	/*
	|--------------------------------------------------------------------------
	| Những loại notification sẽ sử dụng trên Quochoc
	|--------------------------------------------------------------------------
	| 
	*/

	'type'	=> [
		'message' 		=> 'Thông báo chung',
		'discussions'	=> 'Thảo luận bài viết',
		'activity'		=> [
			'like' 		=> 'Người khác thích khóa học của bạn',
			'share' 	=> 'Người khác chia sẻ khóa học của bạn'
		]
	],

	/*
	|--------------------------------------------------------------------------
	| Những mẫu notification sẽ sử dụng
	|--------------------------------------------------------------------------
	| 
	*/

	'template' => [

		'user'	=> [
			'login'	=> [
				
			],

			//Đăng ký làm giáo viên
			'register_teacher' => [
				'successful' => [
					'key'	 => 'REGISTER_TEACHER_SUCCESSFUL',
					'note'	 => 'Đăng ký trở thành giảng viên thành công'
				],
				'active' => [
					'key' 	=> 'REGISTER_TEACHER_ACTIVE',
					'note'  => 'Được duyệt trở thành giảng viên'
				],
				'deactive' => [
					'key'	 => 'REGISTER_TEACHER_DEACTIVE',
					'note'	 => 'Không được duyệt trở thành giảng viên.'
				],
				'delete' => [
					'key'	 => 'REGISTER_TEACHER_DELETE',
					'note'	 => 'Hủy chức vụ giáo viên.'
				],
			],

			'partner'	=> [
				'successful' => [
					'key'	 => 'PARTNER_SUCCESSFUL',
					'note'	 => 'Đăng ký trở thành Partner thành công'
				]
			]
		],

		'course' => [
			'active' => [
				'key' 	=> 'COURSE_ACTIVE',
				'note'  => 'Khóa học được duyệt thành công'
			],
			'delete' => [
				'key'	=> 'COURSE_DELETE',
				'note'	=> 'Khóa học bị xóa'
			],
			'public' => [
				'key'	=> 'COURSE_PUBLIC',
				'note'	=> 'Thành viên public khóa học'
			],
			'noactive'	=> [
				'key'	=> 'COURSE_NOACTIVE',
				'note'	=> 'Khóa học không được duyệt'
			],
			'buy'		=> [
				'key'	=> 'COURSE_BUY',
				'note'	=> 'Mua khóa học thành công (Gửi mess cho học viên)'
			],
			'register'	=> [
				'key'	=> 'COURSE_REGISTER',
				'note'	=> 'Học viên đăng ký nhập học thành công (Gửi mess cho giáo viên)'
			],
			'invite'	=> [
				'key'	=> 'INVITE_COURSE',
				'note'	=> 'Tặng doanh thu cho người giới thiệu khóa học (Gửi cho parner)'
			],
			'review'	=> [
				'key'	=> 'REVIEW_COURSE',
				'note'	=> 'Đánh giá khóa học'
			]
		],

		//Thảo luận
		'discussions'	=> [
			'question'	=> [
				'key'	=> 'DISCUSSIONS_QUESTION',
				'note'	=> 'Học viên đặt câu hỏi thảo luận khóa học'
			],
			'reply'		=> [
				'key'	=> 'DISCUSSIONS_REPLY',
				'note'	=> 'Trả lời câu hỏi thảo luận'
			],
			'like'		=> [
				'key'	=> 'DISCUSSIONS_LIKE',
				'note'	=> 'Thích câu hỏi thảo luận'
			]
		],

		'money'	=> [
			//Nạp tiền
			'recharge'	=> [
				'successful' => [
					'key'	 => 'MONEY_SUCCESSFUL',
					'note'	 => 'Nạp tiền thành công.'
				],
				'false'     => [
					'key'	=> 'MONEY_FALSE',
					'note'	=> 'Giao dịch nạp tiền không thành công'
				],
				'pending'	=> [
					'key' 	=> 'MONEY_PENDING',
					'note'	=> 'Giao dịch nạp tiền đang xử lý'
				]
			]
		],

		// Mã giảm giá
		'promo_code'	=> [
			'add'		=> [
				'key' 	=> 'PROMO_CODE_ADD',
				'note'	=> 'Mã giảm giá'
			],
			'edit'		=> [
				'key'	=> 'PROMO_CODE_EDIT',
				'note'	=> 'Sửa mã giảm giá.'
			],
			'delete'	=> [
				'key'	=> 'PROMO_CODE_DELETE',
				'note'	=> 'Xóa mã giảm giá.'
			],
			'active'	=> [
				'key'	=> 'PROMO_CODE_ACTIVE',
				'note'	=> 'Kích hoạt mã giảm giá.'
			],
			'deactive'	=> [
				'key'	=> 'PROMO_CODE_DEACTIVE',
				'note'	=> 'Mã giảm giá ngừng hoạt động'
			],
			//Khôi phục
			'restore'	=> [
				'key'	=> 'PROMO_CODE_RESTORE',
				'note'	=> 'Khôi phục lại mã giảm giá'
			]
		]
	],

	/*
	|--------------------------------------------------------------------------
	| Thông tin kết nối gủi mail notification
	|--------------------------------------------------------------------------
	|
	*/

	'connections' => [
		'send_mail' => [
			'url_post'	=> 'http://mail.123doc.org/api/event.php',
			'auth'		=> ['name'=>'123doc','pass'=>'2015123doc'],
			'auth_type'	=> 'basic',
			'user_id'	=> env('EMAIL_NOTIFICATION_ID') // ID phục vụ thống kê email
		]
	]
];

?>