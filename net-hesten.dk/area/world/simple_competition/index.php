<?php

$basepath = '../../../..';
$title = 'Lodtrækning';
require "{$basepath}/app_core/object_loader.php";
require "{$basepath}/global_modules/header.php";
$competitions = $link_new->query("SELECT * FROM `{$GLOBALS['DB_NAME_NEW']}`.`game_data_simple_competition` WHERE `startdate` < NOW() ORDER BY `startdate` DESC LIMIT 2");
if (filter_input(INPUT_GET, 'partake') == true) {
	$part_id = (int) filter_input(INPUT_GET, 'comp_id');
	if ($part_id) {
		$link_new->query("INSERT INTO `{$GLOBALS['DB_NAME_NEW']}`.`game_data_simple_competition_participants` (`competition_id`, `participant_id`) 
		VALUES ({$part_id}, {$_SESSION['user_id']})");
	}
}
?>
<style>
	.competition_data>div {
		line-height: 1.5;
	}

	.competition_data>div span {
		font-weight: bold;
	}

	.drawing {
		display: block;
		clear: both;
	}

	.drawing:after {
		display: block;
		content: "";
		clear: both;
	}

	.drawing+.drawing {
		margin-top: 2em;
	}
</style>
<section class="tabs">
	<div class="grid">
		<div data-section-type="info_square">
			<header>
				<h1>Lodtrækninger</h1>
			</header>
		</div>
		<p>Tilmeldinger lukker klokken 20 og starter klokken 5</p>
		<?php while ($competion_data = $competitions->fetch_object()) {
			$competion_subdata = (object) json_decode($competion_data->data);
		?>
			<div class="drawing">
				<img style="float:left;margin-right:20px;" src="//files.<?= filter_input(INPUT_SERVER, 'HTTP_HOST'); ?>/imgHorse/<?= $link_new->query("SELECT image FROM `{$GLOBALS['DB_NAME_NEW']}`.horse_types WHERE id = {$competion_subdata->prize_id}")->fetch_object()->image; ?>" />
				<div style="float:left;" class="competition_data">
					<h2><?= $competion_data->competition_name; ?></h2>
					<div><span>Race:</span> <?= $link_new->query("SELECT `race` FROM `{$GLOBALS['DB_NAME_NEW']}`.`horse_types` 
					WHERE `id` = {$competion_subdata->prize_id}")->fetch_object()->race; ?></div>
					<div><span>Køn:</span> <?= ($competion_subdata->gender == 1 ? 'Tilfældig' : ($competion_subdata->gender == 2 ? 'Hingst' : 'Hoppe')); ?></div>
					<?php if ($competion_data->winner <> 0) {
						$competition_winner = $link_new->query("SELECT `stutteri` AS `username` 
						FROM `{$GLOBALS['DB_NAME_OLD']}`.`Brugere` WHERE `id` = {$competion_data->winner}")->fetch_object();
					?>
						<div><span>Vinder:</span> <a href="/area/world/visit/visit.php?user=<?= $competion_data->winner; ?>"><?= $competition_winner->username; ?></a></div>
					<?php } ?>
					<?php if ($competion_data->enddate > ((new DateTime('NOW'))->format('Y-m-d H:i:s')) && $competion_data->status_code == 42) { ?>
						<?php
						$partaking = false;
						$partaking = $link_new->query("SELECT * FROM `{$GLOBALS['DB_NAME_NEW']}`.`game_data_simple_competition_participants` 
						WHERE `competition_id` = {$competion_data->id} AND `participant_id` = {$_SESSION['user_id']}");
						if (!$partaking->fetch_object()) { ?>
							<a style="margin-top:0.5em;" class="btn btn-success" href="?partake=true&comp_id=<?= $competion_data->id; ?>">Deltag</a>
						<?php } else { ?>
							<a style="margin-top:0.5em;" class="btn btn-info">Du er med</a>
						<?php } ?>
					<?php } ?>
				</div>
			</div>
		<?php } ?>
	</div>
</section>
<?php
require_once("{$basepath}/global_modules/footer.php");
