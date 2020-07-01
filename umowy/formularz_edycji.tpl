<section>
	<h1>Edytuj umowę</h1>
	<form id="form" method="post" action="{$_base_url}" class="jValidate">
		<input type="hidden" name="action" value="edytuj_umowe">
		<input type="hidden" name="module" value="umowy">
		<input name="a_umowa[id_umowy]" value="{$a_umowa.id_umowy2}" type="hidden">
		{if isset($lista_umow_admin)}<input name="lista_umow_admin" value="1" type="hidden">{/if}
		
		<label for="numer_umowy">Numer umowy:</label>
		<input type="text" name="a_umowa[numer_umowy]" class="jRequired" value="{$a_umowa.numer_umowy}">
		
		<p>Okres obowiązywania:</p>
		<div>
			<input type="radio" name="a_umowa[czas_umowy]" value="nieokreslony" class="czas_umowy" id="czas_nieokreslony" {if $a_umowa.okres_obowiazywania=='0000-00-00'}checked{/if}> <label for="czas_nieokreslony" class="inline">na czas nieokreślony</label>
		</div>
		<div>
			<input type="radio" name="a_umowa[czas_umowy]" value="okreslony" id="czas_okreslony" class="czas_umowy" {if $a_umowa.okres_obowiazywania!='0000-00-00'}checked{/if}> <label for="czas_okreslony" class="inline">na czas określony</label>
		</div>
		
		<div class="okres_obowiazywania {if $a_umowa.okres_obowiazywania=='0000-00-00'}hidden{/if}">
			<input type="text" name="a_umowa[okres_obowiazywania]" class="datepicker" value="{$a_umowa.okres_obowiazywania}">
		</div>
		<br>
		
		<label for="uwagi">Uwagi:</label>
		<input type="text" name="a_umowa[uwagi]" id="uwagi" value="{$a_umowa.uwagi}">
		
		<label for="email_naruszenia">E-mail do powiadomień ws. naruszeń przetwarzania danych osobowych:</label>
		<input type="text" name="a_umowa[email_naruszenia]" id="email_naruszenia" value="{$a_umowa.email_naruszenia}">
		
		<label for="email">E-mail do powiadomień dla umowy powierzenia danych osobowych:</label>
		<input type="text" name="a_umowa[email]" id="email" value="{$a_umowa.email}">
		
		<textarea name="a_umowa[tresc]" id="tresc">{$a_umowa.tresc}</textarea>
		
   		<input type="submit" value="Zapisz">

     </form>  
</section>


<script type="text/javascript">
{literal}
    CKEDITOR.replace( "tresc",{
        filebrowserBrowseUrl: "{/literal}{$_base_url}{mod_panel::$js}{literal}/libs/Filemanager-master/index.html",
        height: "550px"
    });
{/literal}
</script>