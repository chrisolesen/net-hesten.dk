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
$public_page = true;
require_once ("{$basepath}/global_modules/header.php"); ?>
<section>
	<style>
		 
		#rules {
			color:transparent;
		}
		#rules h1, 
		#rules h2, 
		#rules h3, 
		#rules p{
			color:black !important;
		}
		#rules h2, 
		#rules h3 {
			padding-top:1em;
		} 
		#rules p {
			font-size: 16px !important;
			line-height: 1.2;
			margin:0;
		}
		#rules p + p {
			margin-top: 1em;
		}
		#rules li { 
			font-size: 16px !important;
			line-height: 1.2;
		}
		#rules ul {
			padding: 10px 0;
		}
		#rules a {
			color:cornflowerblue !important;
		}
	</style>
	<div id="rules" style="max-width: 800px;margin: 0 auto;">
		<h1>Hjælp til NH</h1>
		<p>
			Her kan du finde en liste over spørgsmål, vi tit bliver stillet. Spørgsmålene er delt op i forskellige kategorier. Hvis du har brug for hjælp til et af nedenstående spørgsmål, skal du bare klikke på et af dem, og så kommer du til svaret. Kan du ikke finde dit spørgsmål på listen? Kontakt stutteri Net-hesten eller kig på vores liste over ansatte for at få hjælp.
		</p>
		<p>Nogle enkelte af linjerne er gennemsigtige, disse er ikke endnu blevet manuelt gennemgået til net-hesten 2.0</p>
		<h2>
			Oversigt
		</h2>
		<h3>
			VIGTIGT - SIKKERHED - GENERELT:
		</h3>
		<ul>
			<li><a href="#hvor_mange_stutterier">Hvor mange stutterier må jeg have og hvorfor?</a></li>
			<li><a href="#maa_man_dele">Må jeg gerne dele et stutteri med en anden?</a></li>
			<li><a href="#navne_skifte">Hvordan skifter jeg mit stutterinavn?</a></li>
			<li><a href="#ferie_pasning">Må jeg passe en andens stutteri mens vedkommende er på ferie?</a></li>
			<li><a href="delt_ip">Må man gerne være flere på samme IP-adresse?</a></li>
			<li>Hvorfor skal jeg have en gyldig email-adresse?</li>
			<li><a href="#hvordan_sletter_man_sig">Hvordan sletter jeg mit stutteri?</a></li>
			<li>Hvad gør jeg, hvis jeg tror jeg er blevet hacket, eller en anden har været på mit stutteri?</li>
			<li>Hvad gør jeg, hvis jeg er blevet snydt i en byttehandel?</li>
			<li>Findes der snydekoder til Net-hesten?</li>
			<li><a href="#hvordan_laver_jeg_selv_et_spil">Hvordan laver jeg selv en automatisk side som Net-hesten?</a></li>
			<li>Hvad sker der, hvis jeg bryder reglerne?</li>
		</ul>
		<h3>OM WKR OG BETALING AF WKR:</h3>
		<ul>
			<li><a href="#er_siden_gratis">Er siden gratis?</a></li>
			<li>Hvordan tjener jeg wkr uden at betale rigtige penge?</li>
			<li>Hvordan kan jeg købe mig til flere wkr?</li>
			<li>Er det sikkert at købe wkr?</li>
			<li>Hvad går de indbetalte penge til?</li>
		</ul>
		<h3>HESTETEGNER, MODERATOR, JOURNALIST PÅ SIDEN:</h3>
		<ul>
			<li><a href="#ht_hvordan">Hvordan bliver man hestetegner?</a></li>
			<li>Hvordan bliver man moderator?</li>
			<li>Hvordan bliver man journalist?</li>
			<li>Hvor kan jeg se en liste over alle ansatte på siden?</li>
		</ul>
		<h3>OM HESTENE:</h3>
		<ul>
			<li><a href="#alder_hvordan">Hvordan fungerer hestenes alder?</a></li>
			<li>Kan heste med 2 forskellige racer få føl sammen?</li>
			<li>Hvor mange hopper må jeg have i fol af gangen og hvorfor?</li>
			<li><a href="#græs_alle">Hvorfor er der ikke en knap så man kan sætte alle heste på græs samtidigt?</a></li>
			<li>Hvordan ved jeg om min hest er sjælden?</li>
			<li>Hvordan fungerer det når er føl bliver voksent?</li>
			<li><a href="#er_min_hest_sjaelden">Hvordan ved jeg om min hest er sjælden?</a></li>
			<li><a href="#hvad_naar_hesten_dor">Hvad sker der når min hest dør?</a></li>
		</ul>
		<h3>Indstillinger til spillet</h3>
		<ul>
			<li><a href="#deaktiver_banneret">Hvordan fjerner jeg banneret?</a></li>
			<li><a href="#slim_display">Hvordan gør jeg spillet smallere?</a></li>
		</ul>
		<h1>VIGTIGT – SIKKERHED – GENERELT:</h1>

		<h2 id="hvor_mange_stutterier">Hvor mange stutterier må jeg have og hvorfor?</h2>
		<p>Det er kun tilladt at have ét stutteri per person. Hvis vi opdager at en bruger har mere end ét stutteri, bliver de alle sammen slettet. Vi sletter selvfølgelig først brugere, når vi er 100% sikre på, at der er tale om snyd.</p>
		<p>Flere brugere må gerne deles om den samme computer. Vi kan se på IP adressen, hvis flere stutterier deler computer, men det er altså ikke et tegn på snyd i sig selv.</p>
		<p>Hvis du har flere stutterier, f.eks fordi du har søgt om flere ved en fejl, har slået dig sammen med en anden bruger om ét stutteri, eller har overtaget et stutteri fra en anden bruger, så skriv straks til stutteri Net-hesten, så vi kan finde en løsning. Hvis vi selv opdager, at en bruger har flere stutterier, bliver de alle omgående slettet.</p>
		<h2 id="maa_man_dele">Må jeg gerne dele et stutteri med en anden?</h2>
		<p>Net-hesten er bygget til at der er én person som styrer ét stutteri. Det er dog i orden at dele et stutteri, altså være 2 personer om at eje 1 stutteri, hvis man ikke har andre stutterier ved siden af. Hvis I har et stutteri til overs efter I har slået jer sammen på et andet stutteri, skal i skrive til Net-hesten så vi kan lukke det.</p>
		<p>Hvis man vælger at dele et stutteri er det på eget ansvar. Det er selvfølgelig i orden at spørge moderatorerne eller Net-hesten om råd hvis der opstår problemer, men man skal ikke forvente at de kan gennemskue hvem der skal have hvilke heste eller wkr hvis i bliver uvenner og vil gå hver til sit.</p>
		<p>Så et godt råd vil være at man tænker sig GODT om inden man deler et stutteri med nogen. Gør det kun med en i har kendt længe og stoler på. Vær enige om hvordan hestene skal fordeles hvis I vil gå hver til sit på et tidspunkt.</p>
		<h2 id="navne_skifte">Hvordan skifter jeg mit stutterinavn?</h2>
		<p>Du finder inde på "mit stutteri" knappen "rediger", her kan du bla.a. anmode om navneskife.</p>
		<p>Det koster 2.000.000 wkr at skifte sit stutterinavn</p>
		<p>Denne pris sænkes dog, med 100.000 wkr, for hver måned, siden du sidst skiftede dit navn</p>
		<p>Prisen kan sænkes helt ned til 0 wkr, og du bestemmer selv hvornår og hvor ofte du med den baggrund, vælger at skifte</p>
		<p>Vi har lavet det sådan, da vi ikke ønsker man skifter navn hele tiden, men samtidig anerkender, at måske kan det være du faktisk ikke lige synes så godt om navnet mere, flere år senere</p>
		<p>Alle navneskifte, bliver manuelt gennemgået og accepteret, udfra de samme vilkår som navne til nyeoprettede brugere.</p>
		<h2 id="ferie_pasning">Må jeg passe en andens stutteri mens vedkommende er på ferie?</h2>
		<p>Ja, man må gerne passe en andens stutteri, f.eks. hvis vedkommende er på ferie. Men hvis det ender med at man overtager stutteriet helt, så gælder reglen om flere stutterier, og det medfører sletning af begge stutterier. Hvis den oprindelige ejer ikke ønsker at have sit stutteri mere, så lad være med at logge ind på det igen, og skriv til stutteri Net-hesten for at få det lukket.</p>
		<h2 id="delt_ip">Må man gerne være flere på samme IP-adresse?</h2>
		<p>Flere brugere må gerne deles om den samme computer. Vi kan se på IP adressen, hvis flere stutterier deler computer, men det er altså ikke et tegn på snyd i sig selv. Hvis du f.eks. har søskende, der også spiller Net- hesten, er det helt okay at I er flere stutterier på samme IP-adresse. Det, der ikke er okay, er hvis du selv har flere stutterier.</p>

		Hvorfor skal jeg have en gyldig email-adresse?
		Det er vigtigt, at du opgiver en gyldig email-adresse når du opretter dig, da det er sådan du får dit kodeord til dit stutteri. Hvis du senere hen glemmer dit kodeord, er det også på denne måde, at du kan få tilsendt et nyt.

		<h2 id="hvordan_sletter_man_sig">Hvordan sletter jeg mit stutteri?</h2>
		<p>Hvis du ønsker dit stutteri slette, skal du skrive en mail til tech@net-hesten.dk, fra den samme mailkonto, som er tilknyttet dit stutteri, hvor du nævner, at du gerne vil slettes, og at du er inforstået med, at denne handling ikke kan fortrydes.</p>
		<p>Hvis du ikke har adgang til den mailkonto, der er tilknyttet dit stutteri, skal du skrive i privat besked, til stutteri "TechHesten" at du gerne vil slettes, herefter, skal sende en mail til tech@net-hesten.dk hvor du nævner at du vil slettes, samt din nuværende IP, du kan evt. gå ind på denne side "http://icanhazip.com/" for at få din IP. Når vi har kunnet bekræfte, at det er sansynligt, at din anmodning er fra den rigtige ejer, af stutteriet, bliver alt data inklusiv denne mail slette fra vores system, så vi gemmer altså ikke din IP som du sender os.</p>

		Hvad gør jeg, hvis jeg tror jeg er blevet hacket, eller en anden har været på mit stutteri?
		Det første du skal gøre, er at ændre dit kodeord. Derefter skal du tjekke, om du mangler noget – heste eller wkr. Det kan du for det meste se i din Kontooversigt. Når du har gjort det, kan du skrive til stutteri Net- hesten og fortælle hvad der er sket. Husk at skriv dato og tidspunkt for hvornår du har opdaget, at der var noget galt. Hvis andre personer kender dit password, skal du også sige det til os, så vi kan undersøge det nærmere. Derefter vil vi gøre alt hvad vi kan for at hjælpe dig videre.

		Hvad gør jeg, hvis jeg er blevet snydt i en byttehandel?
		Hvis du føler, at du er blevet snydt, skal du skrive til stutteri Net-hesten. Det er ikke altid, at vi kan gøre noget ved det, men hvis du har mister noget, vil vi gøre hvad vi kan for at hjælpe.

		Hvis vi ser et mønster i at andre brugere bevidst prøver at snyde folk, ser vi det som uacceptabel adfærd og det medfører sletning.

		Findes der snydekoder til Net-hesten?
		Nej, det gør der ikke. Hvis nogen fortæller dig, at der findes snydekoder, så anmeld dem til stutteri Net- hesten eller en moderator.

		<h2 id="hvordan_laver_jeg_selv_et_spil">Hvordan laver jeg selv en side som Net-hesten?</h2>
		<p>Net-Hesten er et spil, programmeret i henholdsvis PHP/MySQL samt HTML og Javascript det er en MEGET omfattende process, at lave et spil som net-hesten. Spillet i sig selv, består af flere tusinde linjer kode, men med det sagt, så er det samtidig en process, man kan lære, hvis man er ihærdig og har sin logiske sans i orden.</p>
		<p>Hvis du starter fra bunden af, vil det nok tage dig, 6+ år at lave et spil som net-hesten ordentligt, men så kommer du også ud på den anden side, med en teknisk kunden der er meget værdifuld.</p>
		<p>Et rigtigt godt sted at starte, hvis du er interesseret i at lære om programmering til internettet, er <a href="https://www.w3schools.com/" target="_Blank">W3Schools</a></p>

		Hvad sker der, hvis jeg bryder reglerne?
		Hvis du bryder reglerne, får du enten en advarsel, en karantæne eller en udsmidning.

		En advarsel er en løftet pegefinger, som betyder at det du har gjort ikke er i orden, og du skal forbedre din opførsel. Får man flere advarsler kan det føre til karantæne eller udsmidning, hvis Net-hesten vurderer det er nødvendigt.

		En karantæne er en time-out, hvor du i et stykke tid udelukkes fra en eller flere aktiviteter på siden. Når karantænen er udløbet, er du igen velkommen.

		En udsmidning betyder at dit stutteri spærres/lukkes, din IP blacklistes/spærres og du har ikke længere adgang til siden eller lov til at oprette et nyt stutteri. Er man smidt ud vil man ikke blive velkommen på siden igen.

		<h1>OM WKR OG BETALING AF WKR:</h1>
		<h2 id="er_siden_gratis">Er siden gratis?</h2>
		<p>Ja, det ér, og vil altid være, gratis at blive medlem af Net-hesten. Samt at spille spillet. Der er ingen af spillets funktioner, man kun har adgang til, ved at betale.</p>

		Hvordan tjener jeg wkr uden at betale rigtige penge?
		Du kan tjene wkr ved at sætte dine heste på græs, at tage chancen hver dag, at vinde i NH Lotteriet, at vinde i stævner og kåringer, at få en hingst udvalgt til bedækning, og at sælge heste dyrere end du har købt dem for.

		Hvordan kan jeg købe mig til flere wkr?
		Under ”Mit stutteri” skal du klikke på ikonet med ”Donation til NH”. Ikonet forestiller en bunke penge. Når du har klikket på ikonet, står der, hvordan det hele fungerer.

		Er det sikkert at købe wkr?
		Ja, det er helt sikkert. Betalingen går igennem PayPal.

		Hvad går de indbetalte penge til?
		Vedligeholdelse af siden, sidens server, og til at der kan laves nye funktioner på siden. Det er dyrt at have en side som Net-hesten, og de indbetalte penge hjælper os med at kunne holde siden oppe.

		<h1>HESTETEGNER, MODERATOR, JOURNALIST PÅ SIDEN:</h1>

		<h2 id="ht_hvordan">Hvordan bliver man hestetegner?</h2>
		<p>Det er svært at blive hestetegner, da der er meget høje krav. Hvis du har tegnet længe og har øvet dig, kan du prøve at blive hestetegner. De heste, du sender ind, skal være tegnet på Net-hesten skabeloner, og de skal være tegnet af dig selv. Du kan finde tegneguides og skabeloner på sidens heste-teger panel, når du er logget ind. Husk, du må ikke bruge Net-hesten's heste og skabeloner på andre sider, heller ikke selvom du selv har farvelagt skabelonen.</p>
		<p>Når du vil vise de heste du har lavet, skal de indsendes via HT-Panelet, hvor du også finder skabelonerne, hvis du er heldig, at blive godkendt som hestetegner, vil det herefter fremgå af panelet, hvor du også for adgang til flere muligheder.</p>
		<p>Hestetegnere løbende, net-hesten søger som minimum at kommenterer på alle indsendte heste, så du for feedback, på hvad der er godt / mindre godt ved din tegning.</p>
		<p>At tegne én fantastisk hest, er ikke en garanti for at blive HT, det er en title der er meget prestigefuld på net-hesten, så man skal tegne mindst et par stykker, der alle er gode.</p>

		Hvordan bliver man moderator?
		Man kan ikke ansøge om at blive moderator – det bedste man kan gøre er at være hjælpsom, venlig og ansvarlig. Vi kontakter selv folk, som vi mener kunne være egnede til jobbet. Det er et hårdt job at være moderator, og det er altså ikke et job for hvem som helst.

		Hvordan bliver man journalist?
		For at blive journalist skal du have en god idé og være glad for at skrive. Journalisternes arbejde er at lave artikler, historier, nyheder og andre spændende ting til Net-hesten. Artiklerne kan ses på forsiden af Net- hesten.

		Hvis du vil ansøge om at blive journalist skal du gå ind under Forummet under Konkurrencer. Her er der et emne, hvor du kan vise din idé til hvis du bliver journalist. Her kan du også læse mere om jobbet, og høre om det er noget for dig. Hvis du er heldig, bliver du kontaktet af stutteri Net-hesten over postkassen.

		Hvor kan jeg se en liste over alle ansatte på siden?
		Du kan se en liste over alle ansatte på siden her.

		<h1>OM HESTENE:</h1>
		<h2 id='alder_hvordan'>Hvordan fungerer hestenes alder?</h2>
		<p>Alle heste bliver 1 år ældre hver 40. dag.</p>
		<p>En hest bliver voksen, når den er 4 år gammel. Når den er 4 år, kan den selv få føl.</p>
		<p>En hest er gravid i 40 dage. Føllet er et føl indtil den bliver 4 år, hvorefter den får et nyt billede som voksen.</p>
		<p>Når en hest er 19 år gammel, kan den ikke længere få flere føl. En hest dør et tilfældigt tidspunkt mens den er 20-25 år gammel.</p>

		Kan heste med 2 forskellige racer få føl sammen?
		Nej, det kan de ikke. Man kan kun avle med 2 heste inden for samme race. På Net-hesten er racen ”Blanding” altså også en race for sig selv, og kan kun få føl med andre heste der også er inden for racen ”Blanding”.

		Hvor mange hopper må jeg have i fol af gangen og hvorfor?
		Man må have 10 hopper i fol(gravid) på samme tid. Hvis man har 10 hopper i fol i forvejen, kan man ikke købe flere hopper, der er i fol.

		Grunden til, at hvert stutteri kun må have 10 hopper i fol af gangen, er, at ellers ville antallet af nye heste stige alt for meget og alt for hurtigt.

		<h2>Hvordan ved jeg om min hest er sjælden?</h2>
		<p id="er_min_hest_sjaelden">
			Din hests sjældenhed bliver beregnet ud fra hvilken tegner som har lavet hesten, hvilken race hesten er, og om den er unik, original eller genereret.
			Alt dette betyder at vi ikke kan give dig en præcis pris på hvad din hest er værd. Vi kan udelukkende give en ca. pris for hvad den måske er værd.<br />
			<!--Unikke heste er mere værd en originale heste, og originale heste er mere værd end genereringsheste.-->
			<!--			Herunder finder du en opdateret liste på hvilke racer der er de mest sjældne på siden.
						Hestenes sjældenhed er inddelt sådledes:
						0-30 heste: Meget sjælden
						31-150 heste: Sjælden
						151-500 heste: Middel
						501-999 heste: Ikke sjælden
						1000+ heste: Slet ikke sjælden -->
		</p>
		<h2>Hvad sker der når min hest dør?</h2>
		<p id="hvad_naar_hesten_dor">Din hest dør når den er mellem 20 og 25 år gammel. Når hesten er død får du en besked fra dyrlægen hvori du finder en oversigt over antal af føl, og rosetter den givende hest har fået.<br />
			Du får 80% af hestens værdi udbetalt. </p>



		<h2 id="græs_alle">Hvorfor er der ikke en knap så man kan sætte alle heste på græs samtidigt?</h2>
		<p>Græsning er først og fremmest en mulighed for at give de mindre stutterier nogle wkr at starte på. Hvis et stutteri på 500 heste bare kunne trykke på én knap for at sætte sine heste på græs, så ville det bare virke som en gigantisk penge-generator. Så derfor bliver det ikke lavet sådan. Hvis man ikke orker at sætte alle sine heste på græs, er det muligvis fordi man har for mange heste.</p>

		Hvordan ved jeg om min hest er sjælden?
		Det er svært at give retningslinjer efter hvornår en hest er sjælden eller ikke. Det hele kommer jo i bund og grund an på, hvor mange der gerne vil eje din hest, og hvor mange wkr de er villige til at give for den.

		Hvis din hest er ”original” betyder det, at den er den første hest med dette billede på siden. Disse heste er derfor lidt sjældne – men der kan altså komme andre heste med samme billede. Din vil dog stadig være den første.

		Hvis din hest er ”unik” betyder det, er den er den ENESTE hest med dette billede på siden. Disse heste er derfor mere sjældne, da der aldrig vil kunne komme en anden hest med dette billede, mens hesten selv lever.

		Derudover kan din hest blive mere værd, hvis den har vundet mange stævner, eller er en race som mange gerne vil have. Men for det meste udgør udseendet og statussen af hesten (original/unik) om hesten er sjælden eller ikke.

		Hvordan fungerer det når er føl bliver voksent?
		Når et føl bliver 4 år, bliver det voksent. Når det bliver voksent stiger det i værdi, og det skifter udseende til en voksen hest. Føl kan godt skifte farve når de bliver voksne.

		Hvis man er meget heldig, kan ens føl blive unikt eller originalt. Dette kan man dog først se, når føllet er blevet 4 år gammelt.
		<h1>Indstillinger til spillet</h1>
		<h2>Hvordan fjerner jeg banneret?</h2>
		<p id="deaktiver_banneret">For at fjerne banneret skal du gå ind på "Mit stutteri" Derinde finder du en blå knap hvor der står "indstillinger" Den trykker du på. Der finder du den linje hvor der står banner, og ændre det fra 'vis' til 'skjul'</p>
		<h2>Hvordan gør jeg spillet smallere?</h2>
		<p id="slim_display">Gå ind i "Mit stutteri" Derinde finder du en blå knap hvor der står "indstillinger"  Den trykker du på. Der finder du den linje hvor der side størrelse, og ændre det fra 'Fuld' til 'Smal' </p>
		<br />
		<br />
		<br />
	</div>
</section>
<?php

require_once ("{$basepath}/global_modules/footer.php");
