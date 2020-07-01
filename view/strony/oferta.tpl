<article class="articleSiteOferta">
    
    <div class="siteWrapper clearfix">
        <h1>{if $def_lang==$lang}{$a_strona['title']}{else}{$a_strona["title_`$lang`"]}{/if}</h1>
    
    	{if $def_lang==$lang}{$a_strona['text']}{else}{$a_strona["text_`$lang`"]}{/if}
    	
    	{if $a_strona['id_sites']==28}
    		<div id="boxy" class="stronaBoxy">
			    <div class="sectionContent clearfix">
			        {foreach $a_boxes as $a_box}
			            <div class="box">
			                <a href="{$_base_url}{$a_box.sludge}">
			                    <img src="images/sites/{$a_box.img}" alt="">
			                    <div class="boxContent">
			                        <h2>{$a_box.title}</h2>
			                        {$a_box.appetizer}
			                    </div>
			                    <span class="learnMore">dowiedz się więcej ></span>
			                </a>
			            </div>
			        {/foreach}
			    </div>
			</div>
    	{/if}

    </div>

</article>
