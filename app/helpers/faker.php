<?php

namespace faker;

function generateRandomString($length = 10)
{
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ   ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; ++$i) {
        $randomString .= $characters[mt_rand(0, $charactersLength - 1)];
    }

    return $randomString;
}

function generateRandomFloat($min = 0, $max = 1, $decimalPlaces = 2)
{
    $number = $min + mt_rand() / mt_getrandmax() * ($max - $min);

    return number_format($number, $decimalPlaces, '.', '');
}

function generateRandomPictureUrl() {
    return 'uploads/default.png?'.uniqid();
}
