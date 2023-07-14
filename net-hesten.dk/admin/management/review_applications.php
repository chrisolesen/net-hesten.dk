<?php
/* REVIEW: SQL Queries */
$basepath = '../../..';
require "{$basepath}/app_core/object_loader.php";
require "{$basepath}/global_modules/header.php";

if (!is_array($_SESSION['rights']) || (!in_array('global_admin', $_SESSION['rights']) && !in_array('admin_users_all', $_SESSION['rights']))) {
	ob_end_clean();
	header('Location: /');
	exit();
}

/* List applicants */
?>
<?php
if (filter_input(INPUT_GET, 'accept_application')) {


	function insert_applicant($applicant_id)
	{
		global $link_new;
		$initial_wkr = 50000;
		/**
		 *  Approve applicant
		 * * */
		$applicant = $link_new->query("SELECT * FROM user_application WHERE id = {$applicant_id} LIMIT 1")->fetch_object();
		if (!$applicant) {
			return [false, 'Ansøgningen findes ikke!'];
		}
		$test_old = $link_new->query("SELECT stutteri FROM `{$GLOBALS['DB_NAME_OLD']}`.Brugere WHERE stutteri = '{$applicant->username}' LIMIT 1")->fetch_object();
		$test_new = $link_new->query("SELECT username FROM users WHERE username = '{$applicant->username}' LIMIT 1")->fetch_object();
		if ($test_old || $test_new) {
			return [false, 'Stutteri navnet er optaget!, lav et afslag på brugeren hvor du beder dem finde et andet navn.'];
		}
		$link_new->query("INSERT INTO `{$GLOBALS['DB_NAME_OLD']}`.Brugere (stutteri, password, navn, email, penge, thumb, date) VALUES ('{$applicant->username}','{$applicant->password}','{$applicant->name}','{$applicant->email}','{$initial_wkr}','',now())");
		$new_user_id = $link_new->insert_id;
		$result = $link_new->query("INSERT INTO users (id, username, password, email) VALUES ('{$new_user_id}', '{$applicant->username}','{$applicant->password}','{$applicant->email}')");
		$new_user_id = $link_new->insert_id;
		if (!$result) {
			return [false, "Oprettelse af stutteriet fejlede, prøv igen, hvis fejlen fortsætteer så kontakt Admin, og sig der var fejl i 'Insert to users for ID: {$applicant_id}'."];
		}
		/* Insert user meta data */
		$result = $link_new->query("INSERT INTO user_data_numeric (parent_id, name, value, date) VALUES ('{$new_user_id}', 'wkr','{$initial_wkr}',now())");
		$result = $link_new->query("INSERT INTO user_data_timing (parent_id, name, value) VALUES ('{$new_user_id}', 'chance_last_taken','2018-01-01 00:00:00')");

		/* Send ingame mail to the user *//*
		$message = "Tillykke, du er nu blevet medlem af Net-Hesten."
				. "<br>Vi håber du vil få mange hyggelige timer herinde. Læs menupunktet <i>Hjælp</i> hvis der er noget som du er i tvivl om. "
				. "<br>Ellers kan du spørge i forummet <i>Hestesnak</i><br>"
				. "<br><font color=red>Husk lige at du ikke må have 2 stutterier - det er udsmidningsgrund!</font><br>"
				. "<br>Rigtig god fornøjelse :glad:";
		$link_new->query("INSERT into Postsystem (emne, besked, sender, modtager, mappe, date) VALUES ('Velkommen til Net-Hesten', '{$message}','admin@Net-hesten','{$applicant->username}','Indbakke',now() )", 'iso-8859-15', 'UTF-8'));
*/
		/* Send an email to the user */
		$to = "$applicant->username <$applicant->email>";
		$from = "From: Net-Hesten <admin@net-hesten.dk>\r\n";
		$subject = "Medlemskab ved Net-Hesten.dk, godkendt!";

		$message = '<!DOCTYPE html>';
		$message .= '<html><body style="background:#dfebd3;padding:20px;"><div style="background:rgba(146, 186, 106, 0.5);max-width:600px;margin:0 auto;padding:20px;border:1px solid white;">';
		$message .= "<b>Hej {$applicant->username},</b><br /><br />Dit stutteri er nu blevet godkendt af Net-Hesten.dk, tillykke :)."
			. '<br>Husk at læse "Hjælp" og "Regler" inden du går i gang. Ellers kan du gå ind på "hestesnak" i forummet og få nyttige tips. <br />Velkommen og god forøjelse.'
			. '<br><br>Gå ind på <a href=https://net-hesten.dk>Net-Hesten</a> og log ind med følgende: <br>'
			. "Stutteri: <b>{$applicant->username}</b> og den kode du valgte ved indmeldelse</b><br /><br />";
		$message .= '<b>Med venlig hilsen</b><br />';
		$message .= 'Net-Hesten - Teamet';
		$message .= '</div></body></html>';
		$headers = "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
		$headers .= "$from\r\n";

		/* Send email with verification */
		if (!mail($to, $subject, $message, $headers)) {
			return [false, "Brugeren er oprette men der kunne ikke sendes en mail, kontakt Admin som vil sende mailen manuelt, oplys bruger id: '{$new_user_id}' samt ansøger id: '{$applicant->id}'"];
		}
		$result = $link_new->query("DELETE FROM user_application WHERE id = {$applicant->id} LIMIT 1");
		if ($result) {
			return [true, 'Brugeren er oprette uden fejl og net-hesten er nu én spiller rigere, hurra!.'];
		}

		return [false, 'Uventet fejl, det burde ikke kunne ske - prøv igen og kontakt Admin hvis det fortsætter!'];
	}

	$applicant_id = (int) filter_input(INPUT_GET, 'accept_application');
	$result = insert_applicant($applicant_id);
?>
	<section>
		<header>
			<h2 class="raised<?= $result[0] ? '' : ' warning'; ?>"><?= $result[0] ? 'Success' : 'Failure'; ?></h2>
		</header>
		<p><?= $result[1]; ?></p>
	</section>
<?php } ?>
<?php if (filter_input(INPUT_GET, 'delete_application')) { ?>
	<section>
		<?php
		$applicant_id = filter_input(INPUT_GET, 'delete_application');
		if (filter_input(INPUT_GET, 'verify') == true || filter_input(INPUT_GET, 'status') == 'unverified') {
			if (filter_input(INPUT_POST, 'deleteMSG')) {
				$applicant = $link_new->query("SELECT * FROM user_application WHERE id = $applicant_id LIMIT 1")->fetch_object();
				$to = "$applicant->username <$applicant->email>";
				$from = "From: Net-Hesten <admin@net-hesten.dk>\r\n";
				$subject = "Medlemskab ved Net-Hesten.dk, ikke godkendt.";
				$message = filter_input(INPUT_POST, 'deleteMSG');
				$headers = "MIME-Version: 1.0\r\n";
				$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
				$headers .= "$from\r\n";
				mail($to, $subject, $message, $headers);
			}
			$link_new->query("DELETE FROM user_application WHERE id = {$applicant_id}");
		?>
			<header>
				<h2 class="raised">Success</h2>
			</header>
			<p>Du har nu slettet ansøgningen. <?= filter_input(INPUT_POST, 'deleteMSG') ? 'Der blev sendt en mail.' : 'Der blev ikke sendt en mail.'; ?></p>
		<?php
		} else {
		?>
			<script>
				function validate_form() {
					if (document.getElementById('deletemessage').value === '') {
						return confirm('Du er ved at slette "<?= filter_input(INPUT_GET, 'applicant_name'); ?>" uden at sende en mail!');
					}
				}
			</script>
			<header>
				<h2 class="raised warning">Er du sikker, på at du vil slette "<?= filter_input(INPUT_GET, 'applicant_name'); ?>"?</h2>
			</header>
			<p>Du er ved at slette ansøgningen med stutterinavn "<?= filter_input(INPUT_GET, 'applicant_name'); ?>",<br />Du bør skrive en mail til brugeren samtidig ved at udfylde tekstfeltet herunder, tryk derefter på bekræft for at slette ansøgningen.</p>
			<br />
			<form method="post" action="?delete_application=<?= $applicant_id; ?>&verify=true" onsubmit="return validate_form();">
				<textarea id="deletemessage" name="deleteMSG" placeholder="Skriv evt. en besked her som sendes til den mail der er på ansøgningen, hvis der ikke skrives noget så vil brugeren ikke vide at du har slettet ansøgningen."></textarea>
				<br /><br />
				<input type="submit" name="delete_applicant" value="Bekræft" />
			</form>
			<h1>Standard tekster</h1>
			<h2>Uegnet navn - Tal / volapyk osv.</h2>
			<textarea style="width: 40px;height: 40px;">Hej,<?= "\n\n"; ?>Jeg kan desværre ikke godkende din ansøgning, da vi af hensyn til den oplevelse og atmosfære vi forsøger at skabe i spillet, ikke tillader navne med ex. talserier så som 12345 og 1122, i det hele taget er lige tal noget vi kun accepterer meget begrænset.<?= "\n\n"; ?>Du er dog velkommen til at ansøge igen, med et mere passende navn, hvis du har problemer med at finde på noget, sjovt / spændende, så kan jeg sige, at der er mange af vores brugere som blot benytter "Stald [bynavn]" eller "Stutteri [eget-navn]" eller lign. heder man f.eks. Freja, kan Frejas heste eller Frej også være idéer, de meste er faktisk tilladt, men vi kræver bare at det lyder som nogenlunde som et egentligt navn, du kan også opdigte noget helt tilfældigt, så længe det er noget man kan udtale og der ikke er en masse af det samme bogstav i en kæde.<?= "\n\n"; ?>Med venlig hilsen<?= "\n"; ?>Net-Hesten</textarea>
			<h2>Flere X pr IP</h2>
			<textarea style="width: 40px;height: 40px;">Hej,<?= "\n\n"; ?>Jeg kan se at der er en eller flere andre brugere aktive på din IP, da det ikke er tilladt, at have mere end én bruger, så er jeg desværre nødt til at afvise din konto.<?= "\n\n"; ?>Hvis du har oprettet dig fra en efterskole eller lign. så opret dig evt. hjemmefra, eller send os en mail på admin@net-hesten.dk, fra den mail hvor du ønsker at bliver oprettet, så og ansøg derefter igen, dette kan du bare gøre med det samme, vi tjekker altid mailen inden vi sletter ansøgninger som din. - Skriv gerne navnet på skolen, dette er dog ikke et krav, men det letter vores process lidt.<?= "\n\n"; ?>Hvis årsagen til "problemet" er, at der er to familie medlemmer i samme hjem, der ønsker at spille på net-hesten, så send en mail til admin@net-hesten.dk og sig at det skyldes, at din søster/bror ønsker at bliver oprettet, ansøg herefter igen :-)<?= "\n\n"; ?>I begge tilfælde er det vigtigt, at du husker, at det er imod spillets regler, at samarbejde helt vildt og f.eks. sende store mænger af heste til sine venner.<?= "\n\n"; ?>Hvis i bryder reglerne, og snyder, kan det føre til at et eller begge stutterier bliver blokeret.<?= "\n\n"; ?>Med venlig hilsen<?= "\n"; ?>Net-hesten</textarea>
		<?php } ?>
	</section>
<?php } ?>
<section>
	<h1>Ansøgere</h1>
	<a class="btn btn-info" href="/admin/">Tilbage</a>
	<style>
		.applicant_grid {
			display: grid;
			grid-template-columns: repeat(3, 1fr) 160px 75px 75px;
			grid-gap: 2px;
		}

		.grid_item {
			padding: 0 5px;
			line-height: 36px;
			border-bottom: 1px #000 solid;
			text-align: center;
		}

		.grid_item {
			padding: 5px;
		}

		.grid_item.verified {
			color: darkgoldenrod;
		}

		.grid_item.unverified {
			color: darkred;
		}

		.grid_header_wrapper {
			display: contents;
		}

		.grid_header_wrapper>.grid_item {
			background: rgba(255, 255, 255, 0.1);
		}
	</style>
	<div class="applicant_grid">
		<div class="grid_header_wrapper">
			<div class="grid_item">Stutteri navn</div>
			<div class="grid_item">Email</div>
			<div class="grid_item">Besked:</div>
			<div class="grid_item">Dato:</div>
			<div class="grid_item"></div>
			<div class="grid_item"></div>
		</div>
		<?php
		$result = $link_new->query("SELECT id, username, email, message, date, verify_date FROM user_application ORDER BY verify_date, date DESC");
		if ($result) {
			while ($data = $result->fetch_object()) {
		?>
				<div class="grid_item"><?= $data->username; ?></div>
				<div class="grid_item"><?= $data->email; ?></div>
				<div class="grid_item"><?= $data->message; ?></div>
				<div class="grid_item <?= ($data->verify_date != null ? 'verified' : 'unverified'); ?>"><?= ($data->verify_date != null ? $data->verify_date : $data->date); ?></div>
				<div class="grid_item"><a href="?accept_application=<?= $data->id; ?>">Opret</a></div>
				<div class="grid_item"><a href="?delete_application=<?= $data->id; ?>&applicant_name=<?= $data->username; ?>&status='<?= ($data->verify_date != null ? 'verified' : 'unverified'); ?>'">Slet</a></div>
		<?php
			}
		}
		?>
	</div>
</section>
<?php
require "{$basepath}/global_modules/footer.php";
