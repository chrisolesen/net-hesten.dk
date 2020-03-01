<?php

class opinions {

    public static function get_pool($attr = []) {
        global $link_new;
        $return_data = [];
        $defaults = [];
        foreach ($defaults as $key => $value) {
            isset($attr[$key]) ? : $attr[$key] = $value;
        }

        /* game_date_opinion_pools */
        /* Status 15 = live, Status 16 = ended */
        /* Fields = id, status, content (json), start_date, end_date */
        
        if (!empty($return_data)) {
            return $return_data;
        } else {
            return false;
        }
    }

}
