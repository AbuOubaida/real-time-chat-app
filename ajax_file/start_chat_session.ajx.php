<?php
session_start();
$to_user_id = null;
$to_user_name = null;
extract($_POST,'0');
$_SESSION['chat'][$to_user_id] = $to_user_name;