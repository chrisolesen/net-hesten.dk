<?php

class accounting {

    public static function get_account_total($attr = []) {

        global $link_new;
        $return_data = [];
        $defaults = ['user_id' => (int) $_SESSION['user_id']];

        foreach ($defaults as $key => $value) {
            isset($attr[$key]) ?: $attr[$key] = $value;
        }

        foreach ($attr as $key => $value) {
            $attr[$key] = $link_new->real_escape_string($value);
        }
        $money = ($link_new->query("SELECT penge AS money FROM `{$GLOBALS['DB_NAME_OLD']}`.Brugere WHERE id = {$attr['user_id']} LIMIT 1")->fetch_object()->money);

        return $money;
    }

    public static function fetch_entries($attr = []) {

        global $link_new;
        $return_data = [];
        $defaults = ['user_id' => (int) $_SESSION['user_id']];

        foreach ($defaults as $key => $value) {
            isset($attr[$key]) ?: $attr[$key] = $value;
        }

        foreach ($attr as $key => $value) {
            $attr[$key] = $link_new->real_escape_string($value);
        }
        $sql = "SELECT "
                . "entry_date, "
                . "line_meta, "
                . "line_amount "
                . "FROM "
                . "{$GLOBALS['DB_NAME_NEW']}.game_data_accounting "
                . "WHERE "
                . "user_id = {$attr['user_id']} "
                . "ORDER BY "
                . "entry_date DESC "
                . "LIMIT 100";
        $result = $link_new->query($sql);
        while ($data = $result->fetch_object()) {
            $return_data[] = (object) ['date' => $data->entry_date, 'amount' => $data->line_amount, 'meta' => json_decode($data->line_meta)];
        }

        return (object) $return_data;
    }

    public static function add_entry($attr = []) {

        global $link_new;
        $return_data = [];
        $defaults = [
            'mode' => '-'
        ];
        if (isset($_SESSION) && isset($_SESSION['user_id'])) {
            $defaults['user_id'] = (int) $_SESSION['user_id'];
        } elseif (!isset($attr['user_id'])) {
            return false;
        }
        foreach ($defaults as $key => $value) {
            isset($attr[$key]) ?: $attr[$key] = $value;
        }
        foreach ($attr as $key => $value) {
            $attr[$key] = $link_new->real_escape_string($value);
        }
        /* Fetch current money */
        $money = ($link_new->query("SELECT penge AS money FROM `{$GLOBALS['DB_NAME_OLD']}`.Brugere WHERE id = {$attr['user_id']} LIMIT 1")->fetch_object()->money);
        $attr['meta_data']['operator'] = $attr['mode'];

        /* Update user money */
        if ($attr['mode'] === '+') {
            $sql = "UPDATE `{$GLOBALS['DB_NAME_OLD']}`.Brugere SET penge = (penge + {$attr['amount']}) WHERE id = {$attr['user_id']} ";
            $attr['meta_data']['line_total'] = $money + $attr['amount'];
        } else {
            $sql = "UPDATE `{$GLOBALS['DB_NAME_OLD']}`.Brugere SET penge = (penge - {$attr['amount']}) WHERE id = {$attr['user_id']} ";
            $attr['meta_data']['line_total'] = $money - $attr['amount'];
        }
        $link_new->query($sql);
        /* Create account list entry */
        $attr['meta_data']['line_text'] = $attr['line_text'];
        $meta_for_db = json_encode($attr['meta_data'], JSON_UNESCAPED_UNICODE);

        $sql = "INSERT INTO {$GLOBALS['DB_NAME_NEW']}.game_data_accounting "
                . "(user_id, line_meta, line_amount)"
                . "VALUES "
                . "({$attr['user_id']}, '{$meta_for_db}', {$attr['amount']}) "
                . "";
        $result = $link_new->query($sql);
        return $result;
    }

}
