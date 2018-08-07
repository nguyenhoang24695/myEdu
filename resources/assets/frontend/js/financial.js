/**
 * Created by hocvt on 1/13/16.
 */


jQuery(document).ready(function(){
    $('#primary_wallet_report_control').on('show.bs.tab', function (event) {
        load_transaction_list({
            wallet_type : 'primary'
        });
    }).tab('show');
    $('#secondary_wallet_report_control').on('show.bs.tab', function (event) {
        load_transaction_list({
            wallet_type : 'secondary'
        });
    })
    //$('#wait_process_report_control').on('show.bs.tab', function (event) {
    //    load_wait_process_list();
    //})
});

var $primary_transaction_list = $('#primary_transaction_list'),
    $secondary_transaction_list = $('#secondary_transaction_list');

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