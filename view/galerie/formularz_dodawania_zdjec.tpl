<a href="galerie/lista-galerii"><h2 id="panelTitle">Dodaj zdjęcia do galerii</h2></a>

<form method="post" action="index.php" class="jValidate">
    <input type="hidden" name="module" value="galerie">
    <input type="hidden" name="action" value="dodaj_zdjecia">
    
    <fieldset class="fullWidth">
        <label for="galeria">Wybierz galerię</label>
        <select id="galeria" name="id_galleries">
            {foreach $a_galleries as $a_gallery}
                <option value="{$a_gallery.id_galleries}">{$a_gallery.title}</option>
            {/foreach}
        </select>
        <br>
        <!-- The fileinput-button span is used to style the file input field as button -->
        <span class="btn btn-success fileinput-button hidden">
            <i class="icon-plus icon-white"></i>
            <span>Wybierz zdjęcia...</span>
            <!-- The file input field used as target for the file upload widget -->
            <input id="fileupload" type="file" name="files[]" multiple>
        </span>
        <br>
        <br>
        <!-- The global progress bar -->
        <div id="progress" class="progress progress-success progress-striped">
            <div class="bar"></div>
        </div>
        <!-- The container for the uploaded files -->
        <table id="uploadedPhotos">
            
        </table>
        </fieldset>
</form>

<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<script src="javascript/libs/file_upload/js/vendor/jquery.ui.widget.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="javascript/libs/file_upload/js/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script src="javascript/libs/file_upload/js/jquery.fileupload.js"></script>
<script>
/*jslint unparam: true */
/*global window, $ */
{literal}
$(function () {

    if( $('select#galeria').val()!='')
        $('span.fileinput-button').show();
    
    $('select#galeria').change(function(){
        if($(this).val()!='')
            $('span.fileinput-button').show();
    });
    
    //loadImageUpload($('#galeria').val(),uniqid());
    
    $('#galeria').change(function(){
        $('table#uploadedPhotos').html('');
        $('#progress').find('.bar').width(0);
        var id_galleries = $(this).val();
        loadImageUpload(id_galleries,'images/galleries/'+id_galleries+'/',340,220,false,uniqid());
    });
    
    $('#fileupload').click(function(){
        var id_galleries = $('#galeria').val();
        loadImageUpload(id_galleries,'images/galleries/'+id_galleries+'/',340,220,false,uniqid());
    });
    
    function loadImageUpload(id_galleries,saveUrl,thumbWidth,thumbHeight,crop,uniqid)
    {
        'use strict';

        var base_url = $('body').attr('data-base-url');
        var url = base_url+'javascript/libs/file_upload/server/php/index.php?saveUrl='+saveUrl+'&thumbWidth='+thumbWidth+'&thumbHeight='+thumbHeight+'&crop='+crop+'&uniqid='+uniqid;

        $('#fileupload').fileupload({
            url: url,
            fail: function (e,data) {
                alert('Błąd przesyłu zdjęcia');
            },
            done: function (e,data) {
                if(data.jqXHR.responseText.match(/error":"([A-Za-z\s]+)"/)[1]=='abort')
                {
                    $.each(data.files, function (index, file) {
                        var a_name = file.name.replace(' ','').split('.');
                        var name = a_name[0]+'_'+uniqid+'.'+a_name[a_name.length-1];
                        $('#uploadedPhotos').append('<tr data-filename="'+name+'"><td><img src="'+base_url+'images/galleries/'+id_galleries+'/thumbnail/'+name+'"></td><td><label for="title">Dodaj opis zdjęcia</label><textarea id="title" class="title"></textarea></td><td><img src="'+base_url+'images/core/delete.png" title="Usuń zdjęcie" class="deletePhoto"></td>');
                            
                        $.ajax({url: base_url+'galerie/zapisz_dane_zdjecia',
                            method: 'POST',
                            data: {id_galleries: id_galleries,fileName: name}
                        });
                    });
                }
            },
            progressall: function (e, data) {
                var progress = parseInt(data.loaded / data.total * 100, 10);
                $('#progress .bar').css(
                    'width',
                    progress + '%'
                );
            },
            start: function(){
                $('#progress').find('.bar').width(0);
            },
            stop: function(){
                $('#uploadedPhotos .deletePhoto').click(function(){
                    $.ajax({url: base_url+'galerie/usun_zdjecie',
                        context: this,
                        method: 'POST',
                        data: {id_galleries: id_galleries,fileName: $(this).closest('tr').attr('data-filename')},
                        success: function(){
                            $(this).closest('tr').fadeRemove();
                        }
                    });
                });
                
                $('#uploadedPhotos .title').blur(function(){
                    $.ajax({url: base_url+'galerie/zapisz_opis_zdjecia',
                        method: 'POST',
                        data: {id_galleries: id_galleries,fileName: $(this).closest('tr').attr('data-filename'), title: $(this).val()}
                    });
                });
            }
        });
    }
});
{/literal}
</script>