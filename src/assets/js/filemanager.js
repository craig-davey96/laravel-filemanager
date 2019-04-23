function get_markup(){

    $.ajax({
        'url' : '/admin/filemanager/getMarkup',
        'type' : 'POST',
        'data' : '',
        'headers': {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            $('.filemanager').html(response.html);
        }
    })

}

function get_results(){

    $('body .filemanager .filemanager_viewer .results').empty();
    $('body .filemanager .loader').fadeIn();

    setTimeout(function () {

        $.ajax({
            'url' : '/admin/filemanager/get',
            'type' : 'POST',
            'data' : '',
            'headers': {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {

                var files = response.file;
                var dirs = response.dirs;

                for (var i = 0; i < dirs.length; i++){

                    var item = '<div class="directory"><div class="vert">'+dirs[i].basename+'</div></div>';
                    $('body .filemanager .filemanager_viewer .results').append(item);

                }

                for (var n = 0; n < files.length; n++){

                    var item = '<div class="file"><input type="checkbox" name="item[]" value="'+files[n].path+'"><img src="'+files[n].url+'"><div class="text"><div class="vert">'+files[n].basename+'</div></div></div>';
                    $('body .filemanager .filemanager_viewer .results').append(item);

                }

                $('body .filemanager .loader').fadeOut();
                vert();
            }
        });

    } , 1000);

}

function vert(){

    var vert = $('body .vert');

    $.each(vert , function (index , element) {
        $(element).width($(element).parent().width()).height($(element).parent().height());
    });

}

function makeid(length) {
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    for (var i = 0; i < length; i++)
        text += possible.charAt(Math.floor(Math.random() * possible.length));

    return text;
}

function upload(file){

    if(file != ''){

        var formdata = new FormData();
        formdata.append('dir' , '');
        formdata.append('upload_file' , file);

        var rand = makeid(5);
        var item = '<div class="item" id="item'+rand+'"><div class="progress"><div class="bar"></div></div></div>';

        $('body .filemanager .filemanager-upload-block .inner .inner-body .dragdrop .items').append(item);

        var xhr = new XMLHttpRequest();

        xhr.open('POST' , '/filemanager/upload');
        xhr.setRequestHeader('X-CSRF-TOKEN' , $('meta[name="csrf-token"]').attr('content'));

        xhr.upload.onprogress = function(e){
          var progress = Math.ceil((e.loaded / e.total) * 100);
          $('body #item'+rand+' .bar').animate({width: progress+'%'});
        };

        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                $('body #item'+rand).css('background' , 'url('+xhr.response+')');
            }
        }

        xhr.send(formdata);

    }

}

$(document).ready(function () {

    get_markup();
    get_results();

    /** UPLOAD CLICK */

    $('body').on('click' , '#upload' , function () {
        $('body .filemanager .filemanager-upload-block').slideToggle();
        $('body .filemanager .filemanager-upload-block .inner .inner-body .dragdrop .items').empty();
        vert();
    });

    /** REFRESH CLICK */

    $('body').on('click' , '#filemanager_refresh' , function () {
        get_results();
    });

    /** DRAGDROP CLICK */

    $('body').on('click' , '.dragdrop' , function () {
       $('#file_manager_files').trigger('click');
    });

    /** FILES CHOSEN */

    $('body').on('change' , '#file_manager_files' , function () {

        var files = $(this)[0].files;
        var num = 0;

        while(num < files.length){
            upload(files[num]);
            ++num;
            if(num == files.length-1){
                get_results();
            }
        }

    });

    /** ACTIVE FILE */

    $('body').on('click' , '.filemanager .results .file' , function () {

        var ele = $(this);
        ele.toggleClass('active');

        if(ele.hasClass('active')){
            ele.find('input').prop('checked' , true);
        }else{
            ele.find('input').prop('checked' , false);
        }

    });

});