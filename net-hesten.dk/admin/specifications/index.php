<?php

$title = 'Specifikationer';
$basepath = '../../..';
require "$basepath/m.net-hesten.dk/admin/elements/header.php";
?>
<?php

if (!in_array('global_admin', $_SESSION['rights']) && !in_array('admin_panel_access', $_SESSION['rights'])) {
	ob_end_clean();
	header('Location: /');
}
?>
<section class="tabs" style='color:black;text-shadow:none;'>
	<nav>
		<ul>
			<li data-target="horses_generel">Heste Generelt</li>
			<li data-target="horses_artists">Heste Tegnere / Skabeloner</li>
			<li data-target="horses_avatars">Spøgelses Heste / Avatars</li>
			<li data-target="articles">Journalister</li>
			<li data-target="economy_generel">WKR generelt</li>
			<li data-target="economy_supporter">WKR supporter levels</li>
			<li data-target="economy_horsesalesman">WKR Hestehandleren</li>
			<li data-target="economy_auctions">Auktioner</li>
		</ul>
	</nav>
	<section data-zone="horses_generel">
		<header><h1 class="raised">Heste Generelt</h1></header>
		<div style='font-size: 1.2em;'>
			<ul>
				<li>Name <= 35 chars </li>
				<li>Racer skal liste mest vindende og mest værdifulde i den race.</li>
				<li>Racer skal have beskrivelser</li>
				<li>Der skal oprettes sub-racer</li>
				<li>- En hest for tildelt en sub-race i forbindelse med folingen, der kan vælges mellem forældrenes sub-racer.</li>
				<li>- sub-racer er rent kosmetisk info</li>
				<li>- sub-racer deler billedebank / stats / beskrivelse.</li>
			</ul>
		</div>
	</section>
	<section data-zone="horses_artists">
		<header><h1 class="raised">Heste Tegnere / Skabeloner</h1></header>
		<div style='font-size: 1.2em;'>
			<ul>
				<li>Løbende løn er en god idé.</li>
				<li>Skabeloner skal på sigt strømlignes således at "Width delt med Height <= 1.4"</li>
				<li>- En hest skal have en Height = 200px</li>
				<li>- Et føl skal have en Height = 125px</li>
				<pre>
					
				Kode
				/* Filename format */
				/* 
				* base64encode (
				* {
				* 'artists':{$artist-1-id,$artist-2-id,$artist-3-id},
				* 'date':mysql now(),
				* 'height':'9999',
				* 'width':'9999',
				* 'template':$template-ID
				* } 
				* );
				*/
				</pre>
			</ul>
		</div>
	</section>
	<section data-zone="horses_avatars">
		<header><h1 class="raised">Heste Tegnere / Skabeloner</h1></header>
		<div style='font-size: 1.2em;'>
			<ul>
				<li>Man kan få en 'avatar' som er en spøgelses hest</li>
				<li>- Der kan tilkøbes grafik layers til den</li>
				<li>- Der kan tilknyttes 'et billed med udstyr' til hvert lag</li>
				<li>- Der skal laves et metagame omkring at opbygge værdien af denne hest/avatar</li>
				<li>- Der skal være specielle stævner for disse heste (når evner tilføjes)</li>
				<li>- De skal være udødelige</li>
				<li>- Man skal mod wkr betaling kunne tegne(få tegnet) helt sit eget billede</li>
				<li>- - Selve heste skal dog være 'Ghost' gennemsigtig</li>
			</ul>
		</div>
	</section>
	<section data-zone="articles">
		<header><h1 class="raised">Journalister</h1></header>
		<div style='font-size: 1.2em;'>
			<ul>
				<li>En journalist aflønnes efter ønske om at kunne opnår supporter level 2.5 alene ved virket.</li>
				<li>- Det er målet at der må skrives maks 5 artikler pr måned.</li>
				<li>En godkendt artikel giver hermed 19.000 wkr pr styk.</li>
				<li>Alle kan sende en artikel ind, og få løn samt point ved godkendelse</li>
				<li>Artikelpoints tabes 3 mdr. efter de er optjent. </li>
				<li>Titlen tildeles manuelt</li>
				<li>2 artikel points i gennemsnit pr 3 seneste måneder er kravet for at beholde titlen.</li>
			</ul>
		</div>
	</section>
	<section data-zone="economy_generel">
		<header><h1 class="raised">WKR generelt</h1></header>
		<div style='font-size: 1.2em;'>
			<ul>
				<li>Der skal skabes incitament (samt mulighed) for at bruge sine wkr = money-sink.</li>
				<li>Det er skal stabiliceres i indkomst på tværs af alle aktiviteter.</li>
				<li>Det ønskes opnået, at supporter level guld(level 2) koster 70% af hvad den gennemsnitslige bruger tjener om måneden.</li>
				<li>- Således skaber diamant(level 3) supportere (med 140%) negativ inflation.</li>
				<li>Der ønskes en betydeligt sundere inflation. Målet er ikke fastsat.</li>
				<li>Lige nu er der en kraftig inflation på flere 1000% afhængig af aktivitet.</li>
			</ul>
		</div>
	</section>
	<section data-zone="economy_supporter">
		<header><h1 class="raised">WKR supporter levels</h1></header>
		<div style='font-size: 1.2em;'>
			<ul>
				<li>Der skal være 3 niveauer 'names-pending' (sølv guld diamant)</li>
				<li>Opnås ved at 'donerer' den angivne mængde WKR til spillet, via en funktion der skabes.</li>
				<li>Således er egentlig køb for rigtige penge ikke strengt nødvendigt.</li>
				<li>Stabiliseres til 5 - 10 - 20 kr pr måned, ved køb for 50kr (380.000 wkr) adgangen.</li>
				<li>1 kr = 7600 wkr.</li>
			</ul>
		</div>
	</section>
	<section data-zone="economy_horsesalesman">
		<header><h1 class="raised">WKR Hestehandleren</h1></header>
		<div style='font-size: 1.2em;'>
			<pre>
			HH køber heste til 80% af værdi 
			- Dog kun hvis han ikke har 2 af den samme
			- Der kommer 5 helt tilfældige genererings heste i timen på HH
			- Alle heste der har boet på HH i 3 dage eksporteres af HH
			Eksport giver 70% af værdi
			- Kan ikke bruges for unikke / originale
			- Kan ikke købes igen (fremgår stadig af stamtavler osv.)
			- I øvrigt ubegrænset
			Heste der dør ved ens stutteri giver penge på lige fod med nuværende
			</pre>
		</div>
	</section>
	<section data-zone="economy_auctions">
		<header><h1 class="raised">Auktioner</h1></header>
		<div style='font-size: 1.2em;'>
			<pre>
			Alle kan frit vælge hvilken dag deres auktioner slutter, indenfor 1 - 14 dage i fremtiden,
			Alle auktioner slutter klokken 17 den valgte dag.
			
			For at sætte en hest på auktion betales:
			- 5% af mindste bud hvis det er større end 2x hestens værdi
			- 1% af køb nu pris (hvis den angives)

			For en gennemført auktion betaler sælger:
			- Hvis hesten er solgt for under 100.000wkr: 500wkr
			- Hvis hesten er solgt for mere end 100.000wkr: 0.5% af salgs prisen.
			
			Et bud på en auktion koster 0 wkr og skal:
			- Hvis det højeste bud er under 250.000wkr byde mindst 2500 over.
			- Hvis det højeste bud er på eller over 250.000wkr byde mindst 1% højere.

			Man for en besked i sin indbakke hvis man bliver overbudt.
			- Man kan tilvælge mail.

			</pre>
		</div>
	</section>
</section>	
<script type="text/javascript">
	jQuery('.tabs nav li').each(function () {
		jQuery(this).click(function () {
			jQuery('.tabs > section').removeClass('visible');
			jQuery('.tabs > section[data-zone="' + jQuery(this).attr('data-target') + '"]').addClass('visible');
		});
	});
</script>
<?php

require "$basepath/m.net-hesten.dk/admin/elements/footer.php";
