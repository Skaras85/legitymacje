<div class="article">
<h1>{lang::get('galeria-zdjec')} {if $def_lang==$lang}{$a_gallery['title']}{else}{$a_gallery["title_`$lang`"]}{/if}</h1>

{if $a_photos!=false}
<ul id="galleryItems" class="clearfix {if session::who('admin')}galleryItemsAdmin{/if}" data-type="photos" data-id="{$a_gallery.id_galleries}">
    {foreach $a_photos as $a_photo name=gallery}
        <li data-id="{$a_photo.id_photos}" class="galleryWrapper{if $a_photo.is_visible==0} galleryInvisible{/if}">
            <a rel="gallery[fancy]" class="galleryLink" data-src="{app::base_url()}galerie/zdjecie/id_galerii/{$a_photo.id_galleries}/id_zdjecia/{$a_photo.id_photos}" href="{app::base_url()}images/galleries/{$a_photo.id_galleries}/{$a_photo.filename}" title="{$a_photo.title}">
                <img src="images/galleries/{$a_photo.id_galleries}/thumbnail/{$a_photo.filename}" alt="{$a_photo.title}">
                {if $a_photo.title}<p class="title">{$a_photo.title}</p>{/if}
            </a>
        </li>
    {/foreach}
</ul>
{else}
    <p class="communicat_error">Brak zdjęć w tej galerii</p>
{/if}
</div>