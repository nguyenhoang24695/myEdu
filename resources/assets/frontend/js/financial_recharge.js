/**
 * Created by hocvt on 1/13/16.
 */




jQuery(document).ready(function(){

    // recharge
    $('#recharge_by_mobile_card_control').on('show.bs.tab', function (event) {
        if(!$recharge_by_mobile_card.hasClass('loaded')){
            $recharge_by_mobile_card.load(recharge_by_mobile_card_link);
            $recharge_by_mobile_card.addClass('loaded');
        }
        $('#recharge_by').val('mobile_card');
    }).tab('show');
    $('#recharge_by_bank_card_control').on('show.bs.tab', function (event) {
        if(!$recharge_by_bank_card.hasClass('loaded')){
            $.get(recharge_by_bank_card_link, function(data){
                $recharge_by_bank_card.html(data);
                assign_bank_action('div.bank-logo');
                $recharge_by_bank_card.addClass('loaded');
            });
        }
        $('#recharge_by').val('bank_card');
    });
    $('#recharge_by_bank_exchange_control').on('show.bs.tab', function (event) {
        if(!$recharge_by_bank_exchange.hasClass('loaded')){
            $.get(recharge_by_bank_exchange_link, function(data){
                $recharge_by_bank_exchange.html(data);
                assign_bank_action('div.bank-card');
                $recharge_by_bank_exchange.addClass('loaded');
            });
        }
        $('#recharge_by').val('bank_exchange');
    });
    $('#recharge_by_COD_control').on('show.bs.tab', function (event) {
        if(!$recharge_by_COD.hasClass('loaded')){
            $.get(recharge_by_COD_link, function(data){
                $recharge_by_COD.html(data);
                $recharge_by_COD.addClass('loaded');
            });
        }
        $('#recharge_by').val('COD');
    });
    $form.on('submit', function(e){
        e.preventDefault();
        e.stopPropagation();
        // prepare data
        var _data = $form.serializeArray();
        var _posting = {};
        $.each(_data, function(i,e){
            _posting[e.name] = e.value;
        });
        if($form.data('posting') == true){
            return false;
        }else{
            $form.data('posting', true)
        }

        // post
        $.post(recharge_post_link, _posting, function(data){
            $form.data('posting', false)
            if(data.success == true){
                //if(data.method == 'direct'){
                    window.location = data.next_link;
                //}else{
                //    window.open(data.next_link);
                //    window.focus();
                //}
            }else{
                $.notify({
                    message: data.message
                },{
                    type: 'danger'
                });
            }
        }, 'json').error(function(e){
            $form.data('posting', false)
            $.notify({
                message: flat_error_message(e.responseJSON).join("<br/>")
            },{
                type: 'danger'
            });
        });
        // process result

        return false;
    }).data('posting', false);

});

var $recharge_by_mobile_card    = $('#recharge_by_mobile_card'),
    $recharge_by_bank_card      = $('#recharge_by_bank_card'),
    $recharge_by_bank_exchange  = $('#recharge_by_bank_exchange'),
    $recharge_by_COD  = $('#recharge_by_COD'),
    $form                       = $('#recharge_form');

var load_transaction_list = function(options){
    var default_options = {

    };
    options = options || {};
    options = $.extend(options, default_options);

    var posting = {};
    if(options.wallet_type == 'primary'){
        posting.primary = true;
    }else if(options.wallet_type == 'secondary'){
        posting.secondary = true;
    }

    $.post(user_financial_report_link, posting, function(data){
        if(data.success){
            if(options.wallet_type == 'primary'){
                $primary_transaction_list.html(data.html);
            }else if(options.wallet_type == 'secondary'){
                $secondary_transaction_list.html(data.html);
            }
        }else{
            $.notify({
                message: data.message
            },{
                type: 'danger'
            });
        }
    }).error(default_error_handle);

}

var assign_bank_action = function(selector){
    var $logos = $(selector);
    console.log($logos);
    var logo_count = $logos.length;
    for(var i = 0; i < logo_count; i++){
        $($logos[i]).on('click', function(){
            var $this = $(this);
            if($this.hasClass('active')){
                return false;
            }else{
                $logos.each(function(i,e){
                    $(e).removeClass('active');
                });
                $this.addClass('active');
            }
            // update bk
            if($this.hasClass('bk_card')){
                $form.find('#bk_id').val($this.data('id'));
                $form.find('#bk_bank_id').val($this.data('bank-id'));
                $form.find('#bk_bank_name').val($this.data('bank-name'));
                $form.find('#bk_bank_payment_method').val($this.data('bank-method'));
            }
            // update manual
            if($this.hasClass('sys_card')){
                $form.find('#my_bank_card').val($this.data('bank-card'));
            }
        });
    }
}

var markup_number = function(elm){
    $(elm).keyup(function(event) {

        // skip for arrow keys
        if(event.which >= 37 && event.which <= 40) return;

        // format number
        $(this).val(function(index, value) {
            return value
                .replace(/\D/g, "")
                .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                ;
        });
    });
}