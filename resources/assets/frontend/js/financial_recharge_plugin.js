/**
 * Created by hocvt on 2/1/16.
 */

(function( $ ){
    $.fn.prepare_recharge_form = function() {
        var $viewer = this;
        var init = function () {
                $recharge_by_mobile_card = $viewer.find('#recharge_by_mobile_card'),
                $recharge_by_bank_card = $viewer.find('#recharge_by_bank_card'),
                $recharge_by_bank_exchange = $viewer.find('#recharge_by_bank_exchange'),
                $recharge_by_COD = $viewer.find('#recharge_by_COD'),
                $form = $viewer.find('#recharge_form');

            // recharge
            $viewer.find('#recharge_by_mobile_card_control').on('show.bs.tab', function (event) {
                show_notice('.recharge_by_mobile_notice');
                if (!$recharge_by_mobile_card.hasClass('loaded')) {
                    $.get(recharge_by_mobile_card_link, function(data){
                        $recharge_by_mobile_card.html(data);
                        $recharge_by_mobile_card.addClass('loaded');
                        $recharge_by_mobile_card.find('[data-toggle=tooltip]').tooltip();
                    });
                }
                $viewer.find('#recharge_by').val('mobile_card');
            }).tab('show');
            $viewer.find('#recharge_by_bank_card_control').on('show.bs.tab', function (event) {
                show_notice('.recharge_by_bank_card_notice');
                if (!$recharge_by_bank_card.hasClass('loaded')) {
                    $.get(recharge_by_bank_card_link, function (data) {
                        $recharge_by_bank_card.html(data);
                        assign_bank_action('div.bank-logo');
                        $recharge_by_bank_card.addClass('loaded');
                        $recharge_by_bank_card.find('[data-toggle=tooltip]').tooltip();
                        $viewer.find('.bs_select').selectpicker();
                        markup_number($viewer.find('.number_mark_up'));
                    });
                }
                $viewer.find('#recharge_by').val('bank_card');
            });
            $viewer.find('#recharge_by_bank_exchange_control').on('show.bs.tab', function (event) {
                show_notice('.recharge_by_bank_exchange_notice');
                if (!$recharge_by_bank_exchange.hasClass('loaded')) {
                    $.get(recharge_by_bank_exchange_link, function (data) {
                        $recharge_by_bank_exchange.html(data);
                        assign_bank_action('div.bank-card');
                        $recharge_by_bank_exchange.addClass('loaded');
                        $recharge_by_bank_exchange.find('[data-toggle=tooltip]').tooltip();
                        $viewer.find('.bs_select').selectpicker();
                        markup_number($viewer.find('.number_mark_up'));
                    });
                }
                $viewer.find('#recharge_by').val('bank_exchange');
            });
            $viewer.find('#recharge_by_COD_control').on('show.bs.tab', function (event) {
                show_notice('.recharge_by_COD');
                if (!$recharge_by_COD.hasClass('loaded')) {
                    $.get(recharge_by_COD_link, function (data) {
                        $recharge_by_COD.html(data);
                        $recharge_by_COD.addClass('loaded');
                        $recharge_by_COD.find('[data-toggle=tooltip]').tooltip();
                    });
                }
                $viewer.find('#recharge_by').val('COD');
            });
            $form.on('submit', function (e) {
                e.preventDefault();
                e.stopPropagation();
                // prepare data
                var _data = $form.serializeArray();
                var _posting = {};
                $.each(_data, function (i, e) {
                    _posting[e.name] = e.value;
                });
                if ($form.data('posting') == true) {
                    return false;
                } else {
                    $form.data('posting', true)
                }

                // post
                $.post(recharge_post_link, _posting, function (data) {
                    $form.data('posting', false)
                    if (data.success == true) {
                        $.notify({
                            message: data.message
                        }, {
                            type: 'success',
                            z_index: 1051
                        });
                        //if(data.method == 'direct'){
                        window.location.href = data.next_link;
                        //}else{
                        //    window.open(data.next_link);
                        //    window.focus();
                        //}
                    } else {
                        $.notify({
                            message: data.message
                        }, {
                            type: 'danger',
                            z_index: 1051
                        });
                    }
                }, 'json').error(function (e) {
                    $form.data('posting', false)
                    $.notify({
                        message: flat_error_message(e.responseJSON).join("<br/>")
                    }, {
                        type: 'danger',
                        z_index: 1051
                    });
                });
                // process result

                return false;
            }).data('posting', false);
            $viewer.data('initialized', true);
        }

        var assign_bank_action = function (selector) {
            var $logos = $viewer.find(selector);
            console.log($logos);
            var logo_count = $logos.length;
            for (var i = 0; i < logo_count; i++) {
                $($logos[i]).on('click', function () {
                    var $this = $(this);
                    if ($this.hasClass('active')) {
                        return false;
                    } else {
                        $logos.each(function (i, e) {
                            $(e).removeClass('active');
                        });
                        $this.addClass('active');
                    }
                    // update bk
                    if ($this.hasClass('bk_card')) {
                        //$form.find('#bk_id').val($this.data('id'));
                        //$form.find('#bk_bank_id').val($this.data('bank-id'));
                        //$form.find('#bk_bank_name').val($this.data('bank-name'));
                        $form.find('#bk_bank_payment_method').val($this.data('bank-method'));
                    }
                    // update manual
                    if ($this.hasClass('sys_card')) {
                        $form.find('#my_bank_card').val($this.data('bank-card'));
                    }
                });
            }
        }

        var $all_notice = $viewer.find('.recharge_notice .toggle');

        var show_notice = function(el_will_show){
            $all_notice.hide();
            $all_notice.filter(el_will_show).show();
        }

        var markup_number = function (elm) {
            var real_field = $($(elm).data('field'));
            $(elm).keyup(function (event) {

                // skip for arrow keys
                if (event.which >= 37 && event.which <= 40) return;

                // format number
                $(this).val(function (index, value) {
                    return value
                        .replace(/\D/g, "")
                        .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                        ;
                });
                real_field.val($(this).val().replace(/\D/g, ""));
            });
        }

        $viewer.bind('shown.bs.modal', function(){
            try{
                grecaptcha.reset();
            }catch(e){
                console.log(e);
            }
            init();
        });

        return this;
    }
}( jQuery ));

$(document).ready(function(){
    var $viewer = $('#recharge_popup_form');
    window.recharge_modal_form = $viewer.prepare_recharge_form();
});