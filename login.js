$(function(){

    $(".valid-email").keyup(function(){

        var form_g = $(this).closest('.form-item');

        if($(this).val().length === 0){
            form_g.removeClass('has-succes').addClass('has-error');
        }else{
            form_g.removeClass('has-error').addClass('has-succes');
        }
    });

    $(".valid-password").keyup(function(){
        
        var form_g = $(this).closest('.form-item');

        if($(this).val().length === 0){
            form_g.removeClass('has-succes').addClass('has-error');
        }else{
            form_g.removeClass('has-error').addCladd('has-succes');
        }
    });
});