$(function(){
  
    if ($(".js-switch")[0]) {
       var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
       elems.forEach(function (html) {
          var value  =  $(html).val();
          if(value == 1){
            $(html).attr('checked', 'checked');
          }

          if($(html).hasClass('js-check-disabled')){
            var switchery = new Switchery(html, {
               secondaryColor: '#999999', 
               disabled: true, 
               disabledOpacity: 0.5
            });
          } else {
            var switchery = new Switchery(html, {
                 color: '#00a888'
             });
          }
       });
   }

   var elems   = document.querySelectorAll('.js-check-change');
   for (var i  = 0; i < elems.length; i++) {
     elems[i].onchange  = function() {
         var value      =  $(this).val();
         var url_post   =  $(this).attr("url");
         $.ajax({
            type     : "GET",
            url      : url_post,
            data     : {id:value},
            success  : function(data) {
               
            }
         });
     };
   }

   $('.btn-delete-action').click(function(ev) {
      ev.preventDefault();
      var answer = confirm('Bạn có chắc chắn muốn xóa bản ghi này.');
      if (answer) return window.location.href = $(this).attr('href');
      else return false;
   });

    $('.active_teacher').unbind().bind( "click", function() {

      var url_href  = $(this).attr('data-src');

      bootbox.confirm({
          title: 'Thông báo!',
          message: 'Bạn chắc chắn đã duyệt thông tin này',
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

    $('.deactive_teacher').unbind().bind( "click", function() {
      var url_href  = $(this).attr('data-src');

      bootbox.prompt("Bạn không duyệt với thành viên này với lý do?", function(result) {                
        
        if (result !== null) {
          return window.location.href = ''+url_href+'?message=' + result;
        }

      });

    });

    $('.remove_role').unbind().bind( "click", function() {

      var url_href  = $(this).attr('data-src');

      bootbox.prompt("Bạn có chắc chắn muốn hủy bỏ giáo viên của thành viên?", function(result) {                
        
        if (result !== null) {
          return window.location.href = ''+url_href+'?message=' + result;
        }

      });

    });

});