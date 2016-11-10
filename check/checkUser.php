<?php
/**
 * Created by PhpStorm.
 * User: alaulom
 * Date: 11/10/16
 * Time: 3:41 PM
 */
namespace check;

use objet\User;

// utilisation objet foireurse

public function validSignUp($id = null, $hash = null)
{
    if ($id = null || $hash = null || $id < 0)
        echo "champs obligatoires";
}