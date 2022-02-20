<?php
session_start();
require_once ('../AllClass/common.php');
$common_cls = new common();
$common_cls->CheckLoginSession('login.php');
$key = ['last_activity'];
$cond = "
    history_id ='".
    $common_cls->Decrypt($_SESSION['login']['last_history_id']).
    "'";
$result = $common_cls->SetOnlineStatus('sing_up_list_login_history',$key,$cond);
