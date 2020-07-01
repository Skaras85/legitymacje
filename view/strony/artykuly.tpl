<section class="formSection">
	
	{if !$a_tag}
		<h1>Blog wegetariański</h1>
	{else}
		<h1>{$a_tag.tagname}</h1>
	{/if}
	
	{foreach $a_artykuly as $a_article}
	    <article class="articleWrapper">
	        <header class="clearfix">
	        	{if $a_article.img}
	        		<a href="{$_base_url}{$a_article.sludge},{$a_article.id_sites}" class="blogLinkImg"><img src="{$_base_url}images/sites/{$a_article.img}" alt="{$a_article.title}"></a>
	        	{/if}
	        	<div class="blogListContent">
		        	<h2><a href="{$_base_url}{$a_article.sludge},{$a_article.id_sites}">{$a_article.title}</a></h2>
		        	<div class="marginTop10">
			        	{$a_article.appetizer}<p><a href="{$_base_url}strony/{$a_article.sludge},{$a_article.id_sites}">czytaj dalej</a></p>
			        </div>
	        	</div>
	        </header>
	        
	    </article>
	    <hr>
	{/foreach}
	{include file="system_view/pagination.tpl"}
</section>