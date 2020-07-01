<a href="mailing/panel"><h2 id="panelTitle" title="Powrót">Mailing</h2></a>

<article>

    <p>Tytuł: {$a_mailing.title}</p>
    <p>Status: {$a_mailing.status}</p>
    <p>Wysłanych: {$a_mailing.number_of_sent}</p>
    <p>Data dodania: {$a_mailing.add_date}</p>
    {if $a_mailing.status=='closed'}
        <p>Data zakończenia: {$a_mailing.end_date}</p>
    {/if}
    <p>Adresaci: {$a_mailing.recipients}</p>
    
    <p>Treść:</p>
    {$a_mailing.text}

</article>