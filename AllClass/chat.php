<?php
require_once ('common.php');

class chat extends common
{
//  #1. For store chat message
    /**
     * @param $ToID
     * @param $message
     * @param $messageType
     * @param $If_Img_DDR_OP
     * @return int
     */
    public function Index($ToID, $message, $messageType, $If_Img_DDR_OP)
    {
        if (empty($ToID) || empty($message) || empty($messageType))
        {
            return 0;
            exit();
        }
        if ($messageType === 'img' || $messageType ==='image')
        {
            if (empty($If_Img_DDR_OP))
            {
                return 0;
                exit();
            }
        }
        $result = null;
        $tableName = 'chat_message';
        $this->CheckTable($tableName);
        $key = ['from_id', 'to_id', 'message', 'messageType', 'status', 'timestamp'];
        $from_ID = $this->Decrypt($_SESSION['login']['user_id']);
        $to_ID = $this->DecryptKey($ToID);
        $val = [$from_ID, $to_ID, $this->Encrypt($message), $messageType,'1',date('Y-m-d h:i:s A')];
        if($this->Insert($tableName,$key,$val)) {
            $result = $this->fetchChatHistory($tableName, $from_ID, $to_ID);
        }
        return $result;
        exit();
    }

//  #2. For Check table is exit or not
    /**
     * @param $tableName
     * @return false|int|string
     */
    public function CheckTable($tableName)
    {
        if (empty($tableName))
        {
            return 0;
            exit();
        }
        $query = 'SELECT * FROM '.$tableName;
        $statement = $this->conn->prepare($query);
        if(!$statement->execute())
        {
            $coll = ['from_id', 'to_id', 'message', 'messageType', 'Img_DDR','status', 'timestamp'];
            $type = ['int', 'int', 'text', 'v', 'v','i','v'];
            return $this->CreateTable($tableName,$coll,$type,'message_id');
            exit();
        }
    }

//  #3. For display chat message
    /**
     * @param $tableName
     * @param $from_user_id
     * @param $to_user_id
     * @return array|int
     */
    public function fetchChatHistory($tableName, $from_user_id, $to_user_id)
    {
        if(empty($tableName) || empty($from_user_id) || empty($to_user_id))
        {
            return 0;
            exit();
        }
        $this->CheckTable($tableName);
        $cond = "(from_id='".$from_user_id."' AND to_id='".$to_user_id."') OR (from_id='".$to_user_id."' AND to_id='".$from_user_id."')";
        $result = $this->FetchData($tableName,'*', $cond, 'timestamp', 'DESC');
        $keyArray = ['status'];
        $valueArray = ['0'];
        $condition = "from_id='".$to_user_id."' AND to_id='".$from_user_id." ' AND status='1'";
        $this->Update('chat_message',$keyArray,$valueArray,$condition);
        return $this->output($result, $from_user_id, $to_user_id);
        exit();
    }
//  #4. For Output
    /**
     * @param $resultArray
     * @param $from_user_id
     * @param $to_user_id
     * @return int|string|null
     */
    public function output($resultArray, $from_user_id, $to_user_id)
    {
        if(empty($resultArray) || empty($from_user_id) || empty($to_user_id) || !is_array($resultArray))
        {
            return 0;
            exit();
        }
        $output = null;
        $result = $resultArray;
        $from_ID = $from_user_id;
        $to_ID = $to_user_id;
        $output = '<ul class="list-unstyled">';
        foreach ($result as $row)
        {
            $userName = '';
            if ($row['from_id'] === $from_ID)
            {
                $userKey = 'you';
                $userName = '<b class="text-success d-block w-100 text-right">'.$userKey.'</b>';
            }
            else {
                $cond = "id='".$to_ID."'";
                $userKey = $this->FetchUserName('sing_up_list','user_name',$cond);
                $userName = '<b class="text-to">'.$userKey.'</b>';
            }
            if($row['messageType'] === 'text')
            {
                if ($userKey === 'you')
                {
                    $messageAlign = 'float-right messageYou';
                    $timeAlign = 'text-left';
                }
                else{
                    $messageAlign = 'float-left messageTo';
                    $timeAlign = 'text-right';
                }
                $output .= <<<EOD
<li style="border-bottom: 1px solid #cccccc">
{$userName}
<p class="m-0"><div class="{$messageAlign}">{$this->Decrypt($row['message'])}</div>
<div class="{$timeAlign}"><small><em>{$row['timestamp']}</em></small></div>
</p>
</li>
EOD;
            }
        }
        $output .= '</ul>';
        return $output;
        exit();
    }
//  #5. For Unseen message count

    /**
     * @param $from_user_id
     * @param $to_user_id
     * @return int|string
     */
    public function count_unseen_message($from_user_id, $to_user_id)
    {
        if (empty($from_user_id) || empty($to_user_id))
        {
            return 0;
            exit();
        }
        $cond = "from_id='".$from_user_id."' AND to_id='".$to_user_id."' AND status='1'";
        $count = $this->RowCount('chat_message','*',$cond);
        $output = '';
        if ($count > 0)
        {
            $output = <<<EOD
<span class="label label-success">{$count}</span>
EOD;
        }
        return $output;
        exit();
    }
}