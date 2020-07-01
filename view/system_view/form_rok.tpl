<select name="rok" id="rok" class="jRequired autoWidth">
	{if isset($wszystkie_rok)}
        <option value="0" {if isset($wybrany_rok) && $wybrany_rok==0}selected{/if}>wszystkie</option>
    {/if}
    <option {if isset($wybrany_rok) && $wybrany_rok==$rok-2}selected{/if}>{$rok-2}</option>
    <option {if isset($wybrany_rok) && $wybrany_rok==$rok-1}selected{/if}>{$rok-1}</option>
    <option  {if isset($wybrany_rok) && $wybrany_rok==$rok}selected{/if}>{$rok}</option>
</select>