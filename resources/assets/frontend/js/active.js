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
                 color: '#ffaa00',
                 secondaryColor: '#fff',
                 jackColor: '#fff',
                 jackSecondaryColor: '#333'
             });
          }
       });
   }

   if($('.js-check-change').length > 0){
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
   }

});