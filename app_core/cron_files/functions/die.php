<?php

if (!isset($basepath)) {
    $basepath = '';
}

if (!isset($time_now)) {
    date_default_timezone_set('Europe/Copenhagen');
    $current_date = new DateTime('now');
    $date_now = $current_date->format('Y-m-d');
    $time_now = $current_date->format('H:i:s');
}

$log_content = PHP_EOL . '# Checking for horses, that are ready, for the afterlife.';
file_put_contents("{$basepath}app_core/cron_files/logs/cron_one_hour_{$date_now}", $log_content, FILE_APPEND);
require_once "{$basepath}app_core/db_conf.php";
require_once "{$basepath}app_core/functions/number_dotter.php";
require_once "{$basepath}app_core/object_handlers/accounting.php";

$Foelbox = mb_convert_encoding('Følkassen', 'latin1', 'UTF-8');
$foel = mb_convert_encoding('føl', 'latin1', 'UTF-8');
$Foel = mb_convert_encoding('Føl', 'latin1', 'UTF-8');
$dead = mb_convert_encoding('død', 'latin1', 'UTF-8');
$DEAD = mb_convert_encoding('DØD', 'latin1', 'UTF-8');
$Doctor = mb_convert_encoding('Dyrelægen', 'latin1', 'UTF-8');
$Dear = mb_convert_encoding('Kære', 'latin1', 'UTF-8');
$the_value = mb_convert_encoding('værdien', 'latin1', 'UTF-8');
$competitions = mb_convert_encoding('stævner', 'latin1', 'UTF-8');
$Foel_competitions = mb_convert_encoding('Følkåringer', 'latin1', 'UTF-8');
$foel_wins = mb_convert_encoding('kåringer', 'latin1', 'UTF-8');
$first_place = mb_convert_encoding('førsteplads', 'latin1', 'UTF-8');
$gets = mb_convert_encoding('får', 'latin1', 'UTF-8');
$got = mb_convert_encoding('fået', 'latin1', 'UTF-8');
$sadly = mb_convert_encoding('desværre', 'latin1', 'UTF-8');
$Unicorns = mb_convert_encoding('Enhjørninge', 'latin1', 'UTF-8');
$Unicorn = mb_convert_encoding('Enhjørning', 'latin1', 'UTF-8');
$special = mb_convert_encoding('særlig', 'latin1', 'UTF-8');
$of = mb_convert_encoding('på', 'latin1', 'UTF-8');
$hope = mb_convert_encoding('håber', 'latin1', 'UTF-8');
$claim_pay = mb_convert_encoding('værdiudbetaling', 'latin1', 'UTF-8');
$years = mb_convert_encoding('år', 'latin1', 'UTF-8');

$today = date("d.m.y.G.i");
$loop = 0;
/* Limit 200 = stabil */
$sql = "SELECT id, alder, bruger, navn, foersteplads, andenplads, tredieplads, kaaringer, pris, race, original, unik FROM Heste WHERE alder > 20 AND bruger != '{$Foelbox}' AND bruger != 'hestehandleren*' and bruger <> 'genfoedsel' AND status = 'hest' ORDER BY rand() LIMIT 175";
$result = $link_old->query($sql);
$viable_horses = 0;
$killed_amount = 0;
while ($horse = $result->fetch_object()) {
    ++$viable_horses;

    if (mt_rand(0, 100) <= $horse->alder) {

        if ($horse->bruger == 'hestehandleren' || $horse->bruger == 'Hestehandleren' || $horse->bruger == NULL) {
            $horse->bruger = 'techhesten';
        }
        $horse_name = mb_convert_encoding($horse->navn, 'UTF-8', 'latin1');
        $horse_user_name = mb_convert_encoding($horse->bruger, 'UTF-8', 'latin1');


        if (strpos("'", $horse->bruger)) {
            continue;
        }
        ++$killed_amount;

        $tegn = array("&", '"', "'");
        $substitut = array("og", "&quot;", "&#039;");

        $user = $link_old->query("SELECT id, penge, stutteri FROM Brugere WHERE stutteri = '{$horse->bruger}' LIMIT 1")->fetch_object();
        $kids = $link_old->query("SELECT count(id) AS kids FROM Heste WHERE farid = {$horse->id} OR morid = {$horse->id}")->fetch_object()->kids;

        $claim = round(($horse->pris * 0.8), 0);
        $claim += ($horse->foersteplads * 5000);
        $claim += ($horse->andenplads * 1000);
        $claim += ($horse->tredieplads * 500);
        $claim += ($horse->kaaringer * 2000);
        $claim += ($kids * 1000);

        $original = false;
        $unique = false;
        if (strtolower($horse->original) === 'ja') {
            $original = true;
            $claim += 10000;
        }
        if (strtolower($horse->unik) === 'ja') {
            $unique = true;
            $claim += 50000;
        }
        $claim_for_text = number_dotter($claim);
        $message = "Kære {$horse_user_name}. <br /><br/>";
        $message .= "Dyrelægen kunne desværre ikke stille noget op, og {$horse_name} er død, {$horse->alder} år gammel.<br /><br />";

        $message .= "Du har fået udbetalt 80% af værdien på {$horse_name} og en bonus, der tilsammen giver {$claim_for_text} wkr.<br />"
                . "Det er beregnet ud fra, hvor mange stævner {$horse_name} vandt, hvor mange kåringer {$horse_name} vandt, "
                . "og hvor mange føl {$horse_name} fik. <br /><br />";
        $message .= "{$horse_name} fik {$kids} føl, der giver en bonus på 1.000 wkr pr. føl. <br />"
                . "Derudover har {$horse_name} vundet 0 stævner, der giver 5.000 wkr for en førsteplads. "
                . "{$horse_name} fik {$horse->andenplads} andenpladser i stævner, hvilket giver 1.000 wkr pr. stk., og {$horse->tredieplads} tredjepladser, der giver 500 wkr pr. stk. <br />"
                . "Følkåringer giver 2.000 wkr stykket, og {$horse_name} fik {$horse->kaaringer} kåringer. <br />";
        if ($original) {
            $message .= "{$horse_name} var original hvilket giver 10.000 wkr i bonus.<br />";
        }
        if ($unique) {
            $message .= "{$horse_name} var unik og for derfor 50.000 wkr i bonus.<br />";
        }
        $message .= "Vi håber, du har haft mange gode stunder med {$horse_name}.";
        $message = str_replace($tegn, $substitut, $message);
        /* Sæt giv penge til brugeren */
        $sql = "UPDATE Brugere SET penge = (penge + {$claim}) WHERE id = {$user->id}";
        $link_old->query($sql);
        /* Insert to bank history */
        $saldo = $claim + $user->penge;
        
        /* Kill Horse */
        $sql = "UPDATE Heste SET status = '{$dead}', death_date = '{$date_now}' WHERE id = {$horse->id}";
        $link_old->query($sql);
        
        $utf_8_message = $message;
        $sql = "INSERT INTO game_data_private_messages (status_code, hide, origin, target, date, message) VALUES (17, 0, 53432, {$user->id}, NOW(), '{$utf_8_message}' )";
        $link_new->query($sql);
        accounting::add_entry(['amount' => $claim, 'line_text' => "Erstatning for {$horse_name} [{$horse->id}]", "user_id" => $user->id, "mode" => "+"]);
    }
}
$log_content = PHP_EOL . '#'
        . PHP_EOL . "# Found {$viable_horses} viable horses."
        . PHP_EOL . "# {$killed_amount} were killed.";
file_put_contents("{$basepath}app_core/cron_files/logs/cron_one_hour_{$date_now}", $log_content, FILE_APPEND);
