/**
 * Created by hocvt on 11/30/15.
 */
$(document).ready(function(){

    var $taginputs = $('input.unibee-taginput-tags'), unibee_tag_options;
    if(unibee_tags_link != ''){
        unibee_tag_options = {
            typeaheadjs: {
                name: 'unibee_tags',
                displayKey: 'name',
                valueKey: 'name',
                source: function(unibee_tags,cb1, cb2){
                    $.post(unibee_tags_link, {keyword: unibee_tags}, function(data){
                        cb2(data);
                    }, 'json');
                }
            }
        };
    }
    $taginputs.tagsinput(unibee_tag_options);

    var $suggested_tags_container = $('.unibee-taginput-suggestd');
    $suggested_tags_container.each(function(){
        var input_trigger = $(this).data('trigger');

    });

    var $tag_suggestor = $('div.taginput_suggestion_container').on('click', 'a.tag',function(){

    });
    if($tag_suggestor.length > 0){
        $tag_suggestor.each(function(){
            var $fire_elm = $($(this).data('watch'));
            var $a_suggestor = $(this);
            var $taginput = $($(this).data('taginput'));
            $a_suggestor.on('click', 'a.tag', function(){
                $taginput.tagsinput('add', $(this).text());
                $(this).remove();
            });
            if($fire_elm.length) {
                $fire_elm.change(function () {
                    $val = $(this).val();
                    $.post(unibee_tags_link, {keyword: $val}, function(data){
                        $a_suggestor.html("");
                        for(var i = 0; i < data.length; i++){
                            var a_suggested_tag = data[i];
                            console.log($a_suggestor, a_suggested_tag.name);
                            $a_suggestor.append(" <a class='tag label label-warning'> <i class='fa fa-plus'></i> "
                                + a_suggested_tag.name
                                + " </a> &nbsp;");
                        }
                    }, 'json');
                });
                $fire_elm.trigger('change');
            }
        });
    }

});
