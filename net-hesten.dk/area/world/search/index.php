<?php
 /* Mit Stutteri */
$basepath = '../../../..';
$title = 'Stutteri';
require "$basepath/app_core/object_loader.php";
require "$basepath/global_modules/header.php";


$search_horses_page = max(0, (int)filter_input(INPUT_GET, 'search_horses_page'));
$horses_pr_page = 10;
?>
<?php $user_info = user::get_info(['user_id' => $_SESSION['user_id']]); ?>
<?php $dead = 'død'; ?>
<style>
    .tabs {
		margin-top: 1em;
	}
	[data-button-type="zone_activator"] {
		margin-bottom: 5px;
	}
	.foel {
		position: absolute;
		bottom: 45px;
		right:12px;
	}
	.enter_graes {
		position: absolute;
		bottom: 12px;
		right:12px;
	}
	.horse_square + .horse_square + .horse_square + .horse_square + .horse_square + .horse_square + .horse_square + .horse_square + .horse_square + .horse_square + .horse_square {
		display: none;
	}
</style>
<?php
$horse_array = [];
$horse_tabs = [];
$attr = ['user_name' => $_SESSION['username'], 'mode' => 'search_all'];
$attr['custom_filter'] .= horse_list_filters::get_filter_string(['zone' => "search_all"]);

$offset = $search_horses_page * $horses_pr_page;
$limit = ($search_horses_page * $horses_pr_page) + $horses_pr_page;
$attr['limit'] = 100;
$attr['offset'] = $offset;
$i = 0;
if (is_array(horses::get_all($attr))) {
    foreach (horses::get_all($attr) as $horse) {
        $horse_is_at_competition = false;
        if ($horse['competition_id']) {
            $horse_is_at_competition = true;
        }
        $gender = ((string)strtolower($horse['gender']) === 'hoppe') ? 'female' : '';
        $gender = ((string)strtolower($horse['gender']) === 'hingst') ? 'male' : $gender;
        $gender = ((string)$gender === '') ? 'error' : $gender;
        $extended_info = '';
		if (in_array('tech_admin', $_SESSION['rights'])) { }
		$owner_id = $link_new->query("SELECT id FROM {$_GLOBALS['DB_NAME_OLD']}.Brugere WHERE stutteri = '{$horse['owner_name']}' LIMIT 1")->fetch_object()->id;
		$owner_name = $horse['owner_name'];
		$extended_info = [
            'name' => $horse['name'],
            'age' => $horse['age'],
            'gender' => $horse['gender'],
            'race' => $horse['race'],
            'artist' => $horse['artist'],
            'id' => $horse['id'],
            'value' => $horse['value'],
			'owner_name' => $owner_name,
			'owner_id' => $owner_id,
            'talent' => $horse['talent'],
            'ulempe' => $horse['ulempe'],
            'egenskab' => $horse['egenskab'],
            'type' => ($horse['unik'] == 'ja' ? 'unique' : ($horse['original'] == 'ja' ? 'original' : 'normal')),
            'gold_medal' => (empty($horse['gold_medal']) ? 0 : $horse['gold_medal']),
            'silver_medal' => (empty($horse['silver_medal']) ? 0 : $horse['silver_medal']),
            'bronze_medal' => (empty($horse['bronze_medal']) ? 0 : $horse['bronze_medal']),
            'junior_medal' => (empty($horse['junior_medal']) ? 0 : $horse['junior_medal']),
        ];
        /* 	?>
* Stamtavle
* Opdrætter
<?php */
        $extended_info = json_encode($extended_info);
        $horse_data = '';
        $horse_data .= "<div class='horse_square horse_object {$gender}' data-horse-id='{$horse['id']}' data-extended-info='{$extended_info}'>";
        $horse_data .= "<div class='info'>";
        //$horse_name_convert = mb_convert_encoding($horse['name'], 'UTF-8', 'latin1');
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
		$horse_data .= "<a href='https://net-hesten.dk/area/world/visit/visit.php?user={$owner_id}'><button class='enter_graes btn btn-info compact_bottom_button' data-button-type='modal_activator' data-target='put_horse_on_grass'>Ejes af {$owner_name}</button></a>";
        
        $horse_data .= "</div>";
        $horse_data .= "<img src='//".filter_input(INPUT_SERVER ,'HTTP_HOST')."/{$horse['thumb']}' data-button-type='modal_activator' data-target='horze_extended_info' />";
        $horse_data .= "<img style='display: none;' class='zoom_img' src='//".filter_input(INPUT_SERVER ,'HTTP_HOST')."/{$horse['thumb']}' />";
        $horse_data .= "</div>";

        $horse_tabs['idle_horses'][] = $horse_data;
        $horse_array[] = $horse_data;
    }
    $amount_active_selection = count($horse_array);
    $amounts['idle_horses'] = count($horse_tabs['idle_horses']);
    $amounts['horses_on_grass'] = count($horse_tabs['horses_on_grass']);
    $amounts['horses_at_contest'] = count($horse_tabs['horses_at_contest']);
    $amounts['breeding_horses'] = count($horse_tabs['breeding_horses']);
    $amounts['foels'] = count($horse_tabs['foels']);
    $amounts['foels_at_contest'] = count($horse_tabs['foels_at_contest']);
    foreach ($amounts as $list => $amount) {
        if ($amount > 10) { } else { }
    }
} else {
    $amounts['idle_horses'] = 0;
    $amounts['horses_on_grass'] = 0;
    $amounts['horses_at_contest'] = 0;
    $amounts['breeding_horses'] = 0;
    $amounts['foels'] = 0;
    $amounts['foels_at_contest'] = 0;
}
if ($active_tab = filter_input(INPUT_GET, 'tab')) {
    if ($active_tab == 'idle_horses') {
        $active_tab_amount = $amounts['idle_horses'];
    } else if ($active_tab == 'horses_on_grass') {
        $active_tab_amount = $amounts['horses_on_grass'];
    } else if ($active_tab == 'breeding_horses') {
        $active_tab_amount = $amounts['breeding_horses'];
    } else if ($active_tab == 'horses_at_contest') {
        $active_tab_amount = $amounts['horses_at_contest'];
    } else if ($active_tab == 'foels') {
        $active_tab_amount = $amounts['foels'];
    } else if ($active_tab == 'foels_at_contest') {
        $active_tab_amount = $amounts['foels_at_contest'];
    }
} else {
    $active_tab_amount = $amounts['idle_horses'];
}
?>

<section class="tabs">
    <section data-zone="horses">
        <div class="grid">
            <div data-section-type="info_square">
                <header>
                    <h1>Alle Heste</h1>
                </header>
                <div class="page_selector">
                    <span class="btn btn-white">Side:
                        <?= $search_horses_page + 1; ?></span>&nbsp;<a class="btn btn-info" href="?search_horses_page=<?= $search_horses_page - 1; ?>">Forrige side</a>&nbsp;<a class="btn btn-info" href="?search_horses_page=<?= $search_horses_page + 1; ?>">Næste side</a>
                    <a class="btn btn-info" style="line-height: 30px;" data-button-type='modal_activator' data-target='filter_horses'>Filtre</a>
                </div>
            </div>
            <?php
            if (is_array($horse_array)) {
                foreach ($horse_array as $horse) {
                    echo $horse . PHP_EOL;
                }
            }
            ?>
        </div>
    </section>
</section>
<script>
    jQuery(document).ready(function () {
<?php if (!isset($_GET['tab'])) { ?>
			jQuery('[data-target="idle_horses"]').click();
<?php 
} else { ?>
			jQuery('[data-target="<?= $_GET['tab']; ?>"]').click();
<?php 
} ?>
	});
	jQuery('[data-button-type="zone_activator"]').each(function () {
		jQuery(this).click(function () {
			jQuery('.tabs > section').removeClass('visible');
			jQuery('.tabs > section[data-zone="' + jQuery(this).attr('data-target') + '"]').addClass('visible');
		});
	});</script>
<?php
 /* Define modal - start */
ob_start();
?>
<style>
    .fifty_p {
		width: 50%;float: left;line-height: 25px;margin-bottom: 5px;
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
        <?= horse_list_filters::render_filter_settings(['zone' => "search_all"]); ?>
    </div>
</div>


<div id="horze_extended_info" class="modal">
    <script>
        function horze_extended_info(caller) {
            horse_data = JSON.parse(jQuery(caller).parent().attr('data-extended-info'));
            jQuery('#horze_extended_info .name').html(horse_data.name);
            jQuery('#horze_extended_info .id').html(horse_data.id);
            jQuery('#horze_extended_info .age').html(horse_data.age);
            jQuery('#horze_extended_info .gender').html(horse_data.gender);
            jQuery('#horze_extended_info .race').html(horse_data.race);
            jQuery('#horze_extended_info .artist').html(horse_data.artist);
            jQuery('#horze_extended_info .value').html(horse_data.value);
            jQuery('#horze_extended_info .owner_name').html('<a href="https://net-hesten.dk/area/world/visit/visit.php?user='+horse_data.owner_id+'">'+horse_data.owner_name+'</a>');
            jQuery('#horze_extended_info .talent').html(horse_data.talent);
            jQuery('#horze_extended_info .ulempe').html(horse_data.ulempe);
            jQuery('#horze_extended_info .egenskab').html(horse_data.egenskab);
            jQuery('#horze_extended_info .type').html(horse_data.type);
            jQuery('#horze_extended_info .gold_medal').html(horse_data.gold_medal);
            jQuery('#horze_extended_info .silver_medal').html(horse_data.silver_medal);
            jQuery('#horze_extended_info .bronze_medal').html(horse_data.bronze_medal);
            jQuery('#horze_extended_info .junior_medal').html(horse_data.junior_medal);
        }
    </script>
    <style>
        #horze_extended_info div {
            line-height: 25px;
        }

        #horze_extended_info span {
            font-family: 'Merienda One', cursive;
        }
    </style>
    <div class="shadow"></div>
    <div class="content">
        <div style="position:absolute;top:6px;right:10px;" onclick="jQuery(this).parent().parent().removeClass('active');"><i class="fa fa-times fa-2x nh-error-color"></i></div>
        <h2>Mere om: <span class="name"></span> <span class="age"></span> år</h2>
        <div>
            <span class="label">ID:</span> <span class="id"></span>
        </div>
        <div>
            <span class="label">Køn:</span> <span class="gender"></span>
        </div>
        <div>
            <span class="label">Race:</span> <span class="race"></span>
        </div>
        <div>
            <span class="label">Tegner:</span> <span class="artist"></span>
        </div>
        <div>
            <span class="label">Værdi:</span> <span class="value"></span>
        </div>
        <div>
            <span class="label">Ejer:</span> <span class="owner_name"></span>
        </div>
        <div>
            <span class="label">Talent:</span> <span class="talent"></span>
        </div>
        <div>
            <span class="label">Ulempe:</span> <span class="ulempe"></span>
        </div>
        <div>
            <span class="label">Egenskab:</span> <span class="egenskab"></span>
        </div>
        <div>
            <span class="label">Type:</span> <span class="type"></span>
        </div>
        <div>
            <span class="label">Guld:</span> <span class="gold_medal"></span>
        </div>
        <div>
            <span class="label">Sølv:</span> <span class="silver_medal"></span>
        </div>
        <div>
            <span class="label">Bronze:</span> <span class="bronze_medal"></span>
        </div>
        <div>
            <span class="label">Føl kåringer:</span> <span class="junior_medal"></span>
        </div>
        <div>
            <span class="label">Udstyr:</span> <span class="">Kommer snart!</span>
        </div>
        <div>
            <span class="label">Stamtavle:</span> <span class="">Kommer snart!</span>
        </div>
        <div>
            <span class="label">Opdrætter:</span> <span class="">Kommer snart!</span>
        </div>
    </div>
</div>
<?php  ?>
<style>
    .horse_object .info .name {
        z-index: 5;
        cursor: pointer;
    }

    .horse_object .info .name input.horse_rename_input {
        position: absolute;
        top: 3px;
        width: 200px;
        max-width: none;
    }
</style>
<?php  ?>
<?php
$modals[] = ob_get_contents();
ob_end_clean();
/* Define modal - end */
?>
<?php
require_once("{$basepath}/global_modules/footer.php");
