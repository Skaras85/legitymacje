<form action="index.php" method="POST" class="clearfix" id="user_form">
	<input type="hidden" name="module" value="users">
	<input type="hidden" name="action" value="zapisz_odczyt_przewodnika">
	<input type="hidden" name="token" value="1">
	<section>
		{if !empty($a_strony)}
			{foreach $a_strony as $index=>$a_strona}
			    <div class="step {if $index==0}chosenStep{else}hidden{/if} jValidate">    
			
			        <h2>{$a_strona.title}</h2>
			        
			        <div class="center ">
			        	{if !empty($a_strony[$index+1])}
			            	<input value="Dalej" type="submit" class="nextStep button green buttonIcon dalejButton">
			            {else}
			            	<input type="submit" class="button komunikaty_akceptacja green buttonIcon takButton" value="Rozpocznij korzystanie">
			            {/if}
			        </div>
			
			        {$a_strona.text}
			
			        <div class="center ">
			            {if !empty($a_strony[$index+1])}
			            	<input value="Dalej" type="submit" class="nextStep  button green buttonIcon dalejButton" >
			            {else}
			            	<input type="submit" class="button komunikaty_akceptacja green buttonIcon takButton" value="Rozpocznij korzystanie">
			            {/if}
			        </div>
			
			     </div>
		    {/foreach}
	    {/if}
    </section>
</form>
