<?php

if ($index_caller !== true) {
    exit();
}
header('content-type: application/json; charset=utf-8');

if (($horse_id = (int) filter_input(INPUT_GET, 'horse_id'))) {
    $response = array();
    $response['status'] = false;
    $response['time'] = time();
//        echo $horse_id;exit();
    if (is_numeric($horse_id) && is_numeric($_SESSION['user_id'])) {
        $username = $link_new->query("SELECT `stutteri` FROM `{$GLOBALS['DB_NAME_OLD']}`.`Brugere` WHERE `id` = {$_SESSION['user_id']}")->fetch_object()->stutteri;
        if (!$username) {
            echo $_GET['callback'] . '(' . json_encode([$response]) . ')';
            exit();
        }
        $result = $link_new->query("SELECT `navn`, `bruger` FROM `{$GLOBALS['DB_NAME_OLD']}`.`Heste` WHERE `id` = {$horse_id} AND `bruger` = '{$username}'");
        if (!$result) {
            echo $_GET['callback'] . '(' . json_encode([$response]) . ')';
            exit();
        }
        while ($data = $result->fetch_object()) {
            $new_name = filter_input(INPUT_GET, 'new_name');
            $new_name = str_replace(['"', "'"], ['', ''], $new_name);
            $new_name = $link_new->real_escape_string(mb_convert_encoding($new_name, 'latin1', 'UTF-8'));
            $link_new->query("UPDATE `{$GLOBALS['DB_NAME_OLD']}`.`Heste` SET `navn` = '{$new_name}' WHERE `id` = {$_GET['horse_id']} AND `bruger` = '{$username}'");
            $response['status'] = true;
            echo $_GET['callback'] . '(' . json_encode($response) . ')';
            exit();
        }
    }
    exit();
}
