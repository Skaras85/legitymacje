<!--
{head::doctype()}
<html class="no-js" lang="pl">
	<head>
        
	</head>
	<body data-base-url="{$_base_url}">
            {if !app::get_result() and view::get_message()!='' and $_subpage!='main/komunikat.tpl'}
                <p class='communicat_error'>{view::get_message()}</p>
            {/if}
            {if app::get_result() and view::get_message()!='' and $_subpage!='main/komunikat.tpl'}
                <p class='communicat_ok'>{view::get_message()}</p>
            {/if}
            {include file=$_subpage}
	</body>
</html>

-->
{include file=$_subpage}