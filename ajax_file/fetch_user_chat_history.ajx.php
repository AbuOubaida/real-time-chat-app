<?php
session_start();
require_once ('../AllClass/chat.php');
$to_user_id = null;
extract($_POST,'0');
$class = new chat();
$class->CheckLoginSession('login.php');
$from_user_id = $class->Decrypt($_SESSION['login']['user_id']);
$to_user_id = $class->DecryptKey($to_user_id);
$output = $class->fetchChatHistory('chat_message',$from_user_id,$to_user_id);
if (empty($output))
{
    echo null;
}
else{
    echo $output;
}