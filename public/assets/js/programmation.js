(function() {
    "use strict";

    $('.canal').hide();

    $("#canalEmail").on('click', function(){
        if($(this).is(':checked')) {
            $('.canalEmail').fadeIn(200);
        }else {
            $('.canalEmail').fadeOut(200);
        }
    });

    $("#canalMobile").on('click', function(){
        if($(this).is(':checked')) {
            $('.canalMobile').fadeIn(200);
        }else {
            $('.canalMobile').fadeOut(200);
        }
    });
}());