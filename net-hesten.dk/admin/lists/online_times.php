<?php
$basepath = '../../..';
require "$basepath/app_core/object_loader.php";
require "$basepath/global_modules/header.php";
?>
<?php
if (!in_array('global_admin', $_SESSION['rights'])) {
    ob_end_clean();
    header('Location: /');
}


$intervals = [
    [
        'start' => '2018-09-01 00:00:00',
        'end' => '2018-10-01 00:00:00'
    ], [
        'start' => '2018-10-01 00:00:00',
        'end' => '2018-11-01 00:00:00'
    ], [
        'start' => '2018-11-01 00:00:00',
        'end' => '2018-12-01 00:00:00'
    ], [
        'start' => '2018-12-01 00:00:00',
        'end' => '2019-01-01 00:00:00'
    ],
];
$intervals = array_reverse($intervals);

foreach ($intervals as $interval) {
    $start = $interval['start'];
    $end = $interval['end'];
    echo $start . ' -> ' . $end . '<br /><br />';
    $players = $link_new->query("SELECT user_id, SUM(TIME_TO_SEC(TIMEDIFF(end, start))) AS duration FROM {$GLOBALS['DB_NAME_NEW']}.user_data_sessions WHERE start > '{$start}' and start < '{$end}' GROUP BY user_id ORDER BY (SUM(TIME_TO_SEC(TIMEDIFF(end, start)))) DESC LIMIT 20");
    while ($player = $players->fetch_object()) {
        $duration = round((1 / 60 / 60) * ((int) $player->duration));
        echo $link_new->query("SELECT stutteri FROM {$GLOBALS['DB_NAME_OLD']}.Brugere WHERE id = {$player->user_id} LIMIT 1")->fetch_object()->stutteri . ': ' . $duration . ' Timer<br />';
    }
    echo "<br /><br />";
}

require "$basepath/global_modules/footer.php";
