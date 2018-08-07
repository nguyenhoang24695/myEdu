//Khai báo các biên global.
var UNIBEE = {
    scrollFirst : 0,
    scrollTop   : 0,
    menuHeight  : 0,
    slide_width : 0,
    slide_height: 0,
    sug_width   : 485,
    sug_height  : 0,
    url_search_api    : '',
    url_global_search : '',
    param_search : '?q='
};


$(function(){
    
    //Lấy chiều cao của menu
    UNIBEE.menuHeight        = $(".dropdown-menu-unibee").height();

    //lấy url search
    UNIBEE.url_global_search = $(".search-unibee").attr('data-action')+UNIBEE.param_search;
    UNIBEE.url_search_api    = $(".search-unibee").attr('auto-action');

    //Lấy chiều dài của slide
    if($(".slide").length > 0){
        UNIBEE.slide_width  = $(".slide").width();
        UNIBEE.slide_height = $(".slide").height();
    }
    
    //Lấy vị trí đầu tiên khi load trang
    UNIBEE.scrollFirst = $(window).scrollTop();

    /***********END*************/

    $(".icon-notify fa-bell-o").hover(function(){
        $(this).addClass("animated swing");
    },function(){
        $(this).removeClass("animated swing");
    });

    $('.scrollable').slimScroll({
        size: '8px',
        height: '220px',
        alwaysVisible: true
    });

    //Đánh dấu đã đọc,xóa thông báo
    $(document).on('click','.is_mark',function(e){
        var pk   = $(this).attr('data-pk');
        var type = $(this).attr('data-type');
        var obj  = $(this);
        if (type == 'read') {
            $(this).parents('.item-notify').find('a').removeClass('is-read');
            var num = $('.icount').find('span').text();
                num = parseInt(num);
            if(num > 0){
                var newnum = parseInt(num-1);
                if(newnum == 0){
                    $('.icount').remove();
                } else {
                    $('.icount').find('span').text(newnum);
                }
            }
            postActionNotification(type,pk);
        } else {
            bootbox.confirm("Bạn có chắc chắn muốn xóa thông báo này?", function(result) {
                if(result){
                    obj.parents('.item-notify').remove();
                    postActionNotification(type,pk);
                }
            }); 
        }
    });


    /*********Đánh giá lớp học************/
    $('#reviews').ajaxForm({

        success: function(data){
            $('#myModal_rating').modal('hide');
            $('#myModal_notify').modal('show');
        },
        error: function(data){
            $(".label-danger").addClass('hide');
            $(".label-danger").text('');
            var errors = data.responseJSON;
            $.each( errors , function( key, value ) {
                $("."+key+"_error").text(value[0]);
                $("."+key+"_error").removeClass('hide');
            });
        }

    });

    if($(".star").length > 0){
        $(".star").hover(function() {
            /* Stuff to do when the mouse enters the element */
            $(".note_rating").text($(this).attr('dtitle'));
        }, function() {
            /* Stuff to do when the mouse leaves the element */
            if( $('input[name=star]:radio:checked').length > 0 ) {
                $(".note_rating").text($("input[name='star']:checked").attr('dvalue'));
            } else {
                $(".note_rating").text('');
            }
            
        });
    }
    /**************END*****************/

    /*************Bootstrap select*****/
    $('.selectpicker').selectpicker({
          
    });
    /**************END*****************/

    /***********Slide trang chủ*********/
    if($('.slide').length > 0){
        $('.slide').DrSlider({
            navigationType: 'circle',
            progressColor: '#ffaa00',
            navigationColor: '#DFDFDF',
            navigationHoverColor: '#ffaa00',
            navigationHighlightColor: '#ffaa00',
            showControl: false,
            width: UNIBEE.slide_width, //slide width
        });
    }
    /**************END*****************/

    /*********Sử lý enable khi điền đầy đủ thông tin login***********/
    if($('.input_login').length > 0){
        $('.send_login').attr('disabled', true);

        $( ".input_login" ).bind( "change keyup", function() {
            var trigger = false;
            $('.input_login').each(function() {
                if ($(this).val() == "") {
                    trigger = true;
                }
            });

            if(trigger){
                $('.send_login').attr('disabled', true);
                $('.send_login').addClass('disabled');
            } else {
                $('.send_login').removeAttr('disabled');
                $('.send_login').removeClass('disabled');
            }
        });
    }
    /*************END***************/
    
    /*************Xử lý menu phần mobile***************/
    var html_menu = $(".main_menu").html();
    $("#sidebar-left").find('ul').html(html_menu);

    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        e.stopPropagation();

        $("#wrapper").toggleClass('effect-animate');
        $('.overlay').toggleClass('hide');

        //Ẩn menu bên phải
        $("#wrapper").removeClass('effect-right');
        $(".overlay-x").addClass('hide');

    });

    $("#menu-use").click(function(e) {
        e.preventDefault();
        e.stopPropagation();
        $("#wrapper").toggleClass('effect-right');
        $('.overlay-x').toggleClass('hide');
    });

    $(document).on("click","html,body",function() {
        if ($('#wrapper').hasClass('effect-right')) {
            $("#wrapper").removeClass('effect-right');
            $('.overlay-x').addClass('hide');
        }
        if($("#wrapper").hasClass('effect-animate')){
            $("#wrapper").removeClass('effect-animate');
            $('.overlay').addClass('hide');
        }
    });

    /********Xử lý sự kiện ô tìm kiếm***********/

    $(".search-mobile").click(function(){
        $(".navbar-mobile-search").show();
    });

    $(".addon-back-s").click(function(){
        $(this).parents(".navbar-mobile-search").hide();
    });

    $(".txt_search").focusout(function() {
        $(this).parents('.input-group').find('.input-group-btn').removeClass('group-reset');
        $(this).parents('.input-group').find('.input-group-btn').addClass('group-none');
    });

    $(".txt_search").focusin(function(event) {
        $(this).parents('.input-group').find('.input-group-btn').removeClass('group-none');
        $(this).parents('.input-group').find('.input-group-btn').addClass('group-reset');
    });

    $(document).on('click','.fa-search',function(){
        var value = $(".txt_search").val();
        if(value != ""){
            checkValueSearch(value,false);
        } else {
            $(".txt_search").focus();
        }
    });

    $(document).on('click','.btn-find',function(){
        var value = $(".search_home").val();
        if(value != ""){
            checkValueSearch(value,false);
        } else {
            $(".search_home").focus();
        }
    });

    //suggest

    var options_auto = {

      url: function(phrase) {
        return UNIBEE.url_search_api;
      },

      getValue: function(element) {
        return element.name;
      },

      ajaxSettings: {
        dataType: "json",
        method: "GET",
        data: {
          dataType: "json"
        }
      },

      preparePostData: function(data) {
        data.phrase = $(".autocomplete_search").val();
        return data;
      },
      list: {
        onChooseEvent: function() {
            var link = $(".autocomplete_search").getSelectedItemData().src;
            checkValueSearch(link,true);
        },
        onKeyEnter : function() {
            var keyword = $(".autocomplete_search").val();
            checkValueSearch(keyword,false);
        }
      },

      requestDelay: 200
    };

    $(".autocomplete_search").easyAutocomplete(options_auto);


    //Tìm kiếm ở trang chủ
    if($('.search_home').length > 0){

        var options_auto = {
          url: function(phrase) {
            return UNIBEE.url_search_api;
          },

          getValue: function(element) {
            return element.name;
          },

          ajaxSettings: {
            dataType: "json",
            method: "GET",
            data: {
              dataType: "json"
            }
          },

          preparePostData: function(data) {
            data.phrase = $(".search_home").val();
            return data;
          },

          list: {
            onChooseEvent: function() {
                var link = $(".search_home").getSelectedItemData().src;
                checkValueSearch(link,true);
            },
            onKeyEnter : function() {
                var keyword = $(".search_home").val();
                checkValueSearch(keyword,false);
            }
          },

          theme: "square",
          requestDelay: 200
        };

        $(".search_home").easyAutocomplete(options_auto);
    }

    //Tìm kiếm ở mobile

    var option_auto_mobile = {
      url: function(phrase) {
        return UNIBEE.url_search_api;
      },

      getValue: function(element) {
        return element.name;
      },

      ajaxSettings: {
        dataType: "json",
        method: "GET",
        data: {
          dataType: "json"
        }
      },

      preparePostData: function(data) {
        data.phrase = $(".auto_search_mobile").val();
        return data;
      },

      list: {
        onChooseEvent: function() {
            var link = $(".auto_search_mobile").getSelectedItemData().src;
            checkValueSearch(link,true);
        },
        onKeyEnter : function() {
            var keyword = $(".auto_search_mobile").val();
            checkValueSearch(keyword,false);
        }
      },

      theme: "square",
      requestDelay: 200
    };

    $(".auto_search_mobile").easyAutocomplete(option_auto_mobile);

    $('.collapse_action').click(function(){
        $('.icon-info').removeClass('fa-minus');
        $('.icon-info').addClass('fa-plus');

        var exit_class = $(this).parents('.panel-partner').find('.collapse')
        if(exit_class.hasClass('in'))
        {
            $(this).children('.icon-info').removeClass('fa-minus');
            $(this).children('.icon-info').addClass('fa-plus');
        } else {
            $(this).children('.icon-info').addClass('fa-minus');
            $(this).children('.icon-info').removeClass('fa-plus');
        }
    });

    $(".num_discount_friend").bind('keyup mouseup', function () {
        var obj      = $(this);
        var type     = obj.attr('d-type');
        var discount = obj.val();
        if(parseInt(discount) < 0){
            obj.val("");
        }
        
        if(obj.val() == ""){
            discount = 0;
        }

        if(type == 1){
            var enjoy          = $(".enjoy_1").attr('d-val');
            var after_discount = $('.after_discount_1');
        } else {
            var enjoy = $(".enjoy_2").attr('d-val');
            var after_discount = $('.after_discount_2');
        }
        var rest = parseInt(enjoy) - parseInt(discount);
        if(rest < 0){
            obj.val(enjoy);
            after_discount.val('0%');
        } else {
            after_discount.val(rest+'%');
        }
    });

    $('#create_link_share').ajaxForm({

        success: function(data){
            if(data.success){
                $('.link_share').val(data.link);

                var clipboard = new Clipboard('.click_to_copy');
                clipboard.on('success', function(e) {
                    e.clearSelection();
                    showTooltip(e.trigger, 'Copied!');
                });

                clipboard.on('error', function(e) {
                    showTooltip(e.trigger, fallbackMessage(e.action));
                });

                $('.social_link_all').html(data.html);
                $("#myModal_sociallink").modal('show');
                $('#myModal_sharelink').modal('hide');
            }
        },
        error: function(data){
            
        }

    });

    $('.action-del-link').unbind().bind( "click", function() {
        var url_href  = $(this).attr('data-src');

        bootbox.confirm({
          title: 'Thông báo!',
          message: 'Bạn chắc chắn muốn xóa bản ghi này',
          buttons: {
              'cancel': {
                  label: 'Để sau',
                  className: 'btn-default pull-left'
              },
              'confirm': {
                  label: 'Đồng ý',
                  className: 'btn-danger pull-right'
              }
          },
          callback: function(result) {
              if (result) {
                   return window.location.href = ''+url_href+'';
              }
          }
      });

    });
    
});

$(window).scroll(function() {

       
    UNIBEE.scrollTop = $(this).scrollTop();
    /*****************END***********************/

    /**Thực hiện hành động khi kéo menu lên trên thì menu sẽ show , kéo xuống menu hide**/

    if (UNIBEE.scrollTop < UNIBEE.scrollFirst) {

        //Kéo lên trên hiển thị navbar-ouline
        //Chỉ thực hiện ở trang chủ
        if($(".isHome").length > 0){
            if(UNIBEE.scrollTop < 60){
                $(".navbar-unibee").addClass('navbar-outline');
            } else {
                $(".navbar-unibee").removeClass('navbar-outline');
            }
        }
        
        //Thực hiện khi kéo lên trên
        $(".navbar-unibee").stop(true,true).fadeIn();
        $(".navbar-unibee").stop(true,true).addClass("animated fadeInDown");
        
        $("#sidebar-right").css('top','50px');
        UNIBEE.scrollFirst = UNIBEE.scrollTop;

    } else {
        
        if(UNIBEE.scrollTop >= 50){
            //Thực hiện khi kéo xuống dưới
            $(".navbar-unibee").stop(true,true).fadeOut();
            $(".navbar-unibee").stop(true,true).removeClass("animated fadeInDown");

            //Ẩn menu ở mobile
            $("#sidebar-right").css('top','0px');

            UNIBEE.scrollFirst = UNIBEE.scrollTop;
        }
    }

    /*****************END***********************/

});


function checkValueSearch(e, isLink) {
   if (isLink) {
      window.location.href = "" + e + "";
   } else {
      var str = e;
      var kw  = str.replace(/\s/g, '+');
      window.location.href = UNIBEE.url_global_search + kw;
   }
}

function postActionNotification(type,pk){
    $.ajax({
        type     : "POST",
        url      : '/notification/action',
        data     : {'type':type,'pk':pk},
        success  : function(data) {
            $.notify({
                message: data.message
            },{
                type: 'success'
            });
        }
    });
}

function copyToClipboard(elementId) {

  // Create a "hidden" input
  var aux = document.createElement("input");

  // Assign it the value of the specified element
  var value = document.getElementById(elementId).value;

  aux.setAttribute("value", value);

  // Append it to the body
  document.body.appendChild(aux);

  // Highlight its content
  aux.select();

  // Copy the highlighted text
  document.execCommand("copy",true);

  // Remove it from the body
  document.body.removeChild(aux);

  //thông báo
  $.notify({
      message: 'Copy thành công!'
  },{
      type: 'success',
      z_index: 99999
  });

}

function maxLengthCheck(object){
    if (object.value.length > object.maxLength){
      object.value = object.value.slice(0, object.maxLength)
    }
}

function pop_social(link_detail,title,des,type){
   var title    =   title;
   var des      =   des;
   switch(type){
      case 1 :
        //facebook
        link    =   "http://www.facebook.com/share.php?u="+link_detail+"&title="+title
      break;
      case 2 :
        //G+
        link    =   "https://plus.google.com/share?url="+link_detail;
      break;
      case 3 :
        //Twitter
        link    =   "http://twitter.com/home?status="+title+link_detail;
      break;
      case 4 :
        //Tumblr
        link    =   "http://www.tumblr.com/share?v=3&u="+link_detail+"&t="+title
      break;
        //Blogger
      case 5 :
        link    =   "http://blogger.com/blog-this.g?t="+des+"&n="+title+"&u="+link_detail
      break;
   }
   window.open(link,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=400,width=600');
   return false;
}