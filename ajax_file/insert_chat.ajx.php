<?php
session_start();
require_once ('../AllClass/chat.php');
$to_user_id = null;
$chat_message = null;
extract($_POST,'0');
$class = new chat();
$class->CheckLoginSession('login.php');
echo $class->Index($to_user_id, $chat_message, 'text', '');


