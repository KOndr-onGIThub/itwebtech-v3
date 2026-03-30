$(function(){


    /* RECAPTCHA */
        // anonymous function lets us use private variables
        (function() {
            // remember the form
            var $form = $("#contact_form");
        
            // whether we want to really submit the form or to trigger recaptcha
            var really = false;
            
            // when the form is submitted,
            $form.on("submit", function(e) {
            // if we're really submitting then don't do anything
            if (really) {
                return;
            }
            
            // don't submit
            e.preventDefault();
        
            // start recaptcha
            grecaptcha.reset();
            grecaptcha.execute();
            });
            
            // set up a "onSubmit" function for recaptcha to call when it completes
            window.onSubmit = function(token) {
            // from here on we should really submit the form
            really = true;
        
            // trigger a second submit
            $form.submit();
            };
        })();

        /* skryt captchu na malym displeji a zobrazit ji az dole*/
/*         $(window).scroll(function() {
            if($(window).width() < 1280){
                if($(window).scrollTop() + $(window).height() > $(document).height() - 900) {
                    $('.grecaptcha-badge').css('visibility', 'visible');
                }else{
                    $('.grecaptcha-badge').css('visibility', 'hidden');
                }
            }else{
                $('.grecaptcha-badge').css('visibility', 'visible');
            }
         }); */





    $('#btn_close_alert').click(function(){
        $('#alert_message_sent').hide( "slow");
    });

    $('#btn_close_alert_recaptcha').click(function(){
        $('#alert_recaptcha').hide( "slow");
    });
});