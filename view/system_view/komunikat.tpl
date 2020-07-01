<section class="formSection">
	<article>
		<p class="
		
		{if app::get_result()}
		    communicat_ok
		{else}
		    communicat_error
		{/if}
		">{view::get_message()}</p>
		
		<a href="{router::go_back()}" class="button">Wstecz</a>
	</article>
</section>