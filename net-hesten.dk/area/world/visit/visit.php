<?php
/* REVIEW: SQL Queries */
/* Mit Stutteri */

$basepath = '../../../..';
$title = 'visit';
require "$basepath/app_core/object_loader.php";
require "$basepath/global_modules/header.php";

$visit_id = (int) filter_input(INPUT_GET, 'user');

if (!$visit_id) {
	header('Location: /area/world/visit/');
	exit();
}

$your_horses_page = max(0, (int) filter_input(INPUT_GET, 'your_horses_page'));
$horses_pr_page = 10;

$dead = 'død';
$visit_user_info = user::get_info(['user_id' => $visit_id, 'link_mode' => 'new']);

if (!$visit_user_info) {
	header('Location: /area/world/visit/');
	exit();
}

$horse_array = [];

$attr = ['user_name' => $visit_user_info->username, 'mode' => 'visiting_one_stud'];

if ($filter_id = filter_input(INPUT_POST, 'id_filter')) {

	$attr = ['user_name' => $visit_user_info->username, 'id_filter' => $filter_id, 'mode' => 'visiting_one_stud'];
}


$attr['custom_filter'] = horse_list_filters::get_filter_string(['zone' => "visit"]);
$offset = $your_horses_page * $horses_pr_page;
$target_page = ($your_horses_page * $horses_pr_page) + $horses_pr_page;
$attr['limit'] = 100;
$attr['offset'] = $offset;
$i = 0;

foreach (horses::get_all($attr) as $horse) {

	$horse_array[] = render_horse_object($horse, 'visit_user');
}
?>
<style>
	.tabs {
		margin-top: 1em;
	}

	.foel {
		position: absolute;
		bottom: 45px;
		right: 12px;
	}

	.enter_graes {
		position: absolute;
		bottom: 12px;
		right: 12px;
	}

	.horse_square+.horse_square+.horse_square+.horse_square+.horse_square+.horse_square+.horse_square+.horse_square+.horse_square+.horse_square+.horse_square {
		display: none;
	}
</style>
<section>
	<div style="float:left;width: 650px;">
		<div class="image_block" style="height:130px;float:left;overflow: hidden;">
			<?php
			if ($visit_user_info->thumb) {
				$stud_thumbnail = "//files." . HTTP_HOST . "/users/{$visit_user_info->thumb}";
			} else {
				$stud_thumbnail = "//files." . HTTP_HOST . "/graphics/logo/default_logo.png";
			}
			?>
			<img style="float:left;margin-right: 2em;border-radius: 5px;max-width: 325px;max-height: 130px;" src="<?= $stud_thumbnail; ?>" />
		</div>
		<span class="label">Stutterinavn:</span><span id="visited_name"><?= $visit_user_info->username; ?></span><br />
		<span class="label">Navn:</span><?= $visit_user_info->name; ?><br />
		<span class="label">Antal heste:</span><?= number_dotter($link_new->query("SELECT count(id) AS amount FROM `{$GLOBALS['DB_NAME_OLD']}`.Heste WHERE bruger = '{$visit_user_info->username}' AND status <> 'død'")->fetch_object()->amount); ?><br />
		<span class="label">Penge:</span><span class="user_money_pool"><?= number_dotter($visit_user_info->money); ?></span> <span class="wkr_symbol dev_test_effect">wkr</span><br />
		<br />
	</div>
</section>

<section class="tabs">
	<?php require 'horze_list.php'; ?>
</section>
<?php
/* Define modal - start */
ob_start();
?>
<style>
	.fifty_p {
		width: 50%;
		float: left;
		line-height: 25px;
		margin-bottom: 5px;
	}
</style>
<div id="filter_horses" class="modal">
	<script>
		function filter_horses(caller) {

		}
	</script>
	<div class="shadow"></div>
	<div class="content">
		<?= horse_list_filters::render_filter_settings(['zone' => "visit"]); ?>
	</div>
</div>
<?php
$modals[] = ob_get_contents();

ob_end_clean();

/* Define modal - end */
require_once("{$basepath}/global_modules/footer.php");
