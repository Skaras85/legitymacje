<section>
	{include file='mailing/mailing_menu.tpl'}
	<h1>Szablon mailingu</h1>
	
	<form id="form" method="post" action="index.php" class="jValidate" enctype="multipart/form-data">
		<input name="action" value="zapisz_szablon" type="hidden">
		<input name="module" value="mailing" type="hidden">
		
		{if !empty($a_strona)}
			<input name="a_strona[id_mailing_szablony]" value="{$a_strona.id_mailing_szablony}" type="hidden">
		{/if}

		<fieldset class="fullWidth">
			<label for="email">email</label>
			<input class="jRequired jEMail autoWidth" autofocus="autofocus" name="a_strona[email]" id="email" type="text" value="{if isset($a_strona) && !isset($smarty.session.form.a_strona)}{$a_strona.email}{/if}{if isset($smarty.session.form.a_strona)}{$smarty.session.form.a_strona.email}{/if}">
	
			<label for="nadawca">Nadawca</label>
			<input class="jRequired autoWidth" name="a_strona[nadawca]" id="nadawca" type="text" value="{if isset($a_strona) && !isset($smarty.session.form.a_strona)}{$a_strona.nadawca}{/if}{if isset($smarty.session.form.a_strona)}{$smarty.session.form.a_strona.nadawca}{/if}">
	
			<label for="email_kopia">kopia na dres email:</label>
			<input class="jEMail autoWidth" name="a_strona[email_kopia]" id="email_kopia" type="text" value="{if isset($a_strona) && !isset($smarty.session.form.a_strona)}{$a_strona.email_kopia}{/if}{if isset($smarty.session.form.a_strona)}{$smarty.session.form.a_strona.email_kopia}{/if}">
	
			<label for="temat">temat</label>
			<input class="jRequired" name="a_strona[temat]" id="temat" type="text" value="{if isset($a_strona) && !isset($smarty.session.form.a_strona)}{$a_strona.temat}{/if}{if isset($smarty.session.form.a_strona)}{$smarty.session.form.a_strona.temat}{/if}">
	
			<label for="tresc">Treść <img src="{$_base_url}images/core/help_icon.png" alt="Pomoc" title="Pomoc" class="pokazPomoc" data-id="9"></label>
			<textarea name="a_strona[text]" id="tresc">{if isset($a_strona) && !isset($smarty.session.form.a_strona)}{$a_strona.text}{/if}{if isset($smarty.session.form.a_strona)}{$smarty.session.form.a_strona.text}{/if}</textarea>
			<br><br>
			
			<p>Załącznik</p>
			<ul id="lista_zalacznikow">
				<li><input type="file" class="zalacznik jExtension" name="zalaczniki[]" data-extensions="doc docx jpg png pdf"></li>
				<li><a href="#" id="szablony_mailing_dodaj_kolejny_zalacznik">+ Dodaj kolejny</a></li>
				{if !empty($a_zalaczniki)}
					{foreach $a_zalaczniki as $zalacznik}
						<li>Załączony plik: <a href="{$zalacznik}">{basename($zalacznik)}</a> - <a href="#" class="usun_zalacznik_szablonu_mailingi" data-zalacznik="{basename($zalacznik)}">usuń</a></li>
					{/foreach}
				{/if}
			</ul>
			
			<br><br>
			<input type="submit" value="Zapisz">
		</fieldset>
	</form>
	
	
	<script type="text/javascript">
	{literal}
	    CKEDITOR.replace( "tresc",{
	        filebrowserBrowseUrl: "{/literal}{$_base_url}{literal}javascript/libs/Filemanager-master/index.html",
	    });
	
	{/literal}
	</script>
</section>