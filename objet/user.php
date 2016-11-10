<?php
/**
 * Created by PhpStorm.
 * User: alaulom
 * Date: 11/10/16
 * Time: 2:28 PM
 */
$user = new utilisateur;

class user
{
    private $id,
    private $username,
    private $email,
    private $password,
    private $is_admin,
    private $is_activ,
    private $hash,
    private $salt;
}