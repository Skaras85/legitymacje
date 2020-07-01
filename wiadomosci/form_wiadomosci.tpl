<section>
	{include file="wiadomosci/wiadomosci_menu.tpl"}
	
	
	<form method="post" action="{$_base_url}" enctype="multipart/form-data" class="jValidate formMessage">
	    <input type="hidden" name="module" value="wiadomosci">
	    <input type="hidden" name="action" value="wyslij_wiadomosc">
	    <input type="hidden" name="a_wiadomosc[id_wiadomosci]" value="{if isset($a_wiadomosc)}{$a_wiadomosc.id_wiadomosci}{/if}" id="id_wiadomosci">
	    {if session::who('admin')}
			<input type="hidden" name="a_wiadomosc[id_adresata]" value="{$a_wiadomosc.id_adresata}">
	    {/if}
	    
	    <fieldset class="fullWidth noPadding">
			<p class="h2">Wiadomość</p>
	        <label for="temat">Temat wiadomości</label>
	        <input type="text" class="jRequired" id="temat" name="a_wiadomosc[temat]" value="{if isset($a_wiadomosc) && !isset($smarty.session.form.a_wiadomosc)}{$a_wiadomosc.temat}{/if}{if isset($smarty.session.form.a_wiadomosc)}{$smarty.session.form.a_wiadomosc.temat}{/if}">
	        
	        <label for="tresc">Treść wiadomości</label>
	        <textarea id="tresc" name="a_wiadomosc[tresc]">{if isset($a_wiadomosc) && !isset($smarty.session.form.a_wiadomosc)}{$a_wiadomosc.tresc}{/if}{if isset($smarty.session.form.a_wiadomosc)}{$smarty.session.form.a_wiadomosc.tresc}{/if}</textarea>
	        
	        <label for="zalacznik">Dodaj załączniki</label>
	        <ul id="attachments">
	            <li><input type="file" name="a_zalaczniki[]" id="zalacznik" class="jExtension" data-extensions="jpg jpeg png gif doc tiff docx txt pdf csv xls xlsx xlt xml"></li>
	        </ul>
	        <br>
	        <span class="addNextAtachment">+ Dodaj kolejny</span>
	        <br>
	        <input type="submit" value="Wyślij wiadomość" class="marginTop20 green buttonIcon wyslijButton">
	    </fieldset>
	
	</form>
	
	<script type="text/javascript">
	{literal}
	    CKEDITOR.replace( "tresc",{
	        filebrowserBrowseUrl: "{/literal}{$_base_url}{$js}{literal}/libs/Filemanager-master/index.html",
	        customConfig : 'config-wiadomosc.js'
	    });
	{/literal}
	</script>
</section>