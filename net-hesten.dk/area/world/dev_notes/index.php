<?php
$basepath = '../../../..';
$title = 'dev_notes';
require "$basepath/app_core/object_loader.php";
require "$basepath/global_modules/header.php";

$sql = "UPDATE user_data_timing SET value = NOW() WHERE parent_id = {$_SESSION['user_id']} AND name = 'last_read_dev_notes'";
$link_new->query($sql);
$_SESSION['settings']['last_read_dev_notes'] = (new DateTime('NOW'))->format('Y-m-d H:i:s');
?>
<header><h1>Nethesten udviklings noter</h1>
	<p>Her skriver vi op hvad der bliver tilføjet løbende, så det er nemmere at følge med i.</p>
</header>
<style>
	.dev_note_main_content ol {
		list-style-type:decimal-leading-zero;;
		list-style-position:outside;
		padding: 0 0 0 2em;
	}
	.dev_note_main_content h2 {
		font-size:1em;
		font-weight:bold;
	}
	.dev_note_main_content h2 {
		margin-top: 0.5em;
	}
	.dev_note_main_content li {
		line-height: 1.2em;
	}
</style>
<div class="dev_note_main_content" style="padding: 0 0 30px;">
	<h2>Uge 8 - 2020</h2>
	<ol>
		<li>NetHestens kildekode er igang med at blive rullet ud på GitHub <a style="color:blue;" href="https://github.com/chrisolesen/net-hesten.dk">LINK</a></li>
		<li>Det vil ske løbende over de næste par uger</li>
		<li>Alle interesserede er velkommende til at deltage:</li>
		<li>Er du interesseret i at prøve at lære at programmerer, til web.</li>
		<li>Med udgangspunkt i net-hesten.dk så send en mail til tech@net-hesten.dk</li>
		<li>Det er nødvendigt at kunne læse engelsk i et vidst omfang, men det er det eneste krav.</li>
	</ol>
	<h2>Uge 49 - 2019</h2>
	<ol>
		<li>Stamtavler virker nu også når man besøger et stutteri</li>
		<li>Når man besøger et stutteri, minder visningen nu om ens eget stutteri, med de samme filtre</li>
		<li>Der er en ny indstilling under "mit stutteri". Her kan man kan vælge, om andre må byde på alle ens heste.</li>
		<li>- Den gør ikke noget endnu, men snart</li>
		<li>Man kan nu se antal udmærkelser en hest har fået, ved stævner og følkåringer</li>
	</ol>
	<h2>Uge 45 - 2019</h2>
	<ol>
		<li>Stamtavler virker på både Auktionshus og HesteHandleren nu</li>
	</ol>
	<h2>Uge 42 - 2019</h2>
	<ol>
		<li>"Zoom visning" af heste virker nu også på auktionshuset.</li>
		<li>Jeg har fikset en fejl, der gjorde at nogle føl blev til heste alt for tidligt.</li>
		<li>- Dem der allerede er heste, bliver først ældre igen, når de indhenter deres rigtige alder.</li>
		<li>- Det giver det mest overskuelige resultat for jer, det betyder at nogle hopper kommer til at have for mange føl.</li>
		<li>Udvidet info på heste virker i auktionshuset nu.</li>
	</ol>
	<h2>Uge 41 - 2019</h2>
	<ol>
		<li>Når man omdøber en hest, for man nu direkte besked, hvis noget gik galt, så man ikke spilder en masse tid.</li>
		<li>- Send gerne en PB med hvilken hest ID du forsøgte at omdøb.</li>
		<li>Når man er på en anden fane i sin browser, kommer der nu en "*" i siden navn, hvis man har fået en ny PB.</li>
		<li>På nogle mindre mobiler, kunne man ikke starte Avl, det kan man nu.</li>
		<li>- Skriv gerne til Tækhesten, hvis det driller andre steder på siden.</li>
	</ol>
	<h2>Uge 33 - 2019</h2>
	<ol>
		<li>Meget basal udgave af "stamtavler", der mangler en del design til det.</li>
		<li>- Men det virker, så ingen grund til I skal vente på det.</li>
		<li>- Klik på hestens billede under "mit stutteri" for at få mulighed for at åbne stamtavlen.</li>
	</ol>
	<h2>Uge 32 - 2019</h2>
	<ol>
		<li>Privat handel funktionen viser nu højst 50 heste ad gangen.</li>
		<li>- Løser en ustabilitet ved mere end 500 heste på langsomme enheder.</li>
		<li>Når man bliver overbudt på en auktion for man nu en PB med det samme fremfor indenfor 5 min.</li>
		<li>Man for også sine wkr tilbage med det samme fremfor nogle gange først når jeg manuelt opdager fejlen.</li>
		<li>Jeg fikset en fejl der gjorde at visse stutterier ikke kunne "besøges".</li>
		<li>Første del af konto oversigt</li>
		<li>- Det er ikke alt der automatisk kommer ind endnu.</li>
	</ol>
	<h2>Uge 15 - 2019</h2>
	<ol>
		<li>En meget simpel udgave af søg på alle heste, der vil blive tilpasset og forbedret i nærmeste fremtid</li>
	</ol>
	<h2>Uge 13 - 2019</h2>
	<ol>
		<li>Man kan nu filtrerer på racer igen (beklager fejlen!)</li>
		<li>- "Alle racer" er tilføjet i toppen så den står både i top og bund for bruger venlighed</li>
		<li>- Hvis "Alle racer" er valgt ignoreres de andre valg</li>
		<li>- Man kan vælge flere ved at holde "Ctrl" nede mens man klikker</li>
		<li>- Hvis man så "Ctrl" klikke på "Alle" er det en nem måde midlertidigt at deaktiverer sit ynglings valg</li>
		<li>Under status i filtre kan man vælge "ufolede hopper"</li>
		<li>Standard forslag på max alder er nu "alle" når man lige har nulstillet</li>
		<li>- Dette burde stoppe at en del af jer ved en fejl er kommet til at søge 0-0 år</li>
		<li>Auktioners slutdato tæller nedad fremfor at vise datoen</li>
		<li>- Giv gerne lyd hvis nogen meget gerne vil have den gamle måde som en indstilling :-)</li>
		<li>På auktionshuset er teksten "ingen bud endnu" udbyttet med "mindste bud: xxxxx-wkr"</li>
		<li>I "privat handel" starter visningen på "dine tilbud" men hvis den er tom, starter den på "dine salg", er den tom starter den på "dine heste"</li>
		<li>For at passe mere ind mere de flestes forventning - bliver alle ens filtre nu nulstillet når man logger ind</li>
		<li>- Der kommer senere, en indstilling under "mit stutteri" hvor man kan slå den funktion fra</li>
	</ol>
	<h2>Uge 9 - 2019</h2>
	<ol>
		<li>De 6 knapper(områder) på "Mit Stutteri" er flyttet til filtre under "status"</li>
		<li>- Filtreringen på "mit stutteri" er styrket en hel del i stabiliteten</li>
		<li>- Standard er nu "alle heste"</li>
		<li>- Filtreringen på "mit stutteri" lever mere op til hvad vi ser at de fleste af jer forventer</li>
		<li>Det er nu muligt at fjerne sit stutteri billede uden at upload et nyt</li>
		<li>- Der er indsat et standard billede hvis man ikke har et selv</li>
		<li>Hestenavne med æ/ø/å virker nu</li>
		<li>Hvor der under "mit stutteri" før stod om en hest i avl "IFolet ....." står der nu "Foler ca. ..." som er mere brugbart</li>
	</ol>
	<h2>Uge 8 - 2019</h2>
	<ol>
		<li>Nu virker filtrene også på PHH</li>
		<li>På listen over stutterier kan man nu søge på navne, flere filtre kommer senere</li>
		<li>Første del af forbedringer til slut på auktionerne - nu især tydeligere sluttider</li>
	</ol>
	<h2>Uge 49 - 2018 -> Uge 7 - 2019</h2>
	<ol>
		<li>Vi har holdt en forlænget juleferie men er igang igen :-)</li>
	</ol>
	<h2>Uge 48 - 2018</h2>
	<ol>
		<li>Funktion der gør at "net-hesten" kan lave "med konkurrancer" / lodtrækninger</li>
		<li>Føl kan ikke længere tilmeldes stævner, kun følkårringer</li>
		<li>Diverse admin-panel forbedringer</li>
		<li>- Visse hestebilleder kan kun blive specifikt hoppe eller hingst</li>
		<li>- Visse hestebilleder har en størrer chance for at vokse op ved ex. Jul/Halloween/Sommer</li>
	</ol>
	<h2>Uge 47 - 2018</h2>
	<ol>
		<li>En meget grundliggende udgave af "multi trådet PB"<br />Beregnet til at gøre livet lettere for vores RP skrivere, der ønsker at køre flere spor<br />Alle kommentarer fra ALLE er dog meget velkommende: hvad virker godt hvad gør ikke, har i idéer til forbedringer.</li>
	</ol>
	<h2>Uge 46 - 2018</h2>
	<ol>
		<li>Lidt love til vores mobil brugere<br />Fra nu af betragtes mobil-siden som anvendelig. <br />Derfor må alle problemer I oplever med den, meget gerne sende i PB til tækhesten</li>
		<li>De øverste 3 heste i PHH listen "dine heste" kunne ikke sælges, da pris kassen kom under hesten.</li>
		<li>Tegnere er nu sorteret alfabetisk i alle filtre</li>
		<li>Alle de nødvendige admin-paneler til at begynde at lave "Heste Tegner Centeret" færdigt</li>
	</ol>
	<h2>Uge 45 - 2018</h2>
	<ol>
		<li>PHH! Se i menuen til venstre for at prøve det af.<br />- Vi holder ekstra godt øje med funktionen de næste par dage<br />- Men sig endelig til, hvis noget driller</li>
	</ol>
	<h2>Uge 44 - 2018</h2>
	<ol>
		<li>Man kan bladrer i privat beskeder, så man kan se ældre beskeder</li>
		<li>Optimering af fejl finding, så vi lidt nemmere kan spotte fejl automatisk</li>
		<li>Denne side</li>
		<li>Opdatering af vores data politik</li>
		<li>Man kan nu aktiverer en smallere visning af siden (<a href="/infosheets/help.php#slim_display">Se her hvordan</a>)</li>
		<li>Man kan nu bladrer i heste handleren, når man har brugt filtre</li>
		<li>Nu blinker knappen hertil, ligesom PB, hvis der er noget man ikke har læst</li>
		<li>Man kan nemt besøge et stutteri, ved at trykke på deres navn i chatten</li>
		<li>- Ovenstående, også via online listen</li>
	</ol>
	<h2>Uge 32 - 2018</h2>
	<ol>
		<li>Lagt linjen for de næste par dages prioriteringer, så vi kommer godt i gang igen, efter sommerferien.</li>
		<li>De brugere der ikke havde været aktive før GDPR trådte i kraft, som skulle slettes, havde indledende kun fået anonymiseret deres kontis, og slettet deres adgang, nu er vi færdige med at admin kan konfiskerer deres heste, til salg på auktioner og slette dem helt og endeligt.</li>
		<li>Forbedringer til en del admin paneler, der gør admins arbejde med mange ting en del nemmere.</li>
		<li>Stævner burde nu køre automatisk fremfor manuelt.</li>
		<li>Fejlretning, hvis man skrev ex. "15.000" fremfor "15000" som minimums bud på auktionshuset, ville den tage det som 15, nu virker det som forventet.</li>
	</ol>
	<h2>Uge 28 - 2018</h2>
	<ol>
		<li>Man kan højst købe en hest fra hestehandleren hver 15 minutter, det skulle betyde der er meget mere sjovt at kigge på. Og næste HH party bliver også meget sjovere for flere spillere :-)</li>
		<li>Alt kode der driver net-hesten.dk er nu endelig 100% håndskrevet af mig, så vi ikke har nogle gamle komponenter der driller.</li>
	</ol>
	<h2>Uge 22 - 2018</h2>
	<ol>
		<li>Måden spillet foreslår andre stutterinavne på, når man skriver PB, er blevet forbedret kraftigt, (skulle også bruges til det nye PHH, der snart er færdig)</li>
		<li>Få en PB når en hest har folet</li>
		<li>Besøge et andet stutteri</li>
		<li>Se en liste med andre stutterier</li>
	</ol>
	<h2>Uge 21 - 2018</h2>
	<ol>
		<li>Alt muligt kedeligt juridisk vedr. GDPR ny EU Lov + nogle enkelte løse ting.</li>
	</ol>
	<h2>Uge 15 - 2018</h2>
	<ol>
		<li>Tryk på hestens billede fra "mit stutteri", for at åbne (mere om) boksen.</li>
		<li>Racer med æ/ø/å og andre special tegn, virker nu i forhold til stævner og følkårringer.</li>
		<li>Fremover hedder TechHesten -> "TækHesten" i spillet, for at forhindre der opstår flere steder hvor æ/ø/å ikke bliver testet ordentligt. (beklager problemerne)</li>
		<li>Stævnerne burde være fikset så de kan kører flydende nu :-)</li>
	</ol>
	<h2>Uge 15 - 2018</h2>
	<ol>
		<li>Klokken i chatten og online-listen er nu rettet så den passer.</li>
		<li>Ved en fejl fik man ikke beskeder fra auktionshuset i PB, om at man var blevet overbudt / havde solgt sin hest / havde vundet en auktion, og det jo trist når nu det var lavet 95% færdig. (det for man nu)</li>
		<li>Der er løst en fejl, der i få tilfælde kunne betyde at man ikke kunne sælge en bestemt hest til hestehandleren "den sagde man ikke ejede hesten selv om man gjorde"</li>
		<li>Man kan se i hestehandleren, hvor mange unikke og originale der er til salg, det er godt til fremtidige HH parties.</li>
		<li>Alle planlagte filtre er nu på: "sælge listen på auktionshuset"</li>
		<li>Man kan nu nulstille sit password direkte fra forsiden, hvis man har glemt det, så man ikke behøver bede en admin om hjælp. ( det må i selvfølgelig stadig gerne både via facebook og admin@net-hesten.dk)</li>
		<li>Chatten er opdateret, så den er klar til at blive gjort 100% live indenfor en overskuelig fremtid</li>
		<li>Man kan skifte sit password under "mit-stutteri" - jeg ved ikke lige hvorfor den ikke virkede.</li>
	</ol>
	<h2>Uge 13 - 2018</h2>
	<ol>
		<li>Helt utrolig ultra basal og MEGET LIDT pæn mobil-venlighed, siden bør dog rent teknisk kunne fungerer fra en mobil nu.</li>
		<li>- Det eneste jeg har gjort, er at fjerne ting der specifikt forhindrede den i at vises på en mobil, INTET er optimeret.</li>
		<li>- Man har lov til i PB til mig at fejlmelde på ting der er helt umuligt at gøre via mobil, som man kan via desktop, men beskeder om at det ikke er særligt pænt, bliver pænt ignoreret, da dette alene er et svar på at der var nogle stykker der gerne lige ville kunne tjekke spillet "på farten" - og det "fungerer (burde det) nu"</li>
		<li>Følgende filtreringsmuligheder er tilføjet på (hestehandleren, auktionshuset-sælge, "mit-stutteri") "race" / "køn" / "Alder: fra / til" + "Tegner" + "ID"</li>
		<li>- Det er lavet således, at man ikke behøver fjerne sine andre filtre hvis man bruger ID også, da et ID altid kun vil kunne match én hest, derfor vises den hest uanset hvad de andre filtre er indstillet til :-)</li>
		<li>Opgradering til det bagvedliggende kode til filtrering af heste listerne på alle sider. Så den samme kode nemt kan håndterer filtre på alle sider. Nu skal den bare sættes ind rundt omkring, den er sat ind på Hestehandleren (købe delen), og vil bliver sat ind alle steder, når vi lige er færdige med at tilføje flere muligheder end Race og køn. (Dette sker i løbet af i dag er planen.)</li>
	</ol>
	<h2>Uge 12 - 2018</h2>
	<ol>
		<li>Man kan "tage chancen" - lige nu er den meget simpel, men den fungerer, vi forbedrer den nok lidt i fremtiden :-) - Den er bundet til datoen, så man behøver ikke vente ex. 24 timer, du kan ta'chancen f.eks. 26-03-18 klokken 23:59 og så den 27-03-18 klokken 00:00, men kun 1 gang pr kalender dag :-)</li>
		<li>Man kan nu omdøbe sine heste, dette gøres under "mit stutteri" ved at klikke på dens navn, nu skulle der gerne komme en tekstboks, og et lille "Checkmark", når man klikker på den, så gemmer man det nye navn.</li>
		<li>Man kan nu sælge heste i hestehandleren, dette gøres ved at gå ind på hestehandleren og klikke på "sælg" der er kommet i top-linjen derinde. Her vil du nu få en liste over dine heste, og kan sælge dem, meget på samme måde som du køber heste :-) - vi ved filtreringen er mangelfuld, men den kommer på hastigt ! og senest i løbet af i morgen, hvis ikke der sker noget vildt og voldsomt uventet.</li>
	</ol>
</div>
<?php
require_once("{$basepath}/global_modules/footer.php");
