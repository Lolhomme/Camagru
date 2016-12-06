<?php
/**
 * Created by PhpStorm.
 * User: alaulom
 * Date: 11/10/16
 * Time: 3:41 PM
 */
namespace check;

use objet\user;

// utilisation objet foireurse

include '../config/setup.php';


class checkUser
{
    public function registerAction
    {
        $user = new user();

        if (isset($_POST) && !empty($_POST))
        {
            $form_is_valid = TRUE;

            $user->setUsername($_POST['username']);
            $user->setEmail($_POST['email']);

        }
    }
}