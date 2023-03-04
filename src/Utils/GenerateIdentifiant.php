<?php

namespace App\Utils;

class GenerateIdentifiant
{
    public static function create($user){

        $first_name = strtoupper(substr($user->getFirstName(), 0, 1));
        $last_name = strtoupper(substr($user->getLastName(), 0, 3));
        $mobile = strtoupper(substr($user->getMobile(), 8, 9));

        $identifier = $first_name.$last_name.$mobile.random_int(1, 99);

        return $identifier;
    }
}