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
require_once("{$basepath}/global_modules/header.php"); ?>

<section>

	<style>
		#rules p {
			font-size: 16px !important;
			line-height: 1.2;
		}

		#rules b {
			font-weight: bold;
		}

		#rules a {
			color: cornflowerblue !important;
		}
	</style>
	<div id="rules" style="max-width: 800px;margin: 0 auto;">
		<p>
			<b>Net-hesten</b> er en side for børn og unge, hvor man har mulighed for at oprette et virtuelt stutteri og handle med heste for virtuelle penge. Det er gratis at oprette sig og spille på siden.
		</p>
		<p>
			På Net-hesten går vi meget op i, at alle medlemmer opfører sig ordentligt over for hinanden, og følger vores regler, så alle får en god oplevelse på siden. Hvis et medlem føler sig uretfærdigt behandlet eller har problemer, kan man altid kontakte stutteri Net-hesten i en privat besked eller på mail admin@net-hesten.dk.
		</p>
		<p>
			Vi godkender alle medlemmer til siden manuelt, og holder øje med mistænkelig adfærd, for at gøre vores til at spillet har en god atmosfære, og er et rart sted at opholde sig, også for dem der er af en yngre alder.
		</p>
		<p>
			Det er dermed ikke hvemsomhelst, der bare lige kan oprette en bruger, og begynde at opføre sig ubehageligt på siden.
		</p>
		<p>
			Både som forælder og som medlem på siden, kan man altid sende en e-mail til admin@net-hesten.dk hvis man har spørgsmål, klager eller bekymringer.
		</p>
		<p>
			Følg os på <a href="https://www.facebook.com/Nethesten/" target="_blank">Facebook</a><br />
			Følg udviklingen &lt;<a href="https://github.com/chrisolesen/net-hesten.dk/" target="_blank">GitHub</a>&gt;
		</p>
	</div>
</section>
<?php

require_once("{$basepath}/global_modules/footer.php");
