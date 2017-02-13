<?php
function create_token(){
    session_start();
    $token = uniqid(rand(), true);
    if (!isset($_SESSION['token']) || !isset($_SESSION['token_time']) || empty($_SESSION['token']) || empty($_SESSION['token_time']))
    {
        $_SESSION['token'] = $token;
        $_SESSION['token_time'] = time();
        return $token;
    }
}

function check_token($temps, $referer){
    session_start();
    if (isset($_SESSION['token']) && isset($_SESSION['token_time']) && isset($_POST['token'])){
        if ($_SESSION['token'] ==  $_POST['token']){
            if ($_SESSION['token_time'] >= (time() - $temps)){
                if ($_SERVER['HTTP_REFERER'] == $referer)
                    return true;
            }
        }
    }
    return false;
}
?>