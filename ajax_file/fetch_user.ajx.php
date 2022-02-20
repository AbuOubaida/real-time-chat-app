<?php
session_start();
require_once ('../AllClass/common.php');
require_once ('../AllClass/chat.php');
$class = new common();
$chat = new chat();
$class->CheckLoginSession('login.php');
$condition ="id != '".$class->Decrypt($_SESSION['login']['user_id'])."'";
$result = $class->FetchData('sing_up_list','*',$condition,'','');
$output = <<<EOD
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Username</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
EOD;
foreach ($result as $row)
{
    $status = '';
    $current_timestamp = strtotime(date('Y-m-d H:i:s').'-10 second');
    $current_timestamp = date('Y-m-d H:i:s', $current_timestamp);
    $user_last_activity = $class->FetchUserLastActivity('sing_up_list_login_history',"client_ID='".$row['id']."'", 'last_activity', 'DESC', '1');
    if ($user_last_activity > $current_timestamp)
    {
        $status = '<span class="text-success">Online</span>';
    }
    else {
        $status = '<span class="text-danger">Offline</span>';
    }
    $output .= <<<EOD
        <tr>
            <td>{$row['user_name']} {$chat->count_unseen_message($row['id'], $chat->Decrypt($_SESSION['login']['user_id']))}</td>
            <td>{$status}</td>
            <td>
                <button class="btn btn-info btn-sm start_chat" data-touserid="{$class->EncryptKey($row['id'])}" data-tousername="{$row['user_name']}"> Start Chat</button>
            </td>
        </tr>
EOD;
}
$output .= <<<EOD
    </tbody>
</table>
EOD;
echo $output;