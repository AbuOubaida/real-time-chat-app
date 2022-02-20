<?php
/*********************************************************************************
                            Class Documentation
  #1. For Store Connection In A Public Variable
      |---># $conn
  #2. For Make Database Connection In Constructor
  #3. For Close Database Connection In Destructor
********************************************************************************/

date_default_timezone_set("Asia/Dhaka"); //Time Zone setup
$user_agent = $_SERVER['HTTP_USER_AGENT'];

class DB_Class
{
//  #1. For Store Connection In A Public Variable
    public $conn;

//  #2. For Make Database Connection In Constructor
    /**
     * DB_Class constructor.
     */
    public function __construct()
    {
        $this->conn = new PDO('mysql:host=localhost;dbname=chat_system', 'root', '');
    }

//  #3. For Close Database Connection In Destructor
    public function __destruct()
    {
        // TODO: Implement __destruct() method.
        $this->conn = null;
    }
}
