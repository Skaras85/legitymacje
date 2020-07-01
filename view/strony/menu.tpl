<article class="articleFloat">
    <h1>{$kategoria}</h1>

    {if $a_dania!=false}
        {if $id_dishes_categories==16 || $id_dishes_categories==36}
            {foreach $a_dania as $a_danie}
                <div class="dishItem drinkMenu clearfix">
                    <img src="{$a_danie.img}" alt="{$a_danie.name}" class="left">
                    <h3 class="drinkTitle">{$a_danie.name}</h3><span class="dishPrice">{$a_danie.price} PLN</span>
                    <hr>
                    {if $a_danie.pl_description!=NULL}<p>({$a_danie.pl_description})</p>{/if}
                    {if $a_danie.eng_description!=NULL}<p>({$a_danie.eng_description})</p>{/if}
                </div>
            {/foreach}
        {else if $id_dishes_categories==17}
            {foreach $a_dania as $a_danie}
                <div class="dishItem drinkMenu clearfix">
                    <h3>{$a_danie.name}</h3>
                    <div class="winePriceWrapper">
                        <span class="dishPriceBottle">{$a_danie.price_second} PLN</span>
                        <div class="dishBottle"></div>
                    </div>
                    <div class="winePriceWrapper">
                        <span class="dishPrice">{$a_danie.price} PLN</span>
                        <div class="dishGlass"></div>
                    </div> 
                    <hr class="clear">
                    {if $a_danie.pl_description!=NULL}<p class="italic dishOrigins">{$a_danie.pl_description}</p>{/if}
                    {if $a_danie.eng_description!=NULL}<p>{$a_danie.eng_description}</p>{/if}
                </div>
            {/foreach}
        {else}
            {foreach $a_dania as $a_danie}
                <div class="dishItem clearfix {cycle values='even,odd'}">
                    <h3>{$a_danie.name}</h3>
                    <span class="dishPriceNormal">{$a_danie.price} PLN</span>
                    <div class="clear"></div>
                    {if $a_danie.pl_description!=NULL}<p>({$a_danie.pl_description})</p>{/if}
                    {if $a_danie.eng_description!=NULL}<p>({$a_danie.eng_description})</p>{/if}
                </div>
            {/foreach}
        {/if}
     {else}
        <p class="communicat_error">Brak dań z tej kategorii</p>
     {/if}
    <p>Do rachunku powyżej 100 zł doliczamy 10% serwisu.</p>
</article>
<div class="clear"></div>
