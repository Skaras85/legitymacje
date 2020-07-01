<section>
	<h1>Formularz ponaglenia</h1>
	<form id="form" method="post" action="{$_base_url}" class="jValidate" enctype="multipart/form-data">
		<input type="hidden" name="action" value="wyslij_ponaglenie">
		<input type="hidden" name="module" value="rozliczenia">
		<input name="a_ponaglenie[id_zamowienia]" value="{$a_zamowienie.id_zamowienia}" type="hidden">
		
		<label for="title">Tytuł</label>
		<input class="jRequired" autofocus="autofocus" name="a_ponaglenie[title]" id="title" type="text" value="{$a_tresc.title}">
	            		
		<label for="tresc">Treść</label>
		<textarea name="a_ponaglenie[text]" id="tresc">{$a_tresc.text}</textarea>
	            		
	       <input value="Wyślij" type="submit">
	    </section>
	</form>
	
	
	<script type="text/javascript">
	{literal}
	    CKEDITOR.replace( "tresc",{
	        filebrowserBrowseUrl: "{/literal}{$_base_url}{mod_panel::$js}{literal}/libs/Filemanager-master/index.html",
	    });
	{/literal}
	</script>
</section>