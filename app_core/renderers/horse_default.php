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
            <i class='gender icon-<?= $gender; ?>-1'></i>
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
                    <?php if (in_array($area, ['main_stud'])) { ?>
                        <span class='value'>Værdi: <?= number_dotter($horse['value']); ?><span class="wkr_symbol">wkr</span></span>
                    <?php } elseif (in_array($area, ['horse_trader_sell'])) { ?>
                        <span class='value' style="transform: translateY(-18px);">Værdi: <?= number_dotter($horse->value); ?> <span class="wkr_symbol">wkr</span></span>
                        <span class='value'>Sælg for: <?= number_dotter($horse->value * 0.9); ?> <span class="wkr_symbol">wkr</span></span>
                    <?php } ?>
                </div>
            </div>
            <?php if (in_array($area, ['horse_trader_sell'])) { ?>
                <?php if ($_SESSION['settings']['horse_trader_buy_confirmations'] == 'show') { ?>
                    <button class='open_sell_window btn btn-success compact_bottom_button'>Sælg</button>
                    <form action="/area/world/horsetrader/sell.php" method="post" class="action_popup buy_horse">
                        <?php /* code to place bid */ ?>
                        <input type="hidden" name="action" value="sell_horse_to_trader" />
                        <input type="hidden" name="horse_id" value="<?= $horse->id; ?>" />
                        <?php if (filter_input(INPUT_POST, 'race') && !empty(filter_input(INPUT_POST, 'race'))) { ?>
                            <input type="hidden" name="race" value="<?= filter_input(INPUT_POST, 'race'); ?>" />
                        <?php } ?>
                        <?php if (filter_input(INPUT_POST, 'gender') && !empty(filter_input(INPUT_POST, 'gender'))) { ?>
                            <input type="hidden" name="gender" value="<?= filter_input(INPUT_POST, 'gender'); ?>" />
                        <?php } ?>
                        <div>ID: <?= $horse->id; ?><br /><br /></div>
                        <div>Du for: <?= number_dotter(($horse->value * 0.9)); ?> <span class="wkr_symbol">wkr</span><br /><br /></div>
                        <input type='submit' class='btn btn-success' name='sell_now' value="Sælg nu" />
                        <button class='close_sell_window btn btn-danger'>Luk</button>
                    </form>
                <?php } else { ?>
                    <form action="/area/world/horsetrader/sell.php" method="post" class="nonconfirmation_form visible open_sell_window btn btn-success compact_bottom_button">
                        <?php if (filter_input(INPUT_POST, 'race') && !empty(filter_input(INPUT_POST, 'race'))) { ?>
                            <input type="hidden" name="race" value="<?= filter_input(INPUT_POST, 'race'); ?>" />
                        <?php } ?>
                        <?php if (filter_input(INPUT_POST, 'gender') && !empty(filter_input(INPUT_POST, 'gender'))) { ?>
                            <input type="hidden" name="gender" value="<?= filter_input(INPUT_POST, 'gender'); ?>" />
                        <?php } ?>
                        <input type="hidden" name="action" value="sell_horse_to_trader" />
                        <input type="hidden" name="horse_id" value="<?= $horse->id; ?>" />
                        <input type='submit' class='btn btn-success' name='sell_now' value="Sælg nu" />
                    </form>
                <?php } ?>
            <?php } ?>

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
