<?php
/* REVIEW: SQL Queries */
$basepath = '../../../..';
$title = 'Donationer';
require "{$basepath}/app_core/object_loader.php";
require "{$basepath}/global_modules/header.php";
?>
<style>
	.last {
		text-align: center;
		width: 125px;
		display: inline-block;
	}
	.user {
		color:#333;
		width: 250px;
		display: inline-block;
		/**/
	}
	.amount {
		text-align: right;
		width: 100px;
		display: inline-block;
	}
	li {
		line-height: 1.2;
	}
</style>
<header><h1>Nethesten støtteside</h1></header>
<p style="float:left;height: 100%;width: 400px;padding-right:20px;">
	Her kan du købe wkr for rigtige penge, for at støtte net-hesten udvikling og drift.<br /><br/>
	Det vil altid være gratis, at spille net-hesten, men vi vil til hver en tid sætte pris på enhver støtte, til spillets udvikling og drift.
</p>
<ul>
	<li>
		<span class="user" style="text-align: left;font-weight: bold;">
			Stutterinavn
		</span>
		<span class="amount" style="text-align: left;font-weight: bold;">
			<!--Samlet-->
		</span>
		<span class="last" style="text-align: center;font-weight: bold;">
			Sidst støttet
		</span>
		<!--<span class="last" style="text-align: left;font-weight: bold;">
			Sidst online
		</span>-->
	</li>
	<?php
//	Så "skjul mit navn" / "skjul mit beløb men behold plads på listen" / "skjul navn og beløb"
	$donors = $link_new->query("SELECT user.id AS id, user.stutteri AS user, sum(payment.price) AS amount, MAX(payment.date) AS last_date FROM `{$GLOBALS['DB_NAME_OLD']}`.PaypalPayment AS payment LEFT JOIN `{$GLOBALS['DB_NAME_OLD']}`.Brugere AS user ON user.id = payment.stud_id WHERE payment.date > '2015-08-01 00:00:00' GROUP BY payment.stud_id ORDER BY amount DESC, last_date DESC");
	$donors_formatted = [];
	while ($donor = $donors->fetch_object()) {
//		$last_active = $link_new->query("SELECT end FROM netchw_db1.user_data_sessions WHERE user_id = {$donor->id} ORDER BY end LIMIT 1")->fetch_object()->end;
		?>
		<li>
			<span class="user">
				<?php // $donor->user; ?>
				<?= 'Anonym bruger'; ?> 
			</span>
			<span class="amount">
				<?php // $donor->amount; ?> <!--kr-->
				<?= 'skjult beløb'; ?> 
			</span>
			<span class="last">
				<?= substr($donor->last_date, 0, 10); ?> 
			</span>
			<!--<span class="last">
			<?php /* substr($last_active, 0, 10); */ ?>
			</span>-->
		</li>
		<?php
	}
	?>
</ul>
<?php
require_once ("{$basepath}/global_modules/footer.php");
