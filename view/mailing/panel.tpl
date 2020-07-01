<a href="panel"><h2 id="panelTitle" title="Powrót">Mailingi</h2></a>

<article>
    <a href="{$_base_url}mailing/formularz_nowego_mailingu" class="button">Dodaj nowy mailing</a>

    <p class="h2">Otwarte</p>
    {if $a_otwarte}
        <table>
            <tr>
                <th>Temat</th>
                <th>Data dodania</th>
                <th>Wysłanych</th>
                <th>Adresaci</th>
                <th>Usuń</th>
            </tr>
            {foreach $a_otwarte as $a_mailing}
            <tr data-id="{$a_mailing.id_mailing}">
                <td><a href="{$_base_url}mailing/pokaz_mailing/id/{$a_mailing.id_mailing}">{$a_mailing.title}</a></td>
                <td>{$a_mailing.add_date}</td>
                <td>{$a_mailing.number_of_sent}</td>
                <td>{$a_mailing.recipients}</td>
                <td><a href="#" class="usun_mailing" data-id="{$a_mailing.id_mailing}">usuń</a></td>
            </tr>
            {/foreach}
        </table>
    {else}
        <p>Brak</p>
    {/if}
    
    <p class="h2">Zamknięte</p>
    {if $a_zamkniete}
        <table>
            <tr>
                <th>Temat</th>
                <th>Data dodania</th>
                <th>Data zamknięcia</th>
                <th>Wysłanych</th>
                <th>Adresaci</th>
            </tr>
            {foreach $a_zamkniete as $a_mailing}
            <tr>
                <td><a href="{$_base_url}mailing/pokaz_mailing/id/{$a_mailing.id_mailing}">{$a_mailing.title}</a></td>
                <td>{$a_mailing.add_date}</td>
                <td>{$a_mailing.end_date}</td>
                <td>{$a_mailing.number_of_sent}</td>
                <td>{$a_mailing.recipients}</td>
            </tr>
            {/foreach}
        </table>
    {else}
        <p>Brak</p>
    {/if}

</article>