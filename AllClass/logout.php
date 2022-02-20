<?php
require_once ('common.php');

class logout extends common
{
    public function index($url)
    {
        if (empty($url))
        {
            $url = 'inc/login.inc.php';
        }
        unset($_SESSION['login']);
        $_SESSION['logout'] = true;
        if (headers_sent())
        {
            echo '<script>window.location.href="'.$url.'"</script>';
        }
        else{
            header('Location:'.$url);
        }
    }
}