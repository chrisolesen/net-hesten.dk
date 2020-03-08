<?php
function render_horse_object($horse)
{

    $horse_is_at_competition = false;
    if ($horse['competition_id']) {
        $horse_is_at_competition = true;
    }
    $gender = ((string) strtolower($horse['gender']) === 'hoppe') ? 'female' : '';
    $gender = ((string) strtolower($horse['gender']) === 'hingst') ? 'male' : $gender;
    $gender = ((string) $gender === '') ? 'error' : $gender;
    $extended_info = '';
    if (in_array('tech_admin', $_SESSION['rights'])) {
    }
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
    $disallow_breeding = false;
    if ($horse['status'] == 'føl' || $horse['staevne'] == 'ja' || $horse['graesning'] == 'ja' || $gender == 'male' || $horse_is_at_competition || $horse['breed_date']) {
        $disallow_breeding = true;
    }
    if (!$disallow_breeding) {
        $horse_data .= "<button class='foel btn btn-info compact_top_button' data-button-type='modal_activator' data-target='breed_horse'>Avl</button>";
    }
    if ($horse['breed_date']) {

        $breed_date_target = new DateTime($horse['breed_date']);
        $breed_date_target->add(new DateInterval('P40D'));
        $horse_data .= "<button style='pointer-events: none;' class='enter_graes btn compact_top_button'>Foler ca. {$breed_date_target->format('Y-m-d')}</button>";
    }
    if ($horse['graesning'] == 'ja') {
        $horse_data .= "<button class='enter_graes btn btn-info compact_bottom_button' data-button-type='modal_activator' data-target='put_horse_in_stable'>Sæt i stald</button>";
    } elseif ($horse['staevne'] != 'ja' && !$horse_is_at_competition) {
        $horse_data .= "<button class='enter_graes btn btn-info compact_bottom_button' data-button-type='modal_activator' data-target='put_horse_on_grass'>Sæt på græs</button>";
    }
    $horse_data .= "</div>";
    $horse_data .= "<img src='//files." . HTTP_HOST . "/{$horse['thumb']}' data-button-type='modal_activator' data-target='horze_extended_info' />";
    $horse_data .= "<img style='display: none;' class='zoom_img' src='//files." . HTTP_HOST . "/{$horse['thumb']}' />";
    $horse_data .= "</div>";
    return $horse_data;
}
