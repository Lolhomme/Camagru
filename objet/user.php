<?php
/**
 * Created by PhpStorm.
 * User: alaulom
 * Date: 11/10/16
 * Time: 2:28 PM
 */
namespace objet;

class User
{
    private $id,
    private $username,
    private $email,
    private $password,
    private $is_admin,
    private $is_activ,
    private $hash,
    private $salt;

//
    public function getId()
    {
        return $this->id;
    }
    public function setId($id)
    {
        $this->id = $id;
    }
//
    public function getUsername()
    {
        return $this->username;
    }
    public function setUsername($username)
    {
        $this->username = $username;
    }
//
    public function getEmail()
    {
        return $this->email;
    }
    public function setEmail($email)
    {
        $this->email = $email;
    }
//
    public function getPassword()
    {
        return $this->password;
    }
    public function setPassword($password)
    {
        $this->password = $password;
    }
//
    public function getIsAdmin()
    {
        return $this->is_admin;
    }
    public function setIsAdmin($is_admin)
    {
        $this->is_admin = $is_admin;
    }
//
    public function getIsActiv()
    {
        return $this->is_activ;
    }
    public function setIsActiv($is_activ)
    {
        $this->is_activ = $is_activ;
    }
//
    public function getHash()
    {
        return $this->hash;
    }
    public function setHash($hash)
    {
        $this->hash = $hash;
    }
//
    public function getSalt()
    {
        return $this->salt;
    }
    public function setSalt($salt)
    {
        $this->salt = $salt;
    }
}