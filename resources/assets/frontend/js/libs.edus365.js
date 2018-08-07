/**
 * Thư viện riêng tổng hợp 1 số hàm dùng chung của dự án EDUS365, nên được chèn vào các layout
 * Created by hocvt on 10/1/15.
 */

/**
 * Khai báo các tag có thể sử dụng disabled tag trong html, nếu ko có thì sử dụng hiệu ứng blick để thông báo nút đã bị
 * disabled (có thể tùy chọn có hoặc không).
 * @type {string[]}
 */
var can_disabled_tags = [
    'select',
    'option',
    'input',
    'button',
    'textarea'
];


////////////////////////////////PROTOTYPE FUNCTIONS//////////////////////////////////
/**
 * Hàm sử dụng để thay thế chuỗi trong một mảng bằng 1 chuỗi khác tương ứng trong 1 mảng khác
 * @param find
 * @param replace
 * @returns {String}
 */
String.prototype.replaceArray = function(find, replace) {
    var replaceString = this;
    var regex;
    for (var i = 0; i < find.length; i++) {
        regex = new RegExp(find[i], "g");
        replaceString = replaceString.replace(regex, replace[i]);
    }
    return replaceString;
};


/////////////////////////////////OTHER FUNCTIONS//////////////////////////////////////
/**
 * Hàm mặc định xử lý khi lỗi trong quá trình request ajax
 */
var default_error_handle = function(){
    var error_string = "undefined" == typeof connection_error_string ? 'CONNECTION ERROR' : connection_error_string || 'CONNECTION ERROR';
    if(bootbox){
        bootbox.alert(error_string);
    }else{
        alert(error_string)
    }
};

/**
 * Sử dụng để disable 1 control, tự động nhận biết biến truyền vào, sử dụng kết quả trả về để xác định thao tác
 * có được phép thực hiện hay không
 * @param elm đối truyền vào có thể là element, selector hoặc jQuery object
 * @param time_out nếu cài đặt timeout lớn hơn 0 thì sẽ tự động enable control sau khi hết thời gian time_out
 * @returns {boolean} trả về true nếu control được cài đặt từ enabled -> disabled (có thể thực hiện thao tác), trả về false
 * nếu control đã ở trạng thái disabled (không tiếp tục thực hiện thao tác).
 */
var disable_control = function(elm, time_out, blink_me){
    blink_me = blink_me || false;
    time_out = time_out || 0;// 0 mean forever
    var $elm = elm instanceof jQuery ? elm : $(elm), current_status = false;

    if(can_disabled_tags.indexOf($elm.prop('tagName').toLowerCase()) >= 0){
        current_status = !$elm.attr('disabled');
        $elm.attr('disabled', true);
    }else{
        current_status = !$elm.data('edus_disabled');
        $elm.data('edus_disabled', true);
    }

    if(blink_me){
        $elm.addClass('blink_me');
    }

    if(time_out > 0){
        setTimeout(enable_control, time_out, elm);
        $elm.data('disable_timeout', setTimeout(enable_control, time_out, elm));
    }

    return current_status;
};

/**
 * Sử dụng để enable 1 control, tự động nhận biết biến truyền vào, sử dụng kết quả trả về để xác định thao tác
 * có được phép thực hiện hay không
 * @param elm đối truyền vào có thể là element, selector hoặc jQuery object
 * @param time_out nếu cài đặt timeout lớn hơn 0 thì sẽ tự động enable control sau khi hết thời gian time_out
 * @returns {boolean} trả về true nếu control được cài đặt từ disabled -> enabled (có thể thực hiện thao tác), trả về false
 * nếu control đã ở trạng thái enabled (không tiếp tục thực hiện thao tác).
 */
var enable_control = function(elm, time_out){
    time_out = time_out || 0;// 0 mean forever
    var $elm = elm instanceof jQuery ? elm : $(elm), current_status = false;

    if(can_disabled_tags.indexOf($elm.prop('tagName').toLowerCase()) >= 0){
        current_status = $elm.attr('disabled');
        $elm.attr('disabled', false);
    }else{
        current_status = $elm.data('edus_disabled');
        $elm.data('edus_disabled', false);
    }

    $elm.removeClass('blink_me');

    if(time_out > 0){
        $elm.data('disable_timeout', setTimeout(disable_control, time_out, elm));
    }

    return current_status;
}

/**
 * Ngăn cản click double quá nhanh
 * @param elm
 */
var prevent_dblclick = function(elm){
    disable_control(elm, 500);
}

/**
 * Lấy giá trị của biến có tên được truyền vào.
 * @param key tên của biến cần lấy giá trị
 * @param default_value giá trị mặc định trả về
 * @returns {Object}
 * @private
 */
var __ = function(key, default_value){
    return eval('"undefined" == typeof ' + key + ' ? default_value : ' + key);
}

$(function () {
    $('[data-toggle="tooltip"]').tooltip();
})


var flat_error_message = function(errorsJSON){
    var errors = [];
    for(var i in errorsJSON){
        var j = errorsJSON[i];
        errors = errors.concat(j);
    }
    return errors;
}

window.recharge_modal_form = {modal: function(){
   // do nothing
}};



