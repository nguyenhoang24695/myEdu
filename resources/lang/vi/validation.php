<?php

return [

	/*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | such as the size rules. Feel free to tweak each of these messages.
    |
    */

	"accepted"         => "Trường :attribute phải được chấp nhận.",
	"active_url"       => "Trường :attribute không phải là một URL hợp lệ.",
	"after"            => "Trường :attribute phải là một ngày sau ngày :date.",
	"alpha"            => "Trường :attribute chỉ có thể chứa các chữ cái.",
	"alpha_dash"       => "Trường :attribute chỉ có thể chứa chữ cái, số và dấu gạch ngang.",
	"alpha_num"        => "Trường :attribute chỉ có thể chứa chữ cái và số.",
	"array"            => "Kiểu dữ liệu của trường :attribute phải là dạng mảng.",
	"before"           => "Trường :attribute phải là một ngày trước ngày :date.",
	"between"          => [
		"numeric" => "Trường :attribute phải nằm trong khoảng :min - :max.",
		"file"    => "Dung lượng tập tin trong trường :attribute phải từ :min - :max kB.",
		"string"  => "Trường :attribute phải từ :min - :max ký tự.",
		"array"   => "Trường :attribute phải có từ :min - :max phần tử.",
	],
	"boolean"          => "Trường :attribute phải là true hoặc false.",
	"confirmed"        => "Giá trị xác nhận trong trường :attribute không khớp.",
	"date"             => "Trường :attribute không phải là định dạng của ngày-tháng.",
	"date_format"      => "Trường :attribute không giống với định dạng :format.",
	"different"        => "Trường :attribute và :other phải khác nhau.",
	"digits"           => "Trường :attribute phải gồm :digits chữ số.",
	"digits_between"   => "Trường :attribute phải có :min đến :max chữ số.",
	"email"            => "Trường :attribute phải là một địa chỉ email hợp lệ.",
	"exists"           => "Giá trị đã chọn trong trường :attribute không hợp lệ.",
	"filled"           => "Trường :attribute không được bỏ trống.",
	"image"            => "Các tập tin trong trường :attribute phải là định dạng hình ảnh.",
	"in"               => "Giá trị đã chọn trong trường :attribute không hợp lệ.",
	"integer"          => "Trường :attribute phải là một số nguyên.",
	"ip"               => "Trường :attribute phải là một địa chỉa IP.",
	'json'             => 'The :attribute must be a valid JSON string.',
	"max"              => [
		"numeric" => "Trường :attribute không được lớn hơn :max.",
		"file"    => "Dung lượng tập tin trong trường :attribute không được lớn hơn :max kB.",
		"string"  => "Trường :attribute không được lớn hơn :max ký tự.",
		"array"   => "Trường :attribute không được lớn hơn :max phần tử.",
	],
	"mimes"            => "Trường :attribute phải là một tập tin có định dạng: :values.",
	"min"              => [
		"numeric" => "Trường :attribute phải tối thiểu là :min.",
		"file"    => "Dung lượng tập tin trong trường :attribute phải tối thiểu :min kB.",
		"string"  => "Trường :attribute phải có tối thiểu :min ký tự.",
		"array"   => "Trường :attribute phải có tối thiểu :min phần tử.",
	],
	"not_in"           => "Giá trị đã chọn trong trường :attribute không hợp lệ.",
	"numeric"          => "Trường :attribute phải là một số.",
	"regex"            => "Định dạng trường :attribute không hợp lệ.",
	"required"         => "Trường :attribute không được bỏ trống.",
	"required_if"      => "Trường :attribute không được bỏ trống khi trường :other là :value.",
	"required_with"    => "Trường :attribute không được bỏ trống khi trường :values có giá trị.",
	"required_with_all" => "The :attribute field is required when :values is present.",
	"required_without" => "Trường :attribute không được bỏ trống khi trường :values không có giá trị.",
	"required_without_all" => "Trường :attribute không được bỏ trống khi tất cả :values không có giá trị.",
	"same"             => "Trường :attribute và :other phải giống nhau.",
	"size"             => [
		"numeric" => "Trường :attribute phải bằng :size.",
		"file"    => "Dung lượng tập tin trong trường :attribute phải bằng :size kB.",
		"string"  => "Trường :attribute phải chứa :size ký tự.",
		"array"   => "Trường :attribute phải chứa :size phần tử.",
	],
	"string"           => "The :attribute must be a string.",
	"timezone"         => "Trường :attribute phải là một múi giờ hợp lệ.",
	"unique"           => "Trường :attribute đã có trong CSDL.",
	"url"              => "Trường :attribute không giống với định dạng một URL.",
    "upload_notice"  => "Kích thước tập tin phải nhỏ hơn :size và có định dạng :exts",
	"captcha" 		=> ":attribute không đúng.",

	/**
	 * Custom message error
	 */
	'errors' => [
		'no_image' => 'Không có ảnh',
		'blank_image' => 'Ảnh trống',
		'blank_file_upload' => 'Không có file được tải lên',
	],
	/**
	 * Custom validation message
	 */
	'cat_exist' => 'Danh mục không tồn tại',
	'cat_active' => 'Danh mục phải đang hoạt động',
    'tmp_exist' => 'Không tồn tại file tạm',

	'invalid_extension' => 'Chỉ hỗ trợ các định dạng :exts',
	'invalid_size' => '{0}Kích thước tập tin không được quá :max_size kB| [1,Inf]Kích thước tập tin không được nhỏ hơn :min_size kB và lớn hơn :max_size kB',

	/*
	|--------------------------------------------------------------------------
	| Custom Validation Language Lines
	|--------------------------------------------------------------------------
	|
	| Here you may specify custom validation messages for attributes using the
	| convention "attribute.rule" to name the lines. This makes it quick to
	| specify a specific custom language line for a given attribute rule.
	|
	*/

	'custom' => [
		'attribute-name' => [
			'rule-name' => 'custom-message',
		]
	],

	/*
	|--------------------------------------------------------------------------
	| Custom Validation Attributes
	|--------------------------------------------------------------------------
	|
	| The following language lines are used to swap attribute place-holders
	| with something more reader friendly such as E-Mail Address instead
	| of "email". This simply helps us make messages a little cleaner.
	|
	*/

	'attributes' => [
        //Videos
        'vid_title' => 'tên video',
        'vid_description' => 'mô tả video',
        //Courses
		'cou_title' => 'tên khóa học',
		'cou_description' => 'mô tả khóa học',
		'cou_summary' => 'mô tả khóa học',
		'language' => 'ngôn ngữ',
		'introduction' => 'giới thiệu',
        //Document
        'doc_title' => 'tiêu đề tài liệu',
        'doc_description' => 'mô tả tài liệu',
        //Blog
        'blo_title' => 'Tiều đề blog',
        'blo_summary' => 'Mô tả ngắn blog',
        'blo_path' => 'Ảnh đại diện blog',
        'blo_content' => 'Nội dung blog',
        'blo_cate'	=>  'Danh mục blog',
        //Reviews
        'rating' => 'Bình chọn',
        'rev_content' => 'Nội dung đánh giá',

		'g-recaptcha-response' => 'Captcha',
		'mobile_card_pin' => 'mã pin',
		'mobile_card_serial' => 'số serial',
		'mobile_card_provider' => 'loại thẻ',
		'bk_bank_id' => 'ngân hàng',
		'bk_bank_name' => 'tên ngân hàng',
		'bk_id' => 'id ngân hàng',
		'bank_direct' => [
			'name' => 'họ tên',
			'phone' => 'số điện thoại',
			'amount' => 'số tiền',
			'email' => 'email',
			'address' => 'địa chỉ',
		],
		'my_bank_card' => 'ngân hàng',
		'bank_exchange' => [
			'name' => 'họ tên',
			'phone' => 'số điện thoại',
			'amount' => 'số tiền',
			'email' => 'email',
			'address' => 'địa chỉ',
		],
        'COD' => [
            'contact_name' => 'họ tên',
            'contact_phone' => 'số điện thoại',
            'contact_address' => 'địa chỉ',
            'contact_email' => 'email',
            'contact_city' => 'tỉnh / thành',
            'contact_district' => 'quận / huyện',
        ],

	],

	'image_dimension' => 'Kích thước ảnh tối thiểu rộng :width, cao :height.',

];