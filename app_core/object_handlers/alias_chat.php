<?php

class alias_chat
{

    public static function post_message($attr = [])
    {
        global $link_new;
        global $link_new;
        $return_data = [];
        $defaults = [];

        foreach ($defaults as $key => $value) {
            isset($attr[$key]) ?: $attr[$key] = $value;
        }

        if ($attr['message'] == '') {
            return false;
        }

        foreach ($attr as $key => $value) {
            $attr[$key] = $link_new->real_escape_string($value);
        }

        $sql = "INSERT INTO game_data_alias_chat "
            . "(creator, alias, value) "
            . "VALUES "
            . "({$attr['poster_id']}, '{$attr['alias']}', '{$attr['message']}')";
        $result = $link_new->query($sql);

        if ($result) {
            $return_data[] = [true, 'Besked postet'];
            return $return_data;
        } else {
            return false;
        }
    }

    public static function get_messages($attr = [])
    {
        global $link_new;
        global $link_new;
        global $GLOBALS;
        $return_data = [];
        $defaults = ['limit' => 10];
        foreach ($defaults as $key => $value) {
            isset($attr[$key]) ?: $attr[$key] = $value;
        }
        $limit = '';
        if (isset($attr['limit']) && isset($attr['page']) && $attr['page'] != false) {
            $offset = $attr['limit'] * ((int)$attr['page'] - 1);
            $limit = "LIMIT {$attr['limit']} OFFSET {$offset}";
        }
        $sql = "SELECT "
            . "new.creation_date, "
            . "new.value, "
            . "new.alias_id "
            . "FROM "
            . "{$GLOBALS['DB_NAME_NEW']}.game_data_alias_chat AS new "
            . "WHERE "
            . "new.status_code <> 13 "
            . ($limit == '' ? "AND new.creation_date > DATE_SUB(NOW(),INTERVAL 2 DAY) " : '')
            . "ORDER BY "
            . "new.creation_date DESC "
            . "{$limit}";

        $result = $link_new->query($sql);
        while ($data = $result->fetch_object()) {
            $return_data[] = ['creator_id' => $data->creator_id, 'creator' => $data->creator, 'date' => $data->creation_date, 'text' => $data->value];
        }

        if (!empty($return_data)) {
            return $return_data;
        } else {
            return false;
        }
    }
}
