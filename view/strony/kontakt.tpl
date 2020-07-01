<article class="articleSite">
    <div id="articleSite"></div>
    <div class="siteWrapper clearfix">

    	<div id="kontaktContent">
    		<h1>{$a_strona['title']}</h1>
			{$a_strona['text']}
		</div>

		<form action="index.php" method="POST" id="kontaktForm" class="jValidate">
			<input type="hidden" name="module" value="panel">
			<input type="hidden" name="action" value="wyslij_maila">
			<h2>{$a_napisz.title}</h2>
			{$a_napisz.text}<br>
			<label for="imie">Imię:</label>
			<input type="text" name="imie" id="imie">
			<label for="email">Email lub telefon:</label>
			<input type="text" name="email" id="email" class="jRequired jEMail">
			<label for="text">Treść wiadomości:</label>
			<textarea id="text" name="text" class="jRequired"></textarea><br>
			<input type="submit" value="Wyślij">
		</form>
	</div>
    

</article>
