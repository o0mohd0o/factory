<?php

function roleName($name)
{
    $name = str_replace('_', ' ', $name);
    $name = ucwords($name);
    return __($name);
}
