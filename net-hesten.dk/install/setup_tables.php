<?php
$basepath = '../..';
require_once("{$basepath}/app_core/db_conf.php");

$files = [
    'accounting',
    'artist_submissions',
    'auctions',
    'chat',
    'code_types',
    'competitions',
    'horses',
    'opinions',
    'priv_trade',
    'users'
];
foreach ($files as $file) {
    $query = file_get_contents("{$basepath}/shema_setups/new/{$file}.sql");
    $stmt = $GLOBALS['pdo_new']->prepare($query);
    if ($stmt->execute()) {
        echo "Success &lt;{$file}&gt;<br />";
    } else {
        echo "Fail &lt;{$file}&gt;<br />";
    }
}

$query = file_get_contents("{$basepath}/shema_setups/old/latin1_tables.sql");
$stmt = $GLOBALS['pdo_old']->prepare($query);
if ($stmt->execute()) {
    echo "Success &lt;Latin 1&gt;<br />";
} else {
    echo "Fail &lt;Latin 1&gt;<br />";
}

?>
<a href="/install/">go back</a>

