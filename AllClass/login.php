<?php
/*******************************************************************************
                            Class Documentation
  #1. For login and store login history
      |--->#MakeString(TableName,UserName or email,Password(DB form),[If any extraCondition by And. Else ''/null])=>return non zero(0)
  #2. For Store Login History
      |--->#LoginHistory($tableName, $ID)=> return non zero(0)
  #3. Remember me
      |--->RememberMe($email, $password, $time)=> return int
  #4. Login cookies check
      |--->CheckLoginCookies($redirectURL)=> no return type
  #5. Get cookies
      |--->GetCookies($errorURL, $successURL)=> no return type
  #6. Logout check
      |--->LogoutCheck($redirectPath)=> no return type
  #7. For isset login only for sign in page
      |--->IssetLogin($redirectURL)=> no return type
  #8. For If not submit and assess that page then Auto login credential
      |--->IfNotSubmit($submitName, $errorURL, $successURL)=> return int/no return type
 #9. For If submit and assess that page then login credential
      |--->IfSubmit($userName, $password, $remember, $tableName, $anyExtCond, $errorURL, $successURL)
*******************************************************************************/

require_once ('common.php');
class login extends common
{
//  #1. For login and store login history
    /**
     * @param $tableName
     * @param $userName
     * @param $password
     * @param $anyExtCond
     * @return int|string
     */
    public function index($tableName, $userName, $password, $anyExtCond)
    {
        if(empty($tableName) || empty($userName) || empty($password))
        {
            return 0;
            exit;
        }
        $tbName = htmlspecialchars($tableName);
        $name   = htmlspecialchars($userName);
        $pass   = htmlspecialchars($password);
        $cond   = '';
        if(!empty($anyExtCond)): $cond .= ' AND '.$anyExtCond.' ';endif;
        $query = 'SELECT * from '.$tbName.' WHERE 
            user_name = :username OR 
            email     = :email 
        '.$cond;
        $statement =  $this->conn->prepare($query);
        $statement -> execute(
           array(
               ':username'  =>  $name,
               ':email'     =>  $name
           )
        );
        $count = $statement->rowCount();
        if ($count > 0)
        {
            $result = $statement->fetchAll();
            foreach ($result as $row)
            {
                if(password_verify($pass, $row['password']))
                {
                    $_SESSION['login']['user_id'] = $this->Encrypt($row['id']);
                    $_SESSION['login']['user_email'] = $this->Encrypt($row['email']);
                    $_SESSION['login']['user_name'] = $this->Encrypt($row['user_name']);
                    $historyTable = $tableName.'_login_history';
                    $this->LoginHistory($historyTable, $row['id']);
                    $_SESSION['login']['last_history_id'] = $this->Encrypt($this->conn->lastInsertId());
                    return 1;
                    exit;
                }
                return 'Wrong Password';
                exit;
            }
        }
        else{
            return 'Invalid user';
            exit;
        }
    }
//  #2. For Store Login History
    /**
     * @param $tableName
     * @param $ID
     * @return bool|int
     */
    public function LoginHistory($tableName, $ID)
    {
        if (empty($tableName) || empty($ID))
        {
            return 0;
            exit;
        }
        $historyTable = $tableName;
        $query = 'SELECT * FROM '.$historyTable;
        $Statement = $this->conn->prepare($query);
        if(!$Statement->execute())
        {
            $createTable 	= "CREATE TABLE {$historyTable}(history_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, IP VARCHAR(100), client_ID INT, login_browser VARCHAR(255), login_os VARCHAR(255), login_date VARCHAR(255), login_time VARCHAR(255), last_activity DATETIME,logout_date VARCHAR(255), logout_time VARCHAR(255), login_status INT DEFAULT 1)";
            $this->conn->exec($createTable);
        }
        $key   = ['IP', 'client_ID', 'login_browser', 'login_os', 'login_date', 'login_time', 'last_activity'];
        $value = [$this->Get_client_ip(), $ID, $this->GetBrowser(), $this->GetOS(),date('Y-m-d h:i:s A'), date('h:i:s A'), date('Y-m-d h:i:s')];
        return $this->Insert($historyTable, $key, $value);
        exit;
    }
//  #3. Remember me
    /**
     * @param $email
     * @param $password
     * @param $time
     * @return int
     */
    public function RememberMe($email, $password, $time)
    {
        if(empty($email) || empty($password))
        {
         return 0;
         exit();
        }
        if(empty($time))
        {
            $time = '7';
        }
        $key = ['_0', '_9'];
        $key = $this->EncryptKey($key);
        $value = [$this->Encrypt($email), $this->Encrypt($password)];
        $data = array_combine($key, $value);
        return $this->setCookies($data,$time);
    }
//  #4. Login cookies check
    /**
     * @param $url
     */
    public function CheckLoginCookies($url)
    {
        if(empty($url))
        {
            $url = 'inc/login.inc.php';
        }
        if(!isset($_SESSION['checkCookies']) && empty($_SESSION['checkCookies']))
        {
            $_SESSION['get_cookies']=1;
            if (headers_sent())
            {
                echo "<script>window.location.href='".$url."'</script>";
                exit;
            }
            header('Location:'.$url);
            exit();
        }
    }
//  #5. Get cookies
    /**
     * @param $errorURL
     * @param $successURL
     */
    public function GetCookies($errorURL, $successURL)
    {
        if (empty($errorURL))
        {
            $errorURL = 'login.php';
        }
        if (empty($successURL))
        {
            $successURL = 'index.php';
        }
        if (isset($_SESSION['get_cookies']) && !empty($_SESSION['get_cookies']) && $_SESSION['get_cookies'] === 1)
        {
            if (isset($_COOKIE[$this->EncryptKey('_0')], $_COOKIE[$this->EncryptKey('_9')]))
            {
                $email = $this->Decrypt($_COOKIE[$this->EncryptKey('_0')]);
                $password = $this->Decrypt($_COOKIE[$this->EncryptKey('_9')]);
                $data  = $this->index('sing_up_list',$email,$password,'');
                if($data !== 1) {
                    $this->unsetCookies('','login');
                }
                $this->SentHeaderLocation($successURL);
            }
            $_SESSION['checkCookies']=true;
            $this->SentHeaderLocation($errorURL);
        }
    }
//  #6. Logout check

    /**
     * @param $redirectPath
     */
    public function LogoutCheck($redirectPath)
    {
        if (empty($redirectPath))
        {
            $redirectPath = '/login.php';
        }
        $redirectPath = @$_SERVER['HTTP_ORIGIN'].$this->rootFolder.$redirectPath;
        if (isset($_SESSION['logout']))
        {
            $keyArray = $this->EncryptKey(['_0', '_9']);
            if(isset($_COOKIE[$keyArray[0]],$_COOKIE[$keyArray[1]]) && !empty($_COOKIE[$keyArray[0]]) && !empty($_COOKIE[$keyArray[1]]))
            {
                $this->unsetCookies($keyArray,'logout');
            }
            unset($_SESSION['logout']);
            if (headers_sent())
            {
                echo '<script>window.location.href="'.$redirectPath.'"</script>';
            }
            else {
                header('Location:'.$redirectPath);
            }
            exit();
        }
    }
//  #7. For isset login only for sign in page
    /**
     * @param $redirectURL
     */
    public function IssetLogin($redirectURL)
    {
        if (empty($redirectURL))
        {
            $redirectURL = 'index.php';
        }
        if (isset($_SESSION['login']) && !empty($_SESSION['login']))
        {
            $this->SentHeaderLocation($redirectURL);
            exit();
        }
    }
//  #8. For If not submit and assess that page then Auto login credential
    /**
     * @param $submitName
     * @param $errorURL
     * @param $successURL
     * @return int
     */
    public function IfNotSubmit($submitName, $errorURL, $successURL)
    {
        if (empty($submitName))
        {
            return 0;
            exit();
        }
        if (empty($errorURL))
        {
            $errorURL = 'login.php';
        }
        if (empty($successURL))
        {
            $successURL = 'index.php';
        }
        if (!isset($_POST[$submitName])) {
            //    For unset cookies from logout
            $this->LogoutCheck($errorURL);// check logout or not
            $this->GetCookies($errorURL, $successURL);
            $this->SentHeaderLocation($errorURL);
            exit();
        }
    }
//  #9. For If submit and assess that page then login credential
    /**
     * @param $userName
     * @param $password
     * @param $remember
     * @param $tableName
     * @param $anyExtCond
     * @param $errorURL
     * @param $successURL
     * @return int
     */
    public function IfSubmit($userName, $password, $remember, $tableName, $anyExtCond, $errorURL, $successURL)
    {
        if (empty($userName) || empty($password) || empty($tableName))
        {
            return 0;
            exit();
        }
        if (empty($errorURL))
        {
            $errorURL = 'login.php';
        }
        if (empty($successURL))
        {
            $successURL = 'index.php';
        }
        $data  = $this->index($tableName,$userName,$password,$anyExtCond);
        if ($data === 0) {
            $_SESSION['message']['error'] = 'Something wont wrong!';
            $this->SentHeaderLocation($errorURL.'?user='.$userName);
            exit();
        }
        else if($data !== 1) {
            $_SESSION['message']['error'] = $data;
            $this->SentHeaderLocation($errorURL.'?user='.$userName);
            exit();
        }
        else {
        //if remember option has
            if ($remember === 'on')
            {
                $this->RememberMe($userName,$password,'30');
            }
        //--------------------------
            $this->SentHeaderLocation($successURL);
            exit();
        }
    }
}