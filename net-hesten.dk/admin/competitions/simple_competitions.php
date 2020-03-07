<?php
$basepath = '../../..';
require "$basepath/app_core/object_loader.php";
require "$basepath/global_modules/header.php";

if (!in_array('global_admin', $_SESSION['rights'])) {
    ob_end_clean();
    header('Location: /');
}

 

if (filter_input(INPUT_GET, 'do') === 'end_simple_competition') {
    if ($competition_id = filter_input(INPUT_GET, 's_com_id')) {
        $winner = $link_new->query("SELECT participant_id AS winner FROM {$_GLOBALS['DB_NAME_NEW']}.game_data_simple_competition_participants where competition_id = {$competition_id} ORDER BY rand() limit 1")->fetch_object()->winner;
        
        $link_new->query("UPDATE {$_GLOBALS['DB_NAME_NEW']}.game_data_simple_competition SET winner = {$winner}, status_code = 43 WHERE id = {$competition_id}");

        $selected_id =((object)json_decode( $link_new->query("SELECT data FROM {$_GLOBALS['DB_NAME_NEW']}.game_data_simple_competition WHERE id = {$competition_id}")->fetch_object()->data))->prize_id;

        //$competion_subdata->prize_id;
        if ($selected_id) {
            $thumb_data = $link_new->query("SELECT artists, image, race FROM {$_GLOBALS['DB_NAME_NEW']}.horse_types WHERE id = {$selected_id} LIMIT 1")->fetch_object();
            $artist = $link_old->query("SELECT stutteri FROM {$_GLOBALS['DB_NAME_OLD']}.Brugere WHERE id = {$thumb_data->artists} LIMIT 1")->fetch_object()->stutteri;
            if (!$artist) {
                $artist = $link_old->query("SELECT stutteri FROM {$_GLOBALS['DB_NAME_OLD']}.Brugere WHERE id = 0 LIMIT 1")->fetch_object()->stutteri;
                //exit('Kun en tegner lige nu tak.');
            }
           
            $thumb = '/imgHorse/' . $thumb_data->image;
            $race = mb_convert_encoding($thumb_data->race, 'latin1', 'UTF-8');

            $advantage = $link_old->query("SELECT egenskab FROM {$_GLOBALS['DB_NAME_OLD']}.horse_habits WHERE egenskab <> '' ORDER BY RAND() LIMIT 1")->fetch_object()->egenskab;
            $disadvantage = $link_old->query("SELECT ulempe FROM {$_GLOBALS['DB_NAME_OLD']}.horse_habits WHERE ulempe <> '' ORDER BY RAND() LIMIT 1")->fetch_object()->ulempe;
            $talent = $link_old->query("SELECT talent FROM {$_GLOBALS['DB_NAME_OLD']}.horse_habits WHERE talent <> '' ORDER BY RAND() LIMIT 1")->fetch_object()->talent;

            $height_data = $link_old->query("SELECT lower, upper FROM {$_GLOBALS['DB_NAME_OLD']}.horse_height WHERE race = '{$race}' LIMIT 1")->fetch_object();
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
            $name = mb_convert_encoding($name, 'latin1', 'UTF-8');

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
                $link_old->query($sql);
                ?>
                <a href="/admin/hestetegner/edit_horse.php?horse_id=<?= mysqli_insert_id($link_old); ?>">ID: <?= mysqli_insert_id($link_old); ?></a>
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
}


?>
<style>
    .admin_panel .btn.btn-info {
        margin-bottom:0.5em;
        display:block;
        width:260px;
    }
</style>
<section class="admin_panel">
    <h1>Lodtræknings Admin</h1>
    <a class="btn btn-info" href="/admin/competitions/">Tilbage</a>
    <ol>
    <?php $competitions = $link_new->query("SELECT * FROM {$_GLOBALS['DB_NAME_NEW']}.game_data_simple_competition");
    while ($competition = $competitions->fetch_object()) {
        $competion_subdata = (object)json_decode($competion_data->data);
        //$competion_subdata->prize_id;
        ?>
        <li style="border-bottom:1px dashed #333;">
        <?= ($competition->status_code == 42 ? '<span style="color:green;">Live</span>' : ''); ?>
        <?= $link_new->query("SELECT count(competition_id) as amount FROM {$_GLOBALS['DB_NAME_NEW']}.game_data_simple_competition_participants WHERE competition_id = {$competition->id}")->fetch_object()->amount; ?> Deltagere - 
        <?= $competition->competition_name; ?>
        <?php if ($competition->status_code == 42) { ?>
        <a style="color:red;" href="?do=end_simple_competition&s_com_id=<?= $competition->id; ?>">Afslut</a>
        <?php 
    } ?>
        </li>
        <?php 
    } ?>
    </ol>
<?php /* competition status 42 = live 43 = ended */ ?>
</section>
<?php
require "$basepath/global_modules/footer.php";
