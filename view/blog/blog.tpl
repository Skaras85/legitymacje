{if $a_artykuly}
    {foreach $a_artykuly as $a_article}
        <article class="articleWrapper">
            <header>
                <h2>
                    <a href="{$_base_url}sites/{$a_article.sludge},{$a_article.id_sites}">{$a_article.title}</a>
                </h2>
            </header>
            {$a_article.appetizer}
            <footer>
                <p class="blogArticleAddDate">
                    Dodany: {$a_article.add_date|substr:0:10}
                </p>
                <p class="blogArticleCommentsNumber">
                    Komentarzy: {$a_article.number_of_comments}
                </p>
                <p class="blogArticleRating">
                    Ocena: {$a_article.rating} / {$a_article.number_of_votes} {$a_article.number_of_votes_slownie}
                </p>
            </footer>
        </article>
        <hr>
    {/foreach}
{else}
    <p class="text">Brak wyników wyszukiwania dla frazy {$fraza}</p>
    <a href="{$_base_url}blog" class="text"><- Powrót do bloga</a>

{/if}

{include file="system_view/pagination.tpl"}