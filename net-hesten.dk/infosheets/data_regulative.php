<?php

$basepath = '../..';
require_once "$basepath/app_core/db_conf.php";
require_once "$basepath/app_core/object_loader.php";
require_once "$basepath/app_core/user_validate.php";

if (isset($_SESSION['user_id'])) {
	user::register_timing(['user_id' => $_SESSION['user_id'], 'key' => 'last_active']);
	user::register_session(['user_id' => $_SESSION['user_id']]);
}

$admin = false;
$force_banner_off = true;
$public_page = true;
require_once("{$basepath}/global_modules/header.php"); ?>

<section>

	<style>
		#rules {
			color: transparent;
		}

		#rules h1,
		#rules h2,
		#rules h3,
		#rules p {
			color: black !important;
		}

		#rules h1,
		#rules h2,
		#rules h3 {
			padding-top: 1em;
		}

		#rules p {
			font-size: 16px !important;
			line-height: 1.2;
			margin: 0;
		}

		#rules p+p {
			margin-top: 1em;
		}

		#rules li {
			font-size: 16px !important;
			line-height: 1.2;
		}

		#rules li:before {
			content: "•";
			display: inline-block;
			color: black;
			padding-right: 5px;
		}

		#rules ul {
			padding: 10px 0;
		}

		#rules a {
			color: cornflowerblue !important;
		}
	</style>

	<div id="rules" style="max-width: 600px;margin: 0 auto;">
		<h1 style="padding-top:0;">Net-Hesten - Persondata politik </h1>
		<h2>Gældende siden 17. Marts 2020</h2>

		<h2>Forord</h2>
		<p>Den data vi gemmer på net-hesten falder ikke ind under GDPR</p>
		<p>Men da spillet primæret henvender sig til unge mennesker, og vi går meget op i vores brugeres rettigheder på nettet, har vi alligevel valgt beskrive udvalgte punkter</p>
		
		<h2>Oversigt</h2>

		<h3>Dine rettigheder</h3>
		<ul>
			<li><a href="#access">Adgang til vores oplysninger på dig</a></li>
			<li><a href="#deletion">Få slettet oplysninger om dig</a></li>

		</ul>

		<h3>Hvilke data har vi på dig</h3>
		<ul>
			<li><a href="#your_basic_data">Dine basis data</a></li>
			<li><a href="#chat_messages">Dine beskeder, til andre brugere samt i chatten</a></li>
			<li><a href="#saved_sessions">Dine onlinetider + IP i spillet, seneste 6 måneder</a></li>
		</ul>

		<h3>Din adgangskode er sikret hos os</h3>
		<ul>
			<li><a href="#hashing">Password hashing</a></li>
		</ul>

		<h3>Hvem deler vi dine data med</h3>
		<ul>
			<li><a href="#owners">Ejerne</a></li>
			<li><a href="#police">Politiet</a></li>
			<li><a href="#share_facebook">Facebook</a></li>
		</ul>


		<h1>Dine rettigheder</h1>

		<h2 id="access">Adgang til vores oplysninger på dig</h2>

		<p>Du har mulighed for at se de data vi har gemt.</p>
		<p>Det gøres ved at gå ind på "mit stutteri" og trykke "indstillinger" trykke "advancerede" og herefter klikke "tilsend kopi af persondata", herefter bliver det sent til din mail.</p>
		<p>Processen er automatisk, så hvis du ikke modtager mailen, kan du prøve igen, eller sende en mail til tech@net-hesten.dk, så for du det manuelt tilsendt.</p>

		<h2 id="deletion">Få slettet oplysninger om dig</h2>
		<p>Du har ret til at få slette oplysninger om din person, som du ikke ønsker vi har.</p>
		<p>Du kan selv slette f.eks. dit navn, ved at rette det på din profil.</p>
		<p>Der er en del data, vi kun kan slette ved helt at slette din bruger.</p>
		<p>Hvis du ønsker det, skal du blot sende en mail til tech@net-hesten.dk, og orrienterer om, at du ikke længere ønsker at være bruger på net-hesten.</p>
		<p>Herefter, vil vi hurtigst muligt, få slettet alt data om dig, og du vil modtage en endelig bekræftelse på at dette er sket.</p>

		<h1 id="what_data">Hvilke data har vi på dig</h1>

		<h2 id="your_basic_data">Dine basis data</h2>
		<p>Vi gemmer din mail tilknyttet til din bruger</p>
		<p>Der er mulighed for at tilknyttet et navn til sin profil, hvis du har gjort dette, er det naturligvis gemt i vores data</p>

		<h2 id="chat_messages">Dine beskeder, til andre brugere samt i chatten</h2>
		<p>Alt hvad der skrives i chat beskeder, og i private beskeder, gemmes naturligvis på serveren, hvorfor vi af gode grunde har adgang til det.</p>
		<p>Især værd at notere er, at hvis du sletter en PB, vil den anden bruger, ikke miste sin kopi, og selv hvis i begge sletter jeres udgaver, har vi stadig en kopi i systemet</p>
		<p>Hvis begger parter har slettet beskeden vil den blive ryddet fra vores server efter 1 år</p>

		<h2 id="saved_sessions">Dine onlinetider + IP i spillet, seneste 6 måneder</h2>
		<p>Eksempel på hvordan denne data ser ud, den går kun 6 måneder tilbage<br />
			{bruger id} - {start tid} - {slut tid} - {ip} <br />
			52194 2018-05-13 18:36:12 2018-05-13 19:09:43 127.0.0.1<br />
			52194 2018-05-13 12:16:20 2018-05-13 12:16:21 127.0.0.1<br />
			52194 2018-05-13 11:42:04 2018-05-13 11:42:05 127.0.0.1<br />
			52194 2018-05-13 09:01:45 2018-05-13 09:07:04 127.0.0.1<br />
		</p>

		<h1>Din adgangskode er sikret hos os.</h1>

		<h2 id="hashing">Password hashing</h2>
		<p>Hvis nogen skulle få fat i vores bruger tabeller</p>
		<p>Så er alle passwords i tabellen, hashed efter alle kunstens regler</p>
		<p>En hashing funktion er en envejskryptering, der tillader at bekræfte, at to ting var ens, men ikke at finde ud af hvad den først var.</p>
		<p>Derfor skulle det være praktisk uladesiggøreligt, at aflede jeres egentlig passwords ud af vores data.</p>
		<p>Når man opretter en bruger på net-hesten, gemmer vi en hashed udgave af jeres password.</p>
		<p>Når man så logger ind senere, så laver vi en hashed udgave, af det der blev skrevet som kode, og sammenligner de to.</p>
		<p>På den måde gemmer vi aldrig jeres rigtige kode nogen steder.</p>

		<h1>Hvem deler vi dine data med</h1>

		<h2 id="owners">Ejerne</h2>
		<p>Stutteri TækHesten styres af Chris Olesen: <br /> &bullet;&nbsp;<a href="https://www.facebook.com/chris.olesen1">Facebook</a> &bullet;&nbsp;<a href="https://www.linkedin.com/in/chrisolesen1/">LinkedIN</a></p>
		<p>Stutteri Net-Hesten styres af Line Jensen:<br /> &bullet;&nbsp;<a href="https://www.facebook.com/LineBineBone">Facebook</a></p>
		<p>I samarbejde driver vi net-hesten.dk som et privat projekt</p>
		<p>Begge parter har som værende de officielle ejere af sitet, ubeskåret adgang til alle oplysninger.</p>

		<h2 id="police">Politiet</h2>
		<p>Net-Hesten er et spil, der henvender sig til en relativt bred målgruppe, men især henvender vi os også til helt unge brugere</p>
		<p>I den forbindelse, kan der trods vores bedste intentioner, og indsats, potentielt opstå situationer, hvor det vil være nødvendigt at involvere politiet</p>
		<p>I så fald, vil vi dele alt relevant info, på de involverede brugere med ordensmagten</p>

		<h2 id="share_facebook">Facebook</h2>
		<p>Når du skriver til vores profil inde på Facebook, så gør du det, i henhold til Facebooks vilkår.</p>
		<p>Vi har ingen magt over, hvordan Facebook behandler, eller opbevarer data, vi kan sørge for, at det kun er Net-Hestens Admins der har adgang til vores udgave af dine data.</p>
		<p>Når du vælger at kontakte os via Facebook, vælger du derfor samtidig, at være underlagt deres data politikker.</p>
		<p>Helt konkret, går vi ind og sletter gamle beskeder, sendt til vores Facebook profil, efter ~ 1 måned, så vi ikke opbevarer dem unødvendigt.</p>
		<p>Hvis ovenstående ikke er acceptabelt for dig, bør du istedet kontakte os dirkete i spillet, eller på mail tech@net-hesten.dk eller admin@net-hesten.dk</p>
		<p>Vi deler INGEN data med Facebook, der er fremskaffet udenfor Facebook.</p>
		<p>Det vil f.eks. sige at når du klikker på et link til net-hesten fra Facebook.</p>
		<p>Så ved de at du klikkede på linket, men vi fortæller dem ikke noget om, hvad du så foretog dig på siden.</p>
		<br />
		<br />
		<br />
	</div>

</section>

<?php

require_once("{$basepath}/global_modules/footer.php");
