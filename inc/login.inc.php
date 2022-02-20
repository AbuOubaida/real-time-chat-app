<?php
session_start();
require_once ('../AllClass/login.php');
$email    = null;
$password = null;
$remember = null;
extract($_POST,'0');
// if not submit and access this page
$login = new login();
$login->IfNotSubmit('login','login.php', 'index.php');
//---------------------------
// if submit and access this page
$login->IfSubmit($email, $password, $remember, 'sing_up_list', '', 'login.php', 'index.php');
//----------------------------------


