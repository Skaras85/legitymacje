<section>
	<p class="h1">Poradnik</p>
	
	<input type="text" name="fraza" placeholder="Szuaj" id="poradnik_szukaj">
	
	{foreach $a_sites as $index=>$a_strona}
	    <article class="article">
	    	<p class="h4 bold"><a href="#" class="poradnikTitle">{if $def_lang==$lang}{$a_strona['title']}{else}{$a_strona["title_`$lang`"]}{/if}</a></p>
	    	<div class="hidden poradnikContent">
	    	   {if $def_lang==$lang}{$a_strona['text']}{else}{$a_strona["text_`$lang`"]}{/if}
	    	   <p><a href="#" class="poradnikZwin button">zwiń</a></p>
	    	</div>
	    </article>
	{/foreach}
</section>