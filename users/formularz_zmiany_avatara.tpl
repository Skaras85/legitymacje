<section class="formSection formSectionAvatar">
	<fieldset>
		<a href="{$_base_url}{$a_user.sludge_miasta}/{$a_user.sludge}" class="button"><- Powrót</a><br><br><br>
		
	    <h1>Zmień obrazek {if isset($is_bg)}tła{else}logo{/if} Twojej restauracji</h1>
	    
	    {if isset($is_bg) && $a_user.bg!='' || !isset($is_bg) && $a_user.avatar!=''}
		    <p>Aktualny obraz:</p>
		    <img src="images/users/{$a_user.id_users}/{if !isset($is_bg)}avatar/{$a_user.avatar}{else}bg/{$a_user.bg}{/if}?t={$a_user.token_odswiezenia}" class="{if !isset($is_bg)}avatar whiteBorder{/if}">
	    {/if}
	    
	    <h2>1. Wybierz plik</h2>
	    {if isset($is_bg)}<p>(idealny wymiar zdjęcia to 1600x400 pikseli, pamiętaj, że krawędzie obrazka mogą być w profilu niewidoczne)</p>{/if}
	    
	    <form method="POST" action="{$_base_url}" class="jValidate">
	        <input type="hidden" name="module" value="users">
	        <input type="hidden" name="action" value="tworz_avatar">
	        <input id="fileupload" class="formNewContestImages jExtension" type="file" name="files" data-extensions="jpg jpeg gif png">
	        <br>
	        <br>
	        <div id="uploadedPhoto"></div>
	        
	        {if isset($is_bg)}
	        	<input type="hidden" name="is_bg" id="is_bg" value="1">
	        {/if}
	        <input type="hidden" name="uniqid" id="uniqid" value="{$uniqid}">
	        <input type="hidden" name="id" id="id_users" value="{$id}">
	        <input type="hidden" name="img" id="img">
	        <input type="hidden" id="x" name="x">
	        <input type="hidden" id="y" name="y">
	        <input type="hidden" id="w" name="w">
	        <input type="hidden" id="h" name="h">
	        <div class="center marginTop10">
	        	<input type="submit" value="Zapisz" id="tworzAvatar" disabled>
	        </div>
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
	        $('#fileupload').click(function(){
	            loadImageUpload('images/users/temp/',$('#uniqid').val());
	        });
	        
	        function loadImageUpload(saveUrl,uniqid)
	        {
	            'use strict';
	    
	            var base_url = $('body').attr('data-base-url');
	            var url = base_url+'javascript/libs/file_upload/server/php/index.php?saveUrl='+saveUrl+'&noThumb=1&uniqid='+uniqid;
	    
	            $('#fileupload').fileupload({
	                url: url,
	                fail: function (e,data) {
	                    alert('Błąd przesyłu zdjęcia');
	                },
	                done: function (e,data) {
	                    if(data.jqXHR.responseText.match(/error":"([A-Za-z\s]+)"/)[1]=='abort')
	                    {
	                        $.each(data.files, function (index, file) {
	                            
	                            var ext = file.name.split('.').pop();
	                            var name = uniqid+'.'+ext;
	
	                            $('#uploadedPhoto').html('<p class="h2">2. Kadruj obrazek</p><p>(Kliknij na obrazek, przeciągnij i zaznacz obszar, który chcesz, by był widoczny)</p><img src="'+base_url+'images/users/temp/'+name+'">');
	                            $('#img').val(base_url+'images/users/temp/'+name);
	                            
	                            function updateCoords(c)
	                            {
	                                  $('#x').val(c.x);
	                                  $('#y').val(c.y);
	                                  $('#w').val(c.w);
	                                  $('#h').val(c.h);
	                            }
	                            
	                            if(ext=='png' || ext=='gif')
	                                var bgColor = 'transparent';
	                            else
	                                var bgColor = 'black';
	                   
	                            $('#uploadedPhoto img').Jcrop({
	                              aspectRatio: $('#is_bg').length? 4 : 1,
	                              onSelect: updateCoords,
	                              bgColor: bgColor,
	                              onChange: function(){
	                                  $('#tworzAvatar').removeAttr('disabled');
	                              }
	                            });  
	                        });
	                    }
	                }
	            });
	        }
	    });
	    {/literal}
	    </script>
	</fieldset>
</section>