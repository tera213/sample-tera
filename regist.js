$(function(){

    const MSG_EMPTY = '入力必須です。';
    const MSG_EMAIL_TYPE = 'emailの形式ではありません。';
    const MSG_PHONE_TYPE = '電話番号の形式ではありません。';
    const MSG_PASSWORD_TYPE = 'パスワードは6文字以上にしてください。パスワードは半角英数字で入力してください';
    const MSG_PASSWORD_ZENKAKU = 'パスワードは半角英数字で入力してください';
    const MSG_PASSWORDRETYPE_TYPE = '上のパスワードと一致していません';

    $(".valid-name").keyup(function(){

        var form_g = $(this).closest('.form-item');
        //var form_g = $('.form-item');

        if($(this).val().length === 0){
            form_g.removeClass('has-succese').addClass('has-error');
            form_g.find('.help-block').text(MSG_EMPTY);
        }else{
            form_g.removeClass('has-error').addClass('has-succese');
            form_g.find('.help-block').text('');
        }
    });

    $(".valid-email").keyup(function(){

        var form_g = $(this).closest('.form-item');

        if($(this).val().length === 0){
            form_g.removeClass('has-succese').addClass('has-error');
            form_g.find('.help-block').text(MSG_EMPTY);
        }else if($(this).val().length > 50 || !$(this).val().match(/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/) ){
            form_g.removeClass('has-success').addClass('has-error');
            form_g.find('.help-block').text(MSG_EMAIL_TYPE);
        }else{
            form_g.removeClass('has-error').addClass('has-succese');
            form_g.find('.help-block').text('');
        }
    });

    $(".valid-phone").keyup(function(){
        
        var form_g = $(this).closest('.form-item');
        var format_before = $(this).val();

        format_before = format_before.replace(/-/g,'');
        //format_before = format_before.replace(/[\-\x20]/g,'');

        //10桁以上かチェック
        if(format_before.length < 10){
            form_g.removeClass('has-succese').addClass('has-error');
            form_g.find('.help-block').text(MSG_PHONE_TYPE);
        }else{
            form_g.removeClass('has-success').addClass('has-error');
            form_g.find('.help-block').text('');
        }

        //全角英数字を半角に変換
        var format_after = format_before.replace(/[Ａ-Ｚａ-ｚ０-９]/g, function(s){ return String.fromCharCode(s.charCodeAt(0)-0xFEE0)});

        //10桁だった場合
        if(format_after.length === 10){
            //ハイフンを挿入して要素内を書き換え
            $(this).val(format_after.substr(0,4) + '-' +format_after.substr(4,2) + '-' + format_after.substr(6,4));
        }else if(format_after.length === 11){
            //11桁だった場合
            $(this).val(format_after.substr(0,4) + '-' + format_after.substr)
            $(this).val(format_after.substr(0,3) + '-' +format_after.substr(3,4) + '-' + format_after.substr(7,4));
        }else{
            //何もせずに要素ないを書き換える
            $(this).val(format_after);
        }

    });

    $(".valid-password").keyup(function(){

        var form_g = $(this).closest('.form-item');

        if($(this).val().length === 0){
            form_g.removeClass('has-succese').addClass('has-error');
            form_g.find('.help-block').text(MSG_EMPTY);
        }else{
            form_g.removeClass('has-error').addClass('has-succese');
            form_g.find('.help-block').text('');
        }

        // if(!$(this).val().match(/^[\x20-\x7e]*$/)){
        //     form_g.removeClass('has-succese').addClass('has-error');
        //     form_g.find('.help-block').text(MSG_PASSWORD_ZENKAKU);
        // }else{
        //     form_g.removeClass('has-error').addClass('has-succese');
        //     form_g.find('.help-block').text('');
        // }
    });

    $(".valid-password").change(function(){

        var form_g = $(this).closest('.form-item');

        //パスワードが半角になっていないかチェック
        // if(){
        //     form_g.removeClass('has-succese').addClass('has-error');
        //     form_g.find('.help-block').text(MSG_PASSWORD_ZENKAKU);
        // }else{
        //     form_g.removeClass('has-error').addClass('has-succese');
        //     form_g.find('.help-block').text('');
        // }

        //パスワードが6文字以上かチェック
        if($(this).val().length < 6 || !$(this).val().match(/^[\x20-\x7e]+$/)){
            form_g.removeClass('has-succese').addClass('has-error');
            form_g.find('.help-block').text(MSG_PASSWORD_TYPE);
        }else{
            form_g.removeClass('has-error').addClass('has-succese');
            form_g.find('.help-block').text('');
        }
    });

    $(".valid-password-retype").change(function(){

        var form_g = $(this).closest('.form-item');

        if($(this).val().length !== $('.valid-password').val().length){
            form_g.removeClass('has-succese').addClass('has-error');
            form_g.find('.help-block').text(MSG_PASSWORDRETYPE_TYPE);
        }else{
            form_g.removeClass('has-error').addClass('has-succese');
            form_g.find('.help-block').text('');
        }
    });
});