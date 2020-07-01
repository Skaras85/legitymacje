{if $number_of_pages>1}
<section class="paginationWrapper">
    <div class="paginationLabel">Strona {$current_subpage} z {$number_of_pages}</div>
    <ul class="paginationContent">
        {if $current_subpage!=1}
            <li><a href="{$_base_url}{$pagination_data}/numer-strony/1{if isset($pagination_data_extra)}/{$pagination_data_extra}{/if}">Pierwsza</a></li>
            <li><a href="{$_base_url}{$pagination_data}/numer-strony/{$current_subpage-1}{if isset($pagination_data_extra)}/{$pagination_data_extra}{/if}" class="prevSite">Poprzednia</a></li>
        {else}
            <li><a>Pierwsza</a></li>
            <li><a class="prevSite">Poprzednia</a></li>
        {/if}
        
        {if $number_of_pages<5}
            {for $page=1 to $number_of_pages}
                {if $page==$current_subpage}
                   <li class="chosen"><input type="text" name="numer-strony" value="{$page}" class="numeric"></li>
                {else}
                   <li><a href="{$_base_url}{$pagination_data}/numer-strony/{$page}{if isset($pagination_data_extra)}/{$pagination_data_extra}{/if}">{$page}</a></li>
                {/if}
            {/for}
        {else if $current_subpage-2>0 && $current_subpage+2<=$number_of_pages}
            {for $page=$current_subpage-2 to $current_subpage+2}
                {if $page==$current_subpage}
                   <li class="chosen"><input type="text" name="numer-strony" value="{$page}" class="numeric"></li>
                {else}
                   <li><a href="{$_base_url}{$pagination_data}/numer-strony/{$page}{if isset($pagination_data_extra)}/{$pagination_data_extra}{/if}">{$page}</a></li>
                {/if}
            {/for}
        {elseif $number_of_pages-$current_subpage>=2}
            {for $page=1 to 5}
                {if $page==$current_subpage}
                   <li class="chosen"><input type="text" name="numer-strony" value="{$page}" class="numeric"></li>
                {else}
                   <li><a href="{$_base_url}{$pagination_data}/numer-strony/{$page}{if isset($pagination_data_extra)}/{$pagination_data_extra}{/if}">{$page}</a></li>
                {/if}
            {/for}
        {elseif $number_of_pages-$current_subpage<2}
            {for $page=1 to $number_of_pages}
                {if $page==$current_subpage}
                   <li class="chosen"><input type="text" name="numer-strony" value="{$page}" class="numeric"></li>
                {else}
                   <li><a href="{$_base_url}{$pagination_data}/numer-strony/{$page}{if isset($pagination_data_extra)}/{$pagination_data_extra}{/if}">{$page}</a></li>
                {/if}
            {/for}
        {/if}
        {if $current_subpage!=$number_of_pages}
            <li><a href="{$_base_url}{$pagination_data}/numer-strony/{$current_subpage+1}{if isset($pagination_data_extra)}/{$pagination_data_extra}{/if}" class="nextSite">Następna</a></li>
            <li><a href="{$_base_url}{$pagination_data}/numer-strony/{$number_of_pages}{if isset($pagination_data_extra)}/{$pagination_data_extra}{/if}">Ostatnia</a></li>
        {else}
            <li><a>Następna</a></li>
            <li><a>Ostatnia</a></li>
        {/if}
    </ul>
</section>
{/if}
