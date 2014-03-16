$(document).ready(function() {

    //$.ajaxSetup({ cache: false }); // ?

    $("#cities").select2({
        placeholder: "Введите город",
        minimumInputLength: 3,
        ajax: {
            url: '/api/get/cities/format/json',
            dataType: "json",
            data: function (term) {
                return { term: term }
            },
            results: function (data) {
                return { results: data.text }
            }
        },
        formatResult: function(exercise) {
            return exercise.name; //"<div class='select2-user-result'>" + exercise.name + "</div>";
        },
        formatSelection: function(exercise) {
            return exercise.name;
        },
        initSelection : function (element, callback) {
            var elementText = $(element).attr('data-init-text');
            return callback({id:element.val(), name:elementText});
        }

    });


    var preload_data = [
        { id: '1', text: 'Поклейка обоев', locked: true}
        , { id: '2', text: 'Укладка плитки'}
        , { id: '3', text: 'Теплоизоляция', locked: true }
        , { id: '4', text: 'Дизайн интерьера', locked: true }
        , { id: '5', text: 'Штукатурка'}
    ];

    $("#branches").select2({
        placeholder: "Выберите отрасль",
        minimumInputLength: 3,
        multiple: true,
        query: function (query){
            var data = {results: []};

            $.each(preload_data, function(){
                if(query.term.length == 0 || this.text.toUpperCase().indexOf(query.term.toUpperCase()) >= 0 ){
                    data.results.push({id: this.id, text: this.text });
                }
            });

            query.callback(data);
        },
        ajax: {
            url: '/api/get/test/format/json',
            dataType: "json",
            data: function (term) {
                return { term: term }
            },
            results: function (data) {
                return { results: data.text }
            }
        },
        formatResult: function(exercise) {
            return exercise.text; //"<div class='select2-user-result'>" + exercise.name + "</div>";
        },
        formatSelection: function(exercise) {
            return exercise.text;
        },
        initSelection : function (element, callback) {
            var elementText = $(element).attr('data-init-text');
            return callback({id:element.val(), name:elementText});
        }

    });
    $("#branches").select2('data', preload_data );


    var profileNav = $('#profile-nav');
    profileNav.on('click', function(e){
            profileNav.find('ul').toggle();
    });

    $(document).on('click', function(e){
        var target = $(e.target);
        if( ! target.is('#profile-nav') && ! target.parents('#profile-nav').length ) {
            $('#profile-nav ul').hide();
        }
    });


    $('#user-avatar').on('change', function(){
        $('.file-input-name').html($(this).val().replace('C:\\fakepath\\',''));
    });

//-------------------------------------------------//
// Проверка на уникальность имени игрока
//-------------------------------------------------//
    var inputUN = $('.add_edit_form input[name="username"]');

    inputUN.on('keyup', function() {

        if(inputUN.val().length > 2)
        {
            $.ajax({
                url: '/players/checklogin/format/json',
                data: { username : inputUN.val() },
                type: "POST",
                dataType: "json",
                cache: false,
                success: function(data){
                    if(data.match) {
                        inputUN.addClass('busy').removeClass('not-busy');
                    } else {
                        inputUN.removeClass('busy').addClass('not-busy');
                    }
                }
            });
        }
    });


//-------------------------------------------------//    
// Выделение активного меню
//-------------------------------------------------//
    $('.jqueryslidemenu a').each(function () {
        var location = window.location.href 
        var link = this.href                
        var result = location.match(link);  

        if(result != null) {               
            $(this).addClass('current'); 
        }
    });


    $('.btn-o.big').hover(function(){
       $(this).find('.bg-fig').fadeIn(200);
    },function(){
        $(this).find('.bg-fig').fadeOut(200);
    });

    $("#auth-btn").on('click',function(){
        fancyShow( $('.auth-form') );
        return false;
    });

    $("#reg-btn").on('click',function(){
        fancyShow( $('.reg-form') );
        return false;
    });

    $('#auth-submit').click(function(){
        user.auth();
        return false;
    });

    $('#reg-submit').click(function(){
        user.reg();
        return false;
    });

    $('.checkbox-custom i').on('click', function(){
        var icon = $(this);
        var input = $(this).parent().find('input');

       if(icon.hasClass('checked')) {
           icon.removeClass('checked');
           input.prop('checked', true);
        } else {
           icon.addClass('checked');
           input.prop('checked', false);
        }
    });


    hash_loader();

});




function hash_loader() {
    var hash = window.location.hash.substr(1);
    if ( typeof window[hash] == 'function') {
        eval(hash+'()');
    }
}


function fancyShow( obj ) {

    var fancyBoxOptions = {
        openEffect	: 'none',
        closeEffect	: 'none',
        padding : 30,
        topRatio : 0.3,
        tpl: {
            closeBtn: '<a title="Close" class="fancybox-item fancybox-close" href="javascript:;">закрыть</a>'
        }
    };

    $.fancybox( obj, fancyBoxOptions );
}


var user = {};

user.auth = function()
{
    var email = $('#auth-email').val();
    var pass = $('#auth-pass').val();
    var rememberMe = $('#auth-remember').prop("checked");

    $('.alert-error').remove();

    $.post(
        '/auth/login',
        {
            'email' : email,
            'pass': pass,
            'rememberMe': rememberMe
        }, function(data){

            if (data.status == 'ok'){
                location = '/';
            } else {
                var out = '<span class="alert-error">' + data.message + '</span>';
                $('#login-form').before(out);
                $('#email').focus();
            }
        }, 'json'
    )
}

user.reg = function()
{
    var email = $('#reg-email').val();

    $('.alert-error').remove();

    $.post(
        '/registration',
        {
            'email' : email
        },
        function(data) {

            if (data.status == 'ok'){
                fancyShow( $('.auth-form') );
                $('#auth-email').val(email);
            } else {

                var out = '<span class="alert-error">' + data.message + '</span>';
                $('#reg-form').before(out);
                $('#reg-email').focus();
            }
        }, 'json'
    )
}