<?php
$basepath = '../../../..';
$title = 'Donationer';
require "$basepath/app_core/object_loader.php";
require "$basepath/global_modules/header.php";
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
	}
	.amount {
		text-align: right;
		width: 100px;
		display: inline-block;
	}
	li {
		line-height: 1.2;
	}
	.support_main_content:after {
		content:"";
		display: block;
		clear:both;
	}
	.support_main_content > div > a {
		display: inline-block;
		margin-bottom: 20px;
	}
</style>
<header><h1>Nethesten støtteside</h1>
	<p>
		Det vil altid være gratis, at spille net-hesten, men vi vil til hver en tid sætte pris på enhver støtte, til spillets udvikling og drift.
	</p>
	<p>
		Det er lige for tiden, ikke muligt at købe wkr for rigtige penge, men ønsker du stadig at støtte siden, kan du sende en privat donation.
	</p>
	<p>
		Bemærk at det er 100% frivilligt og aldrig vil blive et krav, omvendt giver donationer dig ingen ekstra rettigheder på nuværende tidspunkt.
	</p>
</header>
<div class="support_main_content" style="padding: 0 0 30px;">
	<p>Støt via Chris' (TækHestens paypal): <a style="text-decoration: underline;" href="http://paypal.me/ModalMorphling">Link</a></p>
</div>
<div style="padding: 0 0 30px;">
	<p>Grunden til at vi har valgt donationslinks fremfor wkr shop, handler om at der slet ikke kommer nok ind den her vej.</p>
	<p>En "betalingsløsning" koster 100kr/md som var rent underskud.</p>
	<p>Den gamle paypal model der var "gratis", droppede jeg fordi der gik ca. 40% af jeres penge til paypal i gebyrer</p>
	<p>Det syntes vi ikke gav nogen mening, hvis I tænkte I ville støtte spillet og ikke paypal :-)</p>
	<p>Endelig tæller en donation som en privat gave, hvilket kræver meget mindre regnskab og jura end en shop løsning.</p>
</div>
<div>
	<p>Skulle nogen være interesseret i, at give en "størrer" økonomisk støtte til spillets drift og udvikling.</p>
	<p>Evt målrettet noget konkret. Så send os en mail på <a style="text-decoration: underline;" href="mailto:admin@net-hesten.dk">admin@net-hesten.dk</a>. Så finder vi us af detaljerne.</p>
</div>
<?php
require_once ("{$basepath}/global_modules/footer.php");
