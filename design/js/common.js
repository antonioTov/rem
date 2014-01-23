$(document).ready(function() {

    //$.ajaxSetup({ cache: false }); // ?

    $("#cities").select2({
        placeholder: "������� �����",
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
        { id: '1', text: '�������� �����', locked: true}
        , { id: '2', text: '������� ������'}
        , { id: '3', text: '�������������', locked: true }
        , { id: '4', text: '������ ���������', locked: true }
        , { id: '5', text: '����������'}
    ];

    $("#branches").select2({
        placeholder: "�������� �������",
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
// �������� �� ������������ ����� ������
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
// ��������� ��������� ����
//-------------------------------------------------//
    $('.jqueryslidemenu a').each(function () {    
        var location = window.location.href 
        var link = this.href                
        var result = location.match(link);  

        if(result != null) {               
            $(this).addClass('current'); 
        }
    });


    $('#btn-add-adv').hover(function(){
       $(this).find('.bg-plus').fadeIn(200);
    },function(){
        $(this).find('.bg-plus').fadeOut(200);
    });

    
});
