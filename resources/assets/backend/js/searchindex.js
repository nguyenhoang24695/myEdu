/**
 * Created by hocvt on 12/15/15.
 */

$(document).ready(function(){

    $('button.load_ajax').click(function(){
        var $this = $(this);
        var $viewer = $($this.data('viewer'));
        var _index = $this.data('index');
        var _action = $this.data('action');
        var _alert_text = $this.data('alert');
        if(_alert_text){
            bootbox.confirm(_alert_text, function(result){
                if(result){
                    do_load_info(_index, _action, $viewer);
                }
            })
        }else{
            do_load_info(_index, _action, $viewer);
        }

    });

    var do_load_info = function(_index, _action, $viewer){
        $.post(searchindex_info_link ,{index: _index, action: _action}, function(data){
            $viewer.html("<pre>" + data + "</pre>");
        });
    }

});
