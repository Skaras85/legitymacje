<div class="article">
<h1>{lang::get('galerie-zdjec')}</h1>

{if $a_galerie}
    <ul id="galleryItems" data-type="galleries" class="{if session::who('admin')}galleryItemsAdmin{/if} clearfix">
    	{foreach $a_galerie as $a_galeria name=gallery}
    		<li data-id="{$a_galeria.id_galleries}" class="galleryWrapper{if $a_galeria.is_visible==0} galleryInvisible{/if}" style="{if ($smarty.foreach.gallery.index+1)%3==0}margin-right: 0;{/if}{if $a_galeria.mainphoto!=NULL}background-image: url({app::base_url()}images/galleries/{$a_galeria.id_galleries}/thumbnail/{$a_galeria.mainphoto}){/if}">
    			<a href="galerie/{$a_galeria.sludge},{$a_galeria.id_galleries}" class="galleryLink">
    				{if $a_galeria.title}<p class="title" data-sludge="{$a_galeria.sludge}">{if $def_lang==$lang}{$a_galeria['title']}{else}{$a_galeria["title_`$lang`"]}{/if}</p>{/if}
    			</a>
    		</li>
    	{/foreach}
    </ul>
{/if}
</div>
