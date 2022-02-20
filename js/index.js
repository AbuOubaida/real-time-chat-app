$(document).ready(function () {
    fetch_user()
    setInterval(function () {
        update_last_activity()
        fetch_user()
        update_chat_history_data()
    }, 2000)
    $.ajax({
        url:'ajax_file/auto_chat_box_open.ajx.php',
        method:'POST',
        success:function (data) {
            var a = data.split('|')
            alert(a)
            // make_chat_dialog_box(a[0],a[1])
        }
    })
    function fetch_user()
    {
        $.ajax({
            url:'ajax_file/fetch_user.ajx.php',
            method:'POST',
            success:function (data) {
                $('#user_details').html(data)
            }
        })
    }
    function update_last_activity()
    {
        $.ajax({
            url: 'ajax_file/update_last_activity.ajx.php',
            method: 'POST',
            success:function (data) {

            }
        })
    }
    function make_chat_dialog_box(to_user_id,to_user_name)
    {
        let modal_content = '<div id="user_dialog_' + to_user_id + '" class="user_dialog" title="You have chat with ' + to_user_name + '">';
        // modal_content += '<button type="button" class="ui-button ui-corner-all ui-widget ui-button-icon-only " title="Close"><span class="ui-button-icon ui-icon ui-icon-closethick"></span><span class="ui-button-icon-space"> </span>Close</button>'
        modal_content += '<div style="height: 300px; border: 1px solid #ccc; overflow-y: scroll; margin-bottom: 24px; padding: 16px;" class="chat_history" data-touserid="'+to_user_id+'" id="chat_history_'+to_user_id+'">'
        modal_content += fetch_user_chat_history(to_user_id)
        modal_content += '</div>'
        modal_content += '<div class="form-group">'
        modal_content += '<textarea name="chat_message_'+to_user_id+'" id="chat_message_'+to_user_id+'" class="form-control"></textarea>'
        modal_content += '</div><div class="form-group" align="right">'
        modal_content += '<button type="button" name="send_chat" id="'+to_user_id+'" class="btn btn-info send_chat">Send</button></div></div>'
        $('#user_model_details').html(modal_content)
    }
    $(document).on('click','.start_chat', function(){
        let to_user_id = $(this).data('touserid');
        let to_user_name = $(this).data(('tousername'));
        $.ajax({
            url:"ajax_file/start_chat_session.ajx.php",
            method:"POST",
            data:{to_user_id:to_user_id,to_user_name:to_user_name},
            success:function (data) {
                make_chat_dialog_box(to_user_id, to_user_name)
                $('#user_dialog_'+to_user_id).dialog({
                    autoOpen:false,
                    width: 400
                })
                $('#user_dialog_'+to_user_id).dialog('open')
            }
        })
    })
    $(document).on('click','.send_chat', function () {
        const to_user_id = $(this).attr('id')
        const chat_message = $('#chat_message_' + to_user_id).val()
        $.ajax({
            url:"ajax_file/insert_chat.ajx.php",
            method:"POST",
            data:{to_user_id:to_user_id,chat_message:chat_message},
            success:function (data)
            {
                $('#chat_message_' + to_user_id).val('')
                $('#chat_history_' + to_user_id).html(data)
            }
        })
    })
    function fetch_user_chat_history(to_user_id)
    {
        $.ajax({
            url:'ajax_file/fetch_user_chat_history.ajx.php',
            method:'POST',
            data:{to_user_id:to_user_id},
            success:function(data)
            {
                $('#chat_history_'+to_user_id).html(data)
            }
        })
        return ''
    }
    function update_chat_history_data()
    {
        $('.chat_history').each(function () {
            var to_user_id = $(this).data('touserid')
            fetch_user_chat_history(to_user_id)
        })
    }
})
