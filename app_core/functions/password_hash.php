<?php

function cbc_pwhash($password)
{
    $salt = uniqid('', true);
    $algo = '6';
    $rounds = '5042';
    $cryptSalt = '$' . $algo . '$rounds=' . $rounds . '$' . $salt;

    return crypt(trim($password), $cryptSalt);
}
