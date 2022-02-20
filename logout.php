<?php
session_start();
require_once ('AllClass/logout.php');
$logout = new logout();
$logout->index('inc/login.inc.php');
