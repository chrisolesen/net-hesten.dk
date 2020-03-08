<?php
function render_horse_object($horse, $area)
{
    ob_start();
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

?>
    <div class='horse_square horse_object <?= $gender; ?>' data-horse-id='<?= $horse['id']; ?>'>
        <div class='info'>
            <span class='name'><?= $horse['name']; ?></span>
            <i class='gender icon-{$gender}-1'></i>
            <div class='horse_vcard'>
                <i class='icon-vcard'></i>
                <div class='extended_info'>
                    <span class='type_age'>
                        <?= ($horse['unik'] == 'ja' ? '<span class="unique">Unik</span> ' : ($horse['original'] == 'ja' ? '<span class="original">Original</span> ' : '')); ?>
                        <?= $horse['race']; ?> <?= $horse['age']; ?> år</span><br />
                    <span class='horse_id'>ID: <?= $horse['id']; ?></span><br /><br />
                    <span class='ability'>Egenskab: <?= $horse['egenskab']; ?></span><br />
                    <span class='disability'>Ulempe: <?= $horse['ulempe']; ?></span><br />
                    <span class='talent'>Talent: <?= $horse['talent']; ?></span><br /><br />
                    <span class='artist'>Tegner: <?= $horse['artist']; ?></span>
                    <span class='value'>Værdi: <?= number_dotter($horse['value']); ?><span class="wkr_symbol">wkr</span></span>
                </div>
            </div>
            <?php
            $disallow_breeding = false;
            if ($horse['status'] == 'føl' || $horse['staevne'] == 'ja' || $horse['graesning'] == 'ja' || $gender == 'male' || $horse_is_at_competition || $horse['breed_date']) {
                $disallow_breeding = true;
            }
            if (!$disallow_breeding && in_array($area, ['main_stud'])) {
            ?>
                <button class='foel btn btn-info compact_top_button' data-button-type='modal_activator' data-target='breed_horse'>Avl</button>
            <?php
            }
            if ($horse['breed_date'] && in_array($area, ['main_stud'])) {

                $breed_date_target = new DateTime($horse['breed_date']);
                $breed_date_target->add(new DateInterval('P40D'));
            ?>
                <button style='pointer-events: none;' class='enter_graes btn compact_top_button'>Foler ca. <?= $breed_date_target->format('Y-m-d'); ?></button>
            <?php
            }
            if ($horse['graesning'] == 'ja' && in_array($area, ['main_stud'])) {
            ?>
                <button class='enter_graes btn btn-info compact_bottom_button' data-button-type='modal_activator' data-target='put_horse_in_stable'>Sæt i stald</button>
            <?php
            } elseif ($horse['staevne'] != 'ja' && !$horse_is_at_competition && in_array($area, ['main_stud'])) {
            ?>
                <button class='enter_graes btn btn-info compact_bottom_button' data-button-type='modal_activator' data-target='put_horse_on_grass'>Sæt på græs</button>
            <?php
            }
            ?>
        </div>
        <img src='//files.<?= HTTP_HOST; ?>/<?= $horse['thumb']; ?>' data-button-type='modal_activator' data-target='horze_extended_info' />
        <img style='display: none;' class='zoom_img' src='//files.<?= HTTP_HOST; ?>/<?= $horse['thumb']; ?>' />
    </div>
<?php
    $return_data = ob_get_contents();
    ob_end_clean();
    return $return_data;
}
