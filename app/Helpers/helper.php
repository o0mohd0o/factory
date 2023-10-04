<?php

function roleName($name)
{
    $name = str_replace('_', ' ', $name);
    $name = ucwords($name);
    return __($name);
}

function roundAndFormat($number, $precision =2, $decimals=2)  {
    return number_format(round($number, $precision), $decimals);
}
