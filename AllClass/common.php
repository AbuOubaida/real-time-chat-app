<?php
/*******************************************************************************
                            Class Documentation
  #1. For making string for array value
      |--->#MakeString(providing Array)
  #2. For making string single value
      |--->#MakeSingleString(single value)=> return non zero/string with 'value'
  #3. String Encrypt
      |--->#Encrypt(string value)=> return non zero/encoded string
  #4. String Decrypt
      |--->#Decrypt(string value)=> return non zero/decoded string
  #5. For see client uses operating system
      |--->#GetOS()=> return nonzero/name of operating system
  #6. For see client uses browser name
      |--->#GetBrowser()=> return nonzero/name of browser
  #7. For see client uses Ip address
      |--->#Get_client_ip()=> return nonzero/Ip address
  #8. For insert data in DB
      |--->#Insert($tableName, $key, $value)=>return zero (0)/ one (1)
  #9. For set Cookies
      |--->#setCookies($dataInArray, $timeByDay)=>return zero (0)/ one (1)
  #10 For key encrypt
      |--->EncryptKey($value/$array_value) => return array|int|null(encrypted value)
  #11 For key decrypt
      |--->DecryptKey($value/$array_value) => return array|int|null(decrypted value)
  #12. For Check User Login session is set or not
      |-->CheckLoginSession($errorURL, $successURL)
  #13. For unset cookies
      |--->unsetCookies($keyArray, $reference)=>return int
  #14. For header sent
      |--->SentHeaderLocation($redirectPath)=>return int
 #15. For making redirect url
      |--->MakeRedirectURL($redirectPath)=>return int|string
 #16. For fetch data in a table
      |--->FetchData($tableName, $select, $condition, $orderRow, $order)=>return array|int
 #17. For set user is online
      |--->SetOnlineStatus($tableName, $keyArray, $condition)
/******************************************************************************/

require_once ('db_class.php');
class common extends DB_Class
{
    public $rootFolder = '/chate/';
//  #1. For making string for array
    /**
     * @param $array_value
     * @return bool
     */
    public function MakeString($array_value)
    {
        if(is_array($array_value)!== true)
        {
            return false;
            exit;
        }
        $value=$array_value;
        for($i=0;($i<count($value));$i++)
            $value[$i] = "'".$value[$i]."'";
        return $value;
    }

//  #2. For making string for single value
    /**
     * @param $value
     * @return int|string
     */
    public function MakeSingleString($value)
    {
        if($value === null)
        {
            return 0;
            exit;
        }
        $value = "'".$value."'";
        return $value;
        exit;
    }
//  #3. String Encrypt
    /**
     * @param $string
     * @return int|string
     */
    public function Encrypt($string)
    {
        if(empty($string))
        {
            return 0;
            exit;
        }
        return '$_a5c6b3d'.base64_encode(gzcompress(convert_uuencode(gzencode(bin2hex($string))),'8'));
        exit;
    }
//  #4. String Decrypt
    /**
     * @param $string
     * @return false|int|string
     */
    public function Decrypt($string)
    {
        if(empty($string))
        {
            return null;
            exit;
        }
        return @hex2bin(gzdecode(convert_uudecode(gzuncompress(base64_decode(str_replace('$_a5c6b3d','',$string))))));
        exit;
    }
//  #5. For see client uses operating system
    /**
     * @return mixed|string
     */
    public function GetOS()
    {
        global $user_agent;
        $os_platform  = 'Unknown OS Platform';
        $os_array     = array(
            '/windows nt 10/i'      =>  'Windows 10',
            '/windows nt 6.3/i'     =>  'Windows 8.1',
            '/windows nt 6.2/i'     =>  'Windows 8',
            '/windows nt 6.1/i'     =>  'Windows 7',
            '/windows nt 6.0/i'     =>  'Windows Vista',
            '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
            '/windows nt 5.1/i'     =>  'Windows XP',
            '/windows xp/i'         =>  'Windows XP',
            '/windows nt 5.0/i'     =>  'Windows 2000',
            '/windows me/i'         =>  'Windows ME',
            '/win98/i'              =>  'Windows 98',
            '/win95/i'              =>  'Windows 95',
            '/win16/i'              =>  'Windows 3.11',
            '/macintosh|mac os x/i' =>  'Mac OS X',
            '/mac_powerpc/i'        =>  'Mac OS 9',
            '/linux/i'              =>  'Linux',
            '/ubuntu/i'             =>  'Ubuntu',
            '/iphone/i'             =>  'iPhone',
            '/ipod/i'               =>  'iPod',
            '/ipad/i'               =>  'iPad',
            '/android/i'            =>  'Android',
            '/blackberry/i'         =>  'BlackBerry',
            '/webos/i'              =>  'Mobile'
        );

        foreach ($os_array as $regex => $value) if (preg_match($regex, $user_agent)) $os_platform = $value;

        return $os_platform;
    }
//  #6. For see client uses browser name
    /**
     * @return mixed|string
     */
    public function GetBrowser()
    {

        global $user_agent;

        $browser        = 'Unknown Browser';

        $browser_array = array(
            '/msie/i'      => 'Internet Explorer',
            '/firefox/i'   => 'Firefox',
            '/safari/i'    => 'Safari',
            '/chrome/i'    => 'Chrome',
            '/edge/i'      => 'Edge',
            '/opera/i'     => 'Opera',
            '/netscape/i'  => 'Netscape',
            '/maxthon/i'   => 'Maxthon',
            '/konqueror/i' => 'Konqueror',
            '/mobile/i'    => 'Handheld Browser'
        );

        foreach ($browser_array as $regex => $value) if (preg_match($regex, $user_agent)) $browser = $value;

        return $browser;
    }
//  #7. For see client uses Ip address
    /**
     * @return array|false|string
     */
    public function Get_client_ip()
    {
        if (getenv('HTTP_CLIENT_IP')) $ipAddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR')) $ipAddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED')) $ipAddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR')) $ipAddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED')) $ipAddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR')) $ipAddress = getenv('REMOTE_ADDR');
        else $ipAddress = 'UNKNOWN';
        return $ipAddress;
    }
//  #8. For insert data in DB
    /**
     * @param $tableName
     * @param $key
     * @param $value
     * @return bool|int
     */
    public function Insert($tableName, $key, $value)
    {
        if(empty($tableName) || empty($key) || empty($value) ||(count($key) !== count($value)))
        {
            return 0;
            exit;
        }
        $newKey[] = null;
        $i = 0;
        foreach ($key as $k)
        {
            $newKey[$i] = ':'.$k;
            $i++;
        }
        $data = array_combine($newKey,$value);
        $query = 'INSERT INTO ' .$tableName. ' (' .implode(', ',$key). ')VALUES (' .implode(', ',$newKey). ')';
        $query = htmlspecialchars($query);
        $statement = $this->conn->prepare($query);
        return $statement->execute($data);
        exit;
    }
//  #9. For set Cookies
    /**
     * @param $dataInArray
     * @param $timeByDay
     * @return int
     */
    public function setCookies($dataInArray, $timeByDay): ?int
    {
        if(empty($dataInArray) || empty($timeByDay))
        {
            return 0;
            exit;
        }
        $arrayNO 	= count($dataInArray);
        $key 		= array_keys($dataInArray);
        $value 		= array_values($dataInArray);
        var_dump(time());
        for($i=0;$i<$arrayNO;$i++)
            setcookie($key[$i],$value[$i],time()+60*60*24*$timeByDay);
        return 1;
        exit;
    }
//  #10 For key encrypt
    /**
     * @param $value
     * @return array|int|null
     */
    public function EncryptKey($value)
    {
        $result = null;
        if(empty($value))
        {
            return 0;
            exit;
        }
        if(is_array($value))
        {
            $result[]=null;
            $i=0;
            foreach ($value as $v)
            {
                $result[$i] = bin2hex($v);
                $result[$i] = str_replace('a','S',$result[$i]);
                $result[$i] = str_replace('b','E',$result[$i]);
                $result[$i] = str_replace('c','X',$result[$i]);
                $result[$i] = str_replace('d','F',$result[$i]);
                $result[$i] = str_replace('e','U',$result[$i]);
                $result[$i] = str_replace('f','C',$result[$i]);
                $i++;
            }
            return $result;
            exit();
        }

        $result = bin2hex($value);
        $result = str_replace(array('a', 'b', 'c', 'd', 'e', 'f'), array('S', 'E', 'X', 'F', 'U', 'C'), $result);
        return $result;
        exit();
    }
//  #11 For key decrypt
    /**
     * @param $value
     * @return array|int|null
     */
    public function DecryptKey($value)
    {
        $result = null;
        if(empty($value))
        {
            return 0;
            exit;
        }
        if(is_array($value))
        {
            $result[]=null;
            $i=0;
            foreach ($value as $v)
            {
                $result[$i] = $v;
                $result[$i] = str_replace('S','a',$result[$i]);
                $result[$i] = str_replace('E','b',$result[$i]);
                $result[$i] = str_replace('X','c',$result[$i]);
                $result[$i] = str_replace('F','d',$result[$i]);
                $result[$i] = str_replace('U','e',$result[$i]);
                $result[$i] = str_replace('C','f',$result[$i]);
                $result[$i] = hex2bin($result[$i]);
                $i++;
            }
            return $result;
            exit();
        }

        $result = $value;
        $result = str_replace(array('S', 'E', 'X', 'F', 'U', 'C'), array('a', 'b', 'c', 'd', 'e', 'f'), $result);
        $result = hex2bin($result);
        return $result;
        exit();
    }
//  #12. For Check User Login session is set or not
    /**
     * @param $errorURL
     * @param $successURL
     */
    public function CheckLoginSession($errorURL)
    {
        if (empty($errorURL))
            $errorURL = 'login';
        if (!isset($_SESSION['login']) && empty($_SESSION['login']))
        {
            $this->SentHeaderLocation($errorURL);
            exit();
        }
    }
//  #13. For unset cookies
    /**
     * @param $keyArray
     * @param $reference
     * @return int
     */
    public function unsetCookies($keyArray, $reference)
    {
        if(empty($keyArray) && ($reference === 'login' || $reference === 'logout'))
            $keyArray = $this->EncryptKey(['_0', '_9']);
        if(empty($keyArray))
        {
            return 0;
            exit;
        }
        $value[]=null;
        for ($i=0; ($i<count($keyArray)); $i++)
        {
            $value[$i] = null;
            setcookie($keyArray[$i],$value[$i],60*60*24*30-time());
            unset($_COOKIE[$keyArray[$i]]);
        }
        return 1;
        exit();
    }
//  #14. For header sent
    /**
     * @param $redirectPath
     * @return int
     */
    public function SentHeaderLocation($redirectPath)
    {
        if (empty($redirectPath))
        {
            return 0;
            exit();
        }
        if(empty($this->rootFolder))
            $this->rootFolder= null;
        $redirectPath = $this->MakeRedirectURL($redirectPath);
        if (headers_sent())
        {
            echo '<script>window.location.href="'.$redirectPath.'"</script>';
            exit();
        }
        header('Location:'.$redirectPath);
        exit();
    }
//  #15. For making redirect url

    /**
     * @param $redirectPath
     * @return int|string
     */
    public function MakeRedirectURL($redirectPath)
    {
        if(empty($redirectPath))
        {
            return 0;
            exit();
        }
        return $_SERVER['HTTP_ORIGIN'].$this->rootFolder.$redirectPath;
        exit();
    }
//  #16. For fetch data in a table
    /**
     * @param $tableName
     * @param $select
     * @param $condition
     * @param $orderRow
     * @param $order
     * @return array|int
     */
    public function FetchData($tableName, $select, $condition, $orderRow, $order)
    {
        if (empty($tableName))
        {
            return 0;
            exit();
        }
        if (empty($select))
            $select = '*';
        if (empty($orderRow) || empty($order))
            $order ='';
        else $order=" ORDER by {$orderRow} {$order}";
        if (empty($condition))
            $condition='';
        else $condition=' WHERE '.$condition;
        $query = 'SELECT '.$select.' FROM '.$tableName.$condition.$order;
        $statement = $this->conn->query($query);
        return $statement->fetchAll();
    }
//  #17. For set user is online
    public function SetOnlineStatus($tableName, $keyArray, $condition)
    {
        if (empty($tableName) || empty($keyArray) || empty($condition))
        {
            return 0;
            exit();
        }
        if (!is_array($keyArray))
        {
            return 'key expect must be array';
            exit();
        }
        $valueArray = [date('Y-m-d H:i:s')];
        return $this->Update($tableName,$keyArray,$valueArray,$condition);
        exit();
    }
//  #18. For Update table in DB
    public function Update($tableName, $keyArray, $valueArray, $condition)
    {
        if (empty($tableName) || empty($keyArray) || empty($valueArray) || empty($condition))
        {
            return 0;
            exit();
        }
        if (!is_array($keyArray) || !is_array($valueArray))
        {
            return 'key and value expect must be array';
            exit();
        }
        if (count($keyArray) !== count($valueArray))
        {
            return 'key and value length expect must be equal';
            exit();
        }
        $query = 'UPDATE '.$tableName.' SET '.$this->KeyEqualValueInString($keyArray,$valueArray,', ','s').' WHERE '.$condition;
        return $this->conn->query($query);
        exit();
    }
//  #19. For making key equal value in string
    /**
     * @param $keyArray
     * @param $valueArray
     * @param $separateSign
     * @param $returnTypeStringOrArray
     * @return array|int|string
     */
    public function KeyEqualValueInString($keyArray, $valueArray, $separateSign, $returnTypeStringOrArray)
    {
        if (empty($keyArray) || empty($valueArray))
        {
            return 0;
            exit();
        }
        if (count($keyArray) !== count($valueArray))
        {
            return 'key and value length expect must be equal';
            exit();
        }
        if (!empty($returnTypeStringOrArray) && ($returnTypeStringOrArray === 'string' || $returnTypeStringOrArray === 'STRING' || $returnTypeStringOrArray === 'String' || $returnTypeStringOrArray ==='S' || $returnTypeStringOrArray === 's'))
        {
         if (empty($separateSign))
             $sprs = ', ';
         else$sprs = $separateSign;
         $output[] = null;
         for ($i=0; ($i<count($keyArray)); $i++)
             $output[$i] = $keyArray[$i]." = '".$valueArray[$i]."'";
         return implode($sprs, $output);
        }
        if (empty($returnTypeStringOrArray) || ($returnTypeStringOrArray === 'array' || $returnTypeStringOrArray === 'ARRAY' || $returnTypeStringOrArray === 'Array' || $returnTypeStringOrArray ==='A' || $returnTypeStringOrArray === 'a'))
        {
            $output[] = null;
            for ($i=0; ($i<count($keyArray)); $i++)
                $output[$i] = $keyArray[$i]." = '".$valueArray[$i]."'";
            return $output;
        }
        exit();
    }
//  #20 For fetch User Last activity
    public function FetchUserLastActivity($tableName, $condition, $orderRow, $order, $limit)
    {
        if (empty($tableName) || empty($condition) || empty($orderRow) || empty($order) || empty($limit))
        {
            return 0;
            exit();
        }
        $result = $this->FetchDataByLimit($tableName,'*',$condition,$orderRow,$order,$limit,'');
        foreach ($result as $row)
        {
            return $row['last_activity'];
        }
    }
//  #21. For fetch data in a table By Limit
    /**
     * @param $tableName
     * @param $select
     * @param $condition
     * @param $orderRow
     * @param $order
     * @param $limit
     * @param $offset
     * @return array|int
     */
    public function FetchDataByLimit($tableName, $select, $condition, $orderRow, $order, $limit, $offset)
    {
        if (empty($tableName) || empty($orderRow) || empty($order) || empty($limit))
        {
            return 0;
            exit();
        }
        if (empty($select))
            $select = '*';
        if (empty($condition))
            $condition='';
        else $condition=' WHERE '.$condition;
        if (empty($offset))
            $offset ='';
        else $offset= ' offset '.$offset;
        $order=" ORDER by {$orderRow} {$order} LIMIT {$limit} {$offset}";
        $query = 'SELECT '.$select.' FROM '.$tableName.$condition.$order;
        $statement = $this->conn->query($query);
        return $statement->fetchAll();
    }
    #22. For Create table
    public function CreateTable($tableName, $collNameArray, $datatypeArray, $primaryKeyName)
    {
        if (empty($tableName) || empty($collNameArray) || empty($datatypeArray))
        {
            return 0;
            exit();
        }
        if (empty($primaryKeyName))
        {
            $primaryKeyName = $tableName.'_ID';
        }
        if(count($collNameArray) !== count($datatypeArray))
        {
            return 'Two array length are not same';
            exit();
        }
        $data = array_combine($collNameArray,$datatypeArray);
        $query = 'CREATE TABLE '.$tableName.'('.$primaryKeyName.' INT NOT NULL PRIMARY KEY AUTO_INCREMENT';
        foreach ($data as $k => $v)
        {
            if($v === 'v' || $v === 'V')
            {
                $v = 'VARCHAR(255)';
            }
            if($v === 'int' || $v === 'i' || $v ==='I')
            {
                $v = 'INT';
            }
            $query .= ', '.$k.' '.$v;
        }
        $query .= ')';
        return $this->conn->exec($query);
        exit();
    }

//  #23 Fof fetch user name
    public function FetchUserName($tableName, $userCollName, $condition)
    {
        if (empty($tableName) || empty($userCollName))
        {
            return 0;
            exit();
        }
        $result = $this->FetchData($tableName,$userCollName,$condition,'','');
        foreach ($result as $row)
        {
            return $row[$userCollName];
            exit();
        }
    }
//  #24. For row count
    /**
     * @param $tableName
     * @param $select
     * @param $condition
     * @return int|null
     */
    public function RowCount($tableName, $select, $condition)
    {
        if (empty($tableName))
        {
            return null;
            exit();
        }
        if(empty($select))
        {
            $select = '*';
        }
        if (empty($condition))
        {
            $condition = '';
        }
        else{
            $condition = 'WHERE '.$condition;
        }
        $query = "SELECT {$select} FROM {$tableName} {$condition}";
        $statement = $this->conn->query($query);
        return $statement->rowCount();
        exit();
    }
}








