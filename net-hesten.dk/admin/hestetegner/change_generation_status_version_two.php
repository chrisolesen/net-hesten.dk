<?php
$basepath = '../../..';
$responsive = true;
require "$basepath/app_core/object_loader.php";
require "$basepath/global_modules/header.php";

if (!(is_array($_SESSION['rights']) && in_array('global_admin', $_SESSION['rights']))) {
	ob_end_clean();
	header('Location: /');
}
$Foelbox = 'Følkassen';
$Foel = 'Føl';
$selected_race = substr($_GET['race'], 1, -1);
?>
<a href="/admin/hestetegner/admin_types_version_two.php">Tilbage</a><br /><br /> 
<section>
    <header>
        <h1>Genererings admin - <?= $selected_race; ?></h1>
	</header>
	<style>
        ul {
            border:1px solid black;
            padding:10px;
            display: block;
        }
        ul li {
            display: inline-block;
        }
        .heading span {
            border-bottom: 3px double black;
            text-align: center;
        }
        .monospace {
            font-family: monospace;
        }
        .center_text {
            text-align: center;
        }
		.col {
			float:left;
			width: 50%;
		}
		li {
			line-height: 1.3;
			height: 280px;
			width: 200px;
			border: 3px grey double;
			display: inline-block;
			position: relative;
			overflow: hidden;
			margin: 5px;
			padding:5px;
		}
		img {
			max-height: 100%;
			max-width: 100%;
			position: relative;
		}
		.unique, 
		.rebirth,
		.generation {
			border:3px grey double;
		}
		.generation {
			border-left-color: blue;
			border-bottom-color:blue;
		}
		.rebirth {
			border-top-color:blue;
			border-right-color:blue;
		}
		.unique {
			border-left-color: gold !important;
			border-bottom-color:gold !important;
		}
		.generate_one {
			position: absolute;
			bottom:35px;
			right:5px;
			z-index: 2;
		}
		.alter_type {
			position: absolute;
			bottom:35px;
			left:5px;
			z-index: 2;
		}
		.alter_gen {
			position: absolute;
			bottom:5px;
			right:5px;
			z-index: 2;
		}
		.unique_ness {
			position: absolute;
			bottom:5px;
			left:5px;
			z-index: 2;
		}
		.gender {
			position:absolute;
			top:5px;
			right:5px;
			z-index:2;
		}
		.race {
			position: absolute;
			bottom:65px;
			left:5px;
			z-index: 2;
		}
		.archive {
			position: absolute;
			bottom:65px;
			right:5px;
			z-index: 2;
		}
		/*a.btn {
			font-family:'Merienda One', cursive;
		}*/
    </style>

	<ul>
		<?php
	if (isset($_GET['do'])) {
		$thumb = filter_input(INPUT_GET, 'thumb');
			/*
			  19	type_unique
			  20	type_generation
			  21	type_rebirth
			  22	type_rebirth_generation
			  23	type_rebirth_unique
			  24	type_ordinary
			  25	type_foel
			  26	type_foel_rebirth
		 */
		//	&do=<?= (in_array($data->status, [21, 22, 23]) ? 'deaktivate' : 'activate')

	}
	$target_status = false;
	if (filter_input(INPUT_GET, 'do') === 'deaktivate') {
		if (filter_input(INPUT_GET, 'type') === 'foel') {
			$target_status = 25;
		}
		if (filter_input(INPUT_GET, 'type') === 'adult') {
			$previus_status = $link_new->query("SELECT status FROM {$_GLOBALS['DB_NAME_NEW']}.horse_types WHERE image = '{$thumb}' LIMIT 1")->fetch_object()->status;
			if (in_array($previus_status, [19, 23])) {
				$target_status = 19;
			} else {
				$target_status = 24;
			}
		}
	}
	if (filter_input(INPUT_GET, 'do') === 'activate') {
		if (filter_input(INPUT_GET, 'type') === 'foel') {
			$target_status = 26;
		}
		if (filter_input(INPUT_GET, 'type') === 'adult') {
			$previus_status = $link_new->query("SELECT status FROM {$_GLOBALS['DB_NAME_NEW']}.horse_types WHERE image = '{$thumb}' LIMIT 1")->fetch_object()->status;
			if (in_array($previus_status, [19, 23])) {
				$target_status = 23;
			} else {
				$target_status = 21;
			}
		}
	}
	if (filter_input(INPUT_GET, 'do') === 'deaktivate') {
		if (filter_input(INPUT_GET, 'type') === 'foel') {
			$target_status = 25;
		}
		if (filter_input(INPUT_GET, 'type') === 'adult') {
			$previus_status = $link_new->query("SELECT status FROM {$_GLOBALS['DB_NAME_NEW']}.horse_types WHERE image = '{$thumb}' LIMIT 1")->fetch_object()->status;
			if (in_array($previus_status, [19, 23])) {
				$target_status = 19;
			} else {
				$target_status = 24;
			}
		}
	}
	if (filter_input(INPUT_GET, 'do') === 'make_normal') {
		if (filter_input(INPUT_GET, 'type') === 'foel') {
			die('føl kan ikke være unikke');
		}
		if (filter_input(INPUT_GET, 'type') === 'adult') {
			$target_status = 24;
		}
	}
	if (filter_input(INPUT_GET, 'do') === 'make_unique') {
		if (filter_input(INPUT_GET, 'type') === 'foel') {
			die('føl kan ikke være unikke');
		}
		if (filter_input(INPUT_GET, 'type') === 'adult') {
			$target_status = 19;
		}
	}

	if (filter_input(INPUT_GET, 'do') === 'make_foel') {
		$target_status = 26;
	}
	if (filter_input(INPUT_GET, 'do') === 'make_adult') {
		$target_status = 24;
	}

	if (in_array(filter_input(INPUT_GET, 'do'), ['deaktivate', 'activate', 'make_unique', 'make_normal','make_adult','make_foel'])
		&&
		in_array(filter_input(INPUT_GET, 'type'), ['foel', 'adult'])
		&& $target_status) {
		$result = $link_new->query("UPDATE {$_GLOBALS['DB_NAME_NEW']}.horse_types SET status = {$target_status} WHERE image = '{$thumb}' LIMIT 1");
	}

	if (filter_input(INPUT_GET, 'do') === 'archive') {
		$result = $link_new->query("UPDATE {$_GLOBALS['DB_NAME_NEW']}.horse_types SET archived = 1 WHERE image = '{$thumb}' LIMIT 1");
	}
/*
	if (filter_input(INPUT_GET, 'do') === 'switch_gender') {
		$gender = (int) $link_new->query("SELECT allowed_gender FROM {$_GLOBALS['DB_NAME_NEW']}.horse_types WHERE image = '{$thumb}' LIMIT 1")->fetch_object()->allowed_gender;
		
		if($gender == 1){
			$new_gender = 2;
		} else if ($gender == 2){
			$new_gender = 3;
		} else {
			$new_gender = 1;
		}
		$result = $link_new->query("UPDATE {$_GLOBALS['DB_NAME_NEW']}.horse_types SET allowed_gender = {$new_gender} WHERE image = '{$thumb}' LIMIT 1");
	}*/
	if (filter_input(INPUT_GET, 'do') === 'make_mars') {
		$result = $link_new->query("UPDATE {$_GLOBALS['DB_NAME_NEW']}.horse_types SET allowed_gender = 2 WHERE image = '{$thumb}' LIMIT 1");
	}
	if (filter_input(INPUT_GET, 'do') === 'make_venus') {
		$result = $link_new->query("UPDATE {$_GLOBALS['DB_NAME_NEW']}.horse_types SET allowed_gender = 3 WHERE image = '{$thumb}' LIMIT 1");
	}
	if (filter_input(INPUT_GET, 'do') === 'make_trans') {
		$result = $link_new->query("UPDATE {$_GLOBALS['DB_NAME_NEW']}.horse_types SET allowed_gender = 1 WHERE image = '{$thumb}' LIMIT 1");
	}


	if (filter_input(INPUT_GET, 'do') === 'generate_one') {
		if ($selected_id = filter_input(INPUT_GET, 'id')) {
			$thumb_data = $link_new->query("SELECT artists, image, race FROM {$_GLOBALS['DB_NAME_NEW']}.horse_types WHERE id = {$selected_id} LIMIT 1")->fetch_object();
			$artist = $link_new->query("SELECT stutteri FROM {$_GLOBALS['DB_NAME_OLD']}.Brugere WHERE id = {$thumb_data->artists} LIMIT 1")->fetch_object()->stutteri;
			if (!$artist) {
				exit('Kun en tegner lige nu tak.'); 
			}
			$thumb = '//files.'.HTTP_HOST.'/imgHorse/' . $thumb_data->image;
			$race = $thumb_data->race; 

			$advantage = $link_new->query("SELECT egenskab FROM {$_GLOBALS['DB_NAME_OLD']}.horse_habits WHERE egenskab <> '' ORDER BY RAND() LIMIT 1")->fetch_object()->egenskab;
			$disadvantage = $link_new->query("SELECT ulempe FROM {$_GLOBALS['DB_NAME_OLD']}.horse_habits WHERE ulempe <> '' ORDER BY RAND() LIMIT 1")->fetch_object()->ulempe;
			$talent = $link_new->query("SELECT talent FROM {$_GLOBALS['DB_NAME_OLD']}.horse_habits WHERE talent <> '' ORDER BY RAND() LIMIT 1")->fetch_object()->talent;

			$height_data = $link_new->query("SELECT max_height, min_height FROM {$_GLOBALS['DB_NAME_NEW']}.horse_races WHERE name = '{$race}' LIMIT 1")->fetch_object();
			$height_lower = $height_data->lower;
			$height_upper = $height_data->upper;
			$height = mt_rand($height_lower, $height_upper);

			$gender = (mt_rand(1, 2) === 1 ? 'Hingst' : 'Hoppe');
			require_once "{$basepath}/app_core/cron_files/data_collections/generation_horse_names.php";
			shuffle($boys_names);
			shuffle($girls_names);
			if ($gender === 'Hingst') {
				$name = $boys_names[0];
			} else {
				$name = $girls_names[0];
			}
			$name = $name;

			date_default_timezone_set('Europe/Copenhagen');
			$current_date = new DateTime('now');
			$date_now = $current_date->format('Y-m-d');
			$time_now = $current_date->format('H:i:s');

			$statuschangedate = '00-00-00 00:00:00';
			$date_now_db_format = $current_date->format('Y-m-d H:i:s');
			if (filter_input(INPUT_GET, 'type') === 'foel') {
				$generation_age = 0;
				$horse_birth_status = 'Føl';
			} else {
				$generation_age = 4;
				$horse_birth_status = 'Hest';
			}
			$horse_age_to_irl_days = $generation_age * 40;
			$current_date = new DateTime('now');
			$target_date = $current_date->sub(new DateInterval("P{$horse_age_to_irl_days}D"))->format('Y-m-d H:i:s');



			if ($artist && $thumb && $advantage && $disadvantage && $talent) {
				$sql = "INSERT INTO {$_GLOBALS['DB_NAME_OLD']}.Heste " . PHP_EOL
					. '(' . PHP_EOL
					. 'bruger, status, alder, pris, beskrivelse, ' . PHP_EOL
					. 'foersteplads, andenplads, tredieplads, ' . PHP_EOL
					. 'statuschangedate, date, changedate, status_skift, alder_skift, ' . PHP_EOL
					. 'navn, kon, ' . PHP_EOL
					. 'race, tegner, thumb, height, egenskab, ulempe, talent, ' . PHP_EOL
					. 'farid, morid, random_height' . PHP_EOL
					. ')' . PHP_EOL
					. ' VALUES ' . PHP_EOL
					. '(' . PHP_EOL
					. "'net-hesten', '{$horse_birth_status}', $generation_age, 15000, '', " . PHP_EOL
					. '0, 0, 0, ' . PHP_EOL
					. "'00-00-00 00:00:00', '{$target_date}','{$target_date}', NOW(), NOW(), " . PHP_EOL
					. "'{$name}', '{$gender}', " . PHP_EOL
					. "'{$race}', ' {$artist}', '{$thumb}', {$height}, '{$advantage}', '{$disadvantage}', '{$talent}', " . PHP_EOL
					. "'', '', 'nej'" . PHP_EOL
					. ")";
//					echo $sql;
//					exit('test');
				$link_new->query($sql);
				?>
					<a href="/admin/hestetegner/edit_horse.php?horse_id=<?= mysqli_insert_id($link_old); ?>"><?= mysqli_insert_id($link_old); ?></a>
					<?php
				$error = $link_old->error;


				$tech_mail_message .= "Artist/Thumb: {$artist} // {$thumb}" . PHP_EOL;
				$tech_mail_message .= "Race/kon: {$race}/{$gender}" . PHP_EOL;
				$tech_mail_message .= "Egenskab/Ulempe/Talent: {$advantage}/{$disadvantage}/{$talent}" . PHP_EOL;
				$tech_mail_message .= "Navn: {$name}" . PHP_EOL;
				$tech_mail_message .= "" . PHP_EOL;
				$tech_mail_message .= "" . PHP_EOL;
				++$generated_horses;
				mail('tech@net-hesten.dk', 'generation test', $tech_mail_message);
			}
		}
	}

	$sql = "SELECT image, status, id, allowed_gender, archived FROM {$_GLOBALS['DB_NAME_NEW']}.horse_types WHERE race = '{$selected_race}' ORDER BY date DESC";
	$result = $link_new->query($sql);
	$races = '';
	$latin_dead = 'død';
	while ($data = $result->fetch_object()) {
		if (filter_input(INPUT_GET, 'type') == 'adult') {
			if (in_array($data->status, [25, 26])) {
				continue;
			}
		} elseif (filter_input(INPUT_GET, 'type') == 'foel') {
			if (!in_array($data->status, [25, 26])) {
				continue;
			}
		}
		if($data->archived){continue;}
		if(in_array($data->status, [19, 23])){/* Unique */ $amount = $link_new->query("SELECT count(id) AS amount FROM {$_GLOBALS['DB_NAME_OLD']}.Heste WHERE thumb LIKE '%{$data->image}%' AND status <> 'død'")->fetch_object()->amount;}
		/*
		gender 1: all
		2: male
		3: female 
		*/
		$genders = $data->allowed_gender;
			/*
			  19	type_unique
			  20	type_generation
			  21	type_rebirth
			  22	type_rebirth_generation
			  23	type_rebirth_unique
			  24	type_ordinary
			  25	type_foel
			  26	type_foel_rebirth

			  20,21,22,24
		 */
		?>
			<li title="Type ID: <?= $data->id; ?>" data-horse-thumb="<?= $data->image; ?>" class="<?= in_array($data->status, [21, 22, 23, 26]) ? 'rebirth' : ''; ?> <?php /*= in_array($data->status, [20, 22, 26]) ? 'generation' : ''; */ ?> <?= in_array($data->status, [19, 23]) ? 'unique' : ''; ?>">
				<img src="https://files.<?= filter_input(INPUT_SERVER,'HTTP_HOST');?>/imgHorse/<?= $data->image; ?>" />
				<?php if( !in_array($data->status, [19, 23]) || (in_array($data->status, [19, 23]) && $amount == 0)){ ?>
				<a class="generate_one btn btn-info" href="/admin/hestetegner/change_generation_status_version_two.php?race='<?= $selected_race; ?>'&do=generate_one&id=<?= $data->id; ?>&type=<?= $_GET['type']; ?>">Lav én</a>
				<?php } ?>
				<a class="alter_gen btn <?= in_array($data->status, [21, 22, 23, 26]) ? 'btn-danger' : 'btn-success'; ?>" href="/admin/hestetegner/change_generation_status_version_two.php?race='<?= $selected_race; ?>'&do=<?= (in_array($data->status, [21, 22, 23, 26]) ? 'deaktivate' : 'activate'); ?>&thumb=<?= $data->image; ?>&type=<?= $_GET['type']; ?>"><?= in_array($data->status, [21, 22, 23, 26]) ? 'Genfød ikke' : 'Genfød'; ?></a>
				<?php if (filter_input(INPUT_GET, 'type') !== 'foel') { ?>
				<a class="unique_ness btn <?= in_array($data->status, [20, 21, 22, 24]) ? 'btn-success' : 'btn-danger'; ?>" href="/admin/hestetegner/change_generation_status_version_two.php?race='<?= $selected_race; ?>'&do=<?= (in_array($data->status, [20, 21, 22, 24]) ? 'make_unique' : 'make_normal'); ?>&thumb=<?= $data->image; ?>&type=<?= $_GET['type']; ?>"><?= in_array($data->status, [20, 21, 22, 24]) ? 'Unik' : 'Normal'; ?></a>
				<?php 
		} ?>
		<!-- TO DO: finish -->
		<a class="race btn btn-info" style="pointer-events:none;">ID: <?= $data->id; ?></a>
		<a class="archive btn btn-danger" href="/admin/hestetegner/change_generation_status_version_two.php?race='<?= $selected_race; ?>'&do=archive&thumb=<?= $data->image; ?>&type=<?= $_GET['type']; ?>">Arkiver</a>
		<a class="alter_type btn btn-info" href="/admin/hestetegner/change_generation_status_version_two.php?race='<?= $selected_race; ?>'&do=<?= (in_array($data->status, [25, 26]) ? 'make_adult' : 'make_foel'); ?>&thumb=<?= $data->image; ?>&type=<?= $_GET['type']; ?>"><?= in_array($data->status, [25, 26]) ? 'Bliv Hest' : 'Bliv Føl'; ?></a>
			<div class="gender" style="overflow:hidden;height:32px;" onclick="slide_gender_toggle(this);">
			<?php if($genders == 2) { ?>
				<i class="fa fa-mars fa-2x" style="color:#0f66b4; display:block;"></i></a>
				<a href="/admin/hestetegner/change_generation_status_version_two.php?race='<?= $selected_race; ?>'&do=make_venus&thumb=<?= $data->image; ?>&type=<?= $_GET['type']; ?>"><i class="fa fa-venus fa-2x" style="color:#bd362f; display:block;"></i></a>
				<a href="/admin/hestetegner/change_generation_status_version_two.php?race='<?= $selected_race; ?>'&do=make_trans&thumb=<?= $data->image; ?>&type=<?= $_GET['type']; ?>"><i class="fa fa-transgender fa-2x" style="color:#51a351; display:block;"></i></a>
			<?php } else if ($genders == 3) { ?> 
				<i class="fa fa-venus fa-2x" style="color:#bd362f; display:block;"></i>
				<a href="/admin/hestetegner/change_generation_status_version_two.php?race='<?= $selected_race; ?>'&do=make_mars&thumb=<?= $data->image; ?>&type=<?= $_GET['type']; ?>"><i class="fa fa-mars fa-2x" style="color:#0f66b4; display:block;"></i></a>
				<a href="/admin/hestetegner/change_generation_status_version_two.php?race='<?= $selected_race; ?>'&do=make_trans&thumb=<?= $data->image; ?>&type=<?= $_GET['type']; ?>"><i class="fa fa-transgender fa-2x" style="color:#51a351; display:block;"></i></a>
			<?php } else if ($genders == 1) { ?>
				<i class="fa fa-transgender fa-2x" style="color:#51a351; display:block;"></i>
				<a href="/admin/hestetegner/change_generation_status_version_two.php?race='<?= $selected_race; ?>'&do=make_mars&thumb=<?= $data->image; ?>&type=<?= $_GET['type']; ?>"><i class="fa fa-mars fa-2x" style="color:#0f66b4; display:block;"></i></a>
				<a href="/admin/hestetegner/change_generation_status_version_two.php?race='<?= $selected_race; ?>'&do=make_venus&thumb=<?= $data->image; ?>&type=<?= $_GET['type']; ?>"><i class="fa fa-venus fa-2x" style="color:#bd362f; display:block;"></i></a>
			<?php } ?>
			</div>		
		</li>
			<?php 
	}
	?>
	</ul>
	<div id="switch_type_race" class="modal">
	<style>
	#switch_type_race .btn {
		margin:2px 1px;width:170px;font-family:Roboto;padding:0 7px;font-size:15px;
	}
	</style>
		<script>
			function switch_type_race(caller) {
				jQuery('#switch_type_race__thumb').attr('value', jQuery(caller).parent().attr('data-horse-thumb'));
				jQuery('#switch_type_race__thumb').val(jQuery(caller).parent().attr('data-horse-thumb'));
			}
		</script>
		<div class="shadow"></div>
		<div class="content" style="width:916px;">
			<h2>Skift race</h2>
			<form id="switch_type_race_form" action="" method="post">
				<input id="switch_type_race__thumb" type="hidden" name="thumb" value="" />
			<?php 
				foreach (array_sorter($cached_races,'name',true) as $race){
					?><input class="btn btn-info" type="submit" value="<?= $race['name']; ?>" name="extra_race" />
				<?php }	?>
			</form>
		</div>
	</div>
	<script>
		function slide_gender_toggle(caller){
			if(jQuery(caller).attr('state') == 'opened'){
				jQuery(caller).attr('state','closed');
				jQuery(caller).animate({height: "32px"}, 500);
			} else {
				jQuery(caller).attr('state','opened');
				jQuery(caller).animate({height: "96px"}, 500);
			}
		}
		</script>
</section>
<?php
require "$basepath/global_modules/footer.php";
