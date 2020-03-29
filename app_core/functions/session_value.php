<?php

function session_value($attr)
{
    if (!isset($attr['group'])) {
        $attr['group'] = false;
    }
    if (!isset($attr['mode'])) {
        $attr['mode'] = 'single';
    }
    if (isset($_SESSION)) {
        if ($attr['group']) {
            if (isset($_SESSION[$attr['group']])) {
                if (isset($_SESSION[$attr['group']][$attr['name']])) {
                    return $_SESSION[$attr['group']][$attr['name']];
                }
            }
        } else {
            if (isset($_SESSION[$attr['name']])) {
                return $_SESSION[$attr['name']];
            }
        }
    }
    if ($attr['mode'] == 'array') {
        return [false];
    } else {
        return false;
    }
}
