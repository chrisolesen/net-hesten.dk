<?php
/* Mit Stutteri */

$basepath = '../../../..';
$title = 'visit';
require "$basepath/app_core/object_loader.php";
require "$basepath/global_modules/header.php";

$visit_id = filter_input(INPUT_GET, 'user');

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

$horse_tabs = [];

$attr = ['user_name' => $visit_user_info->username, 'mode' => 'visiting_one_stud'];
$attr = ['user_name' => $visit_user_info->username, 'mode' => 'visiting_one_stud'];

if ($filter_id = filter_input(INPUT_POST, 'id_filter')) {

	$attr = ['user_name' => $visit_user_info->username, 'id_filter' => $filter_id, 'mode' => 'visiting_one_stud'];
	$attr = ['user_name' => $visit_user_info->username, 'id_filter' => $filter_id, 'mode' => 'visiting_one_stud'];
}


$attr['custom_filter'] .= horse_list_filters::get_filter_string(['zone' => "visit_{$active_tab}"]);
$offset = $your_horses_page * $horses_pr_page;
$target_page = ($your_horses_page * $horses_pr_page) + $horses_pr_page;
$attr['limit'] = 100;
$attr['offset'] = $offset;
$i = 0;

foreach (horses::get_all($attr) as $horse) {

	$horse_is_at_competition = false;
	if ($horse['competition_id']) {
		$horse_is_at_competition = true;
	}
	$gender = ((string) strtolower($horse['gender']) === 'hoppe') ? 'female' : '';
	$gender = ((string) strtolower($horse['gender']) === 'hingst') ? 'male' : $gender;
	$gender = ((string) $gender === '') ? 'error' : $gender;

	$extended_info = [
		'name' => $horse['name'],
		'age' => $horse['age'],
		'gender' => $horse['gender'],
		'race' => $horse['race'],
		'artist' => $horse['artist'],
		'id' => $horse['id'],
		'value' => $horse['value'],
		'owner_name' => $horse['owner_name'],
		'talent' => $horse['talent'],
		'ulempe' => $horse['ulempe'],
		'egenskab' => $horse['egenskab'],
		'type' => ($horse['unik'] == 'ja' ? 'unique' : ($horse['original'] == 'ja' ? 'original' : 'normal')),
		'gold_medal' => (empty($horse['gold_medal']) ? 0 : $horse['gold_medal']),
		'silver_medal' => (empty($horse['silver_medal']) ? 0 : $horse['silver_medal']),
		'bronze_medal' => (empty($horse['bronze_medal']) ? 0 : $horse['bronze_medal']),
		'junior_medal' => (empty($horse['junior_medal']) ? 0 : $horse['junior_medal']),
	];
	$extended_info = json_encode($extended_info);

	$horse_data = '';
	$horse_data .= "<div class='horse_square horse_object {$gender}' data-horse-id='{$horse['id']}' data-extended-info='{$extended_info}'>";
	$horse_data .= "<div class='info'>";
	$horse_data .= "<span class='name'>{$horse['name']}</span>";
	$horse_data .= "<i class='gender icon-{$gender}-1'></i>";
	$horse_data .= "<div class='horse_vcard'>";
	$horse_data .= "<i class='icon-vcard'></i> ";
	$horse_data .= "<div class='extended_info'>";
	$horse_data .= "<span class='type_age'>";
	$horse_data .= ($horse['unik'] == 'ja' ? '<span class="unique">Unik</span> ' : ($horse['original'] == 'ja' ? '<span class="original">Original</span> ' : ''));
	$horse_data .= "{$horse['race']} {$horse['age']} år</span><br />";
	$horse_data .= "<span class='horse_id'>ID: {$horse['id']}</span><br /><br />";
	$horse_data .= "<span class='ability'>Egenskab: {$horse['egenskab']}</span><br />";
	$horse_data .= "<span class='disability'>Ulempe: {$horse['ulempe']}</span><br />";
	$horse_data .= "<span class='talent'>Talent: {$horse['talent']}</span><br /><br />";
	$horse_data .= "<span class='artist'>Tegner: {$horse['artist']}</span>";
	$horse_data .= "<span class='value'>Værdi: " . number_dotter($horse['value']) . ' <span class="wkr_symbol">wkr</span></span>';
	$horse_data .= "</div>";
	$horse_data .= "</div>";

	if ($horse['breed_date']) {
		$breed_date_target = new DateTime($horse['breed_date']);
		$breed_date_target->add(new DateInterval('P40D'));
		$horse_data .= "<button style='pointer-events: none;' class='enter_graes btn compact_top_button'>Foler ca. {$breed_date_target->format('Y-m-d')}</button>";
	}

	$horse_data .= "<button data-button-type='modal_activator' data-target='unprovoked_bid' class='enter_graes btn btn-info compact_bottom_button'>Byd på hesten</button>";

	$horse_data .= "</div>";
	$horse_data .= "<img src='//files." . HTTP_HOST . "/{$horse['thumb']}' data-button-type='modal_activator' data-target='horze_extended_info' />";
	$horse_data .= "<img style='display: none;' class='zoom_img' src='//files." . HTTP_HOST . "/{$horse['thumb']}' />";
	$horse_data .= "</div>";

	$horse_tabs['idle_horses'][] = $horse_data;
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
		<span class="label">Antal heste:</span><?= number_dotter($link_new->query("SELECT count(id) AS amount FROM {$GLOBALS['DB_NAME_OLD']}.Heste WHERE bruger = '{$visit_user_info->username}' AND status <> 'død'")->fetch_object()->amount); ?><br />
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
	<style>

	</style>
	<div class="shadow"></div>
	<div class="content">
		<?= horse_list_filters::render_filter_settings(['zone' => "visit_{$active_tab}"]); ?>
	</div>
</div>
<?php
$modals[] = ob_get_contents();

ob_end_clean();

/* Define modal - end */
?>
<?php
require_once("{$basepath}/global_modules/footer.php");
