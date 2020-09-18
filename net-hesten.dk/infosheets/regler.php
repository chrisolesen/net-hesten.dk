<?php

$basepath = '../..';
require_once "{$basepath}/app_core/db_conf.php";
require_once "{$basepath}/app_core/object_loader.php";
require_once "{$basepath}/app_core/user_validate.php";

if (isset($_SESSION['user_id'])) {
	user::register_timing(['user_id' => $_SESSION['user_id'], 'key' => 'last_active']);
	user::register_session(['user_id' => $_SESSION['user_id']]);
}
$admin = false;
$public_page = true;
require_once ("{$basepath}/global_modules/header.php"); ?>
<section>
	<style>
		#rules li,
		#rules p {
			font-size: 16px !important;
			line-height: 1.2;
		}
		#rules a {
			color:cornflowerblue !important;
		}
		#rules li {
			margin-bottom: 0.5em;
		}
	</style>
	<div id="rules" style="max-width: 100%;width:50%;min-width:280px;margin: 0 auto;padding-bottom:40px;float:left;">
		<h1>Net-hestens regler</h1>
		<p>
			Det er vigtigt, at alle brugerne på Net-hesten forstår og følger sidens regler. Reglerne er fastsat så ingen kan få succes med at snyde eller gøre andre dumme ting på Net-hesten. Hvis du overtræder en regel, får du enten en advarsel, en karantæne eller en direkte udsmidning fra siden, alt efter hvor slemt, det du har gjort, er.
		</p>
		<p>
			En advarsel er en løftet pegefinger, som betyder at det du har gjort ikke er i orden, og du skal forbedre din opførsel. Får man flere advarsler kan det føre til karantæne eller udsmidning, hvis Net-hesten vurderer det er nødvendigt.
		</p>

		<p>
			En karantæne er en time-out, hvor du i et stykke tid udelukkes fra en eller flere aktiviteter på siden. Når karantænen er udløbet, er du igen velkommen.
		</p>

		<p>
			En udsmidning betyder at dit stutteri spærres/lukkes, og du for ikke lov til, at oprette et nyt stutteri. Er man smidt ud vil man ikke blive velkommen på siden igen.
		</p>

		<p>
			Så længe du bruger siden til det den er lavet til, følger reglerne, hygger dig og har det sjovt, taler pænt og behandler andre med respekt, behøver du ikke at bekymre dig om at blive smidt ud.
		</p>

		<p>
			Har du på grund af en fejl eller tankeløshed kommet til at gøre noget du tror er forkert, så skriv til os og fortæl det. Vi vil altid gerne høre, hvis der er fejl på siden, og det er kun forbudt, hvis du udnytter det med vilje.
		</p>
		<p>
			Net-hestens regler gælder over alt på Net-hesten – på forummet, i postkassen, i chatten, i hestenes beskrivelser og alle andre steder på siden.
		</p>
		</div>
		<div id="rules" style="max-width: 800px;width:50%;min-width:260px;margin: 0 auto;padding-bottom:40px;float:left;">
		<h2>Konkret</h2>
		<ol>
			<li>
				§1. Det er ikke tilladt at tale grimt eller nedladende om bestemte kulturer, religioner, personer eller andet lignende.
			</li>

			<li>
				§2. Truende adfærd, ubehageligt sprogbrug og mobning er ikke tilladt på Net-hesten.
			</li>

			<li>
				§3. Det er ikke tilladt, at linke til materiale med anstødeligt indhold.
			</li>

			<li>
				§4. Det er ikke tilladt løgnagtigt at udgive sig for at være nogen form for ansat på siden.
			</li>

			<li>
				§5. Det er ikke tilladt at bruge Net-hesten som datingside eller lignende.
			</li>
			
			<li>
				§6. Det er tilladt at spørge "venner" på siden om deres Skype og lign. MEN! af hensyn til vores brugeres sikkerhed og privatliv, medfører det permanent bortvisning, hvis man blive afvist af flere der vælger at kontakte admin.
			</li>

			<li>
				§7. Det er ikke tilladt at spørge andre brugere om deres passwords eller lignende. (Ingen ansat på siden vil nogensinde spørge).
			</li>

			<li>
				§8. Det er ikke tilladt at spamme på siden, fx ved at sende de samme beskeder igen og igen.
			</li>

			<li>
				§9. Det er kun tilladt af have ÉT stutteri pr. person.
			</li>

			<li>
				§10. Det er tilladt at være to personer om ét stutteri – så længe de to brugere ikke har et andet stutteri ved siden af. MEN! hvis der opstår stridighed eller lign, forholder vi os til at den der har adgang til mailkontoen, officielt er ejeren. Så tag højde for dette!.
			</li>

			<li>
				§11. En hestetegner og/eller journalist må ikke dele et stutteri med en anden person.
			</li>

			<li>
				§12. Hvis man finder et ”hul” i systemet på den ene eller anden måde, skal man straks kontakte Net-hesten. Man bliver kun straffet (med sletning) hvis man bevidst snyder.
			</li>

			<li>
				§13. Så er der de uskrevne regler, som vi af gode grunde ikke kan fortælle om her. I sidste ende er det dog Net-hesten, der definerer reglerne, så alt kommer til at gå retfærdigt til på siden.
			</li>
		</ol>
	</div>
</section>
<?php

require_once ("{$basepath}/global_modules/footer.php");
