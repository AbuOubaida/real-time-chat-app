<?php
session_start();
require_once('AllClass/common.php');
$common_cls = new common();
$common_cls->CheckLoginSession('login.php');// check is login ?
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Chatting System</title>
    <link rel="stylesheet" href="jquery-ui-themes-1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="bootstrap-4.1.2/bootstrap.min.css">
    <script src="js/jquery-1.12.4.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/index.js" type="text/javascript"></script>
    <script src="js/popper.js"></script>
    <script src="jquery-ui-1.12.1/jquery-ui.js"></script>
    <style>
        .messageYou {
            background: #97ad89;
            padding: .5px 5px;
            color: white;
            border-bottom-left-radius: 5px;
            border-bottom-right-radius: 5px;
            border-top-left-radius: 5px;
        }
        .messageTo {
            background: #89a6ad;
            padding: .5px 5px;
            color: white;
            border-bottom-left-radius: 5px;
            border-bottom-right-radius: 5px;
            border-top-right-radius: 5px;
        }
        .text-to {
            color: #9acfdc!important;
        }
    </style>
</head>
<body>
    <div class="container">
        <br>
        <h3 align="center">Chat Application using PHP Ajax</h3>
        <br>
        <div id="user_model_details" class="d-block w-100"></div>
        <div class="table-responsive">
            <h4 class="text-center">Online User</h4>
            <p class="text-right">Hi - <?php echo $common_cls->Decrypt($_SESSION['login']['user_name'])// for decrypt data?> <br><a
                        href="logout.php" title="logout">Logout</a></p>
            <div class="col-md-3 float-right">
                <div class="row float-right">
                    <div id="user_details" class="d-block w-100"></div>
                </div>
            </div>
            <?php
//            unset($_SESSION['chat']);
//            var_dump(@$_SESSION['chat']);
            ?>
        </div>
    </div>
</body>
<?php
//if (isset($_SESSION['chat']) && !empty($_SESSION['chat']))
//{
//    foreach ($_SESSION['chat'] as $k=>$v)
//    {
        ?>
<script>
    //$(document).ready(function() {
    //    let to_user_id = '<?php //echo $k;?>//';
    //    let to_user_name = '<?php //echo $v;?>//';
    //    $.ajax({
    //        url:"ajax_file/start_chat_session.ajx.php",
    //        method:"POST",
    //        data:{to_user_id:to_user_id,to_user_name:to_user_name},
    //        success:function (data) {
    //            make_chat_dialog_box(to_user_id, to_user_name)
    //            $('#user_dialog_'+to_user_id).dialog({
    //                autoOpen:false,
    //                width: 400
    //            })
    //            $('#user_dialog_'+to_user_id).dialog('open')
    //        }
    //    })
    //    function make_chat_dialog_box(to_user_id,to_user_name)
    //    {
    //        let modal_content = '<div id="user_dialog_' + to_user_id + '" class="user_dialog" title="You have chat with ' + to_user_name + '">';
    //        modal_content += '<div style="height: 300px; border: 1px solid #ccc; overflow-y: scroll; margin-bottom: 24px; padding: 16px;" class="chat_history" data-touserid="'+to_user_id+'" id="chat_history_'+to_user_id+'">'
    //        modal_content += fetch_user_chat_history(to_user_id)
    //        modal_content += '</div>'
    //        modal_content += '<div class="form-group">'
    //        modal_content += '<textarea name="chat_message_'+to_user_id+'" id="chat_message_'+to_user_id+'" class="form-control"></textarea>'
    //        modal_content += '</div><div class="form-group" align="right">'
    //        modal_content += '<button type="button" name="send_chat" id="'+to_user_id+'" class="btn btn-info send_chat">Send</button></div></div>'
    //        $('#user_model_details').html(modal_content)
    //    }
    //    function fetch_user_chat_history(to_user_id)
    //    {
    //        $.ajax({
    //            url:'ajax_file/fetch_user_chat_history.ajx.php',
    //            method:'POST',
    //            data:{to_user_id:to_user_id},
    //            success:function(data)
    //            {
    //                $('#chat_history_'+to_user_id).html(data)
    //            }
    //        })
    //        return ''
    //    }
    //  make_chat_dialog_box('<?php //echo $k;?>//','<?php //echo $v;?>//')
    //})
</script>
<?php
//    }
//}
?>
</html>