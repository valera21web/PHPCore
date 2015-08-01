<?php
namespace lib;

class DB extends \mysqli
{
    private $result = null;
    private $mysqli = null;
    private static $host;
    private static $database;
    private static $user;
    private static $password;
    private $SETTINGS;


    /**
     * @param string $_host - host of the server DataBase
     * @param string $_database - name of the DataBase
     * @param string $_user - user for this DataBase
     * @param string $_password - password of user
     */
    public function __construct($_host = null, $_database = null, $_user = null, $_password = null)
    {
        if($_host !== null)
        {
            DB::$host = $_host;
            DB::$user = $_user;
            DB::$password = $_password;
            DB::$database = $_database;
        } else {
            if(DB::$host == null)
            {
                $path = getenv("DOCUMENT_ROOT");
                $this->SETTINGS = simplexml_load_file($path."/config.xml");
                DB::$host = (String) $this->SETTINGS->database->host;
                DB::$user = (String) $this->SETTINGS->database->user_name;
                DB::$password = (String) $this->SETTINGS->database->password;
                DB::$database = (String) $this->SETTINGS->database->db_name;
            }
        }
        try{
            parent::__construct(DB::$host, DB::$user, DB::$password, DB::$database);
            if ($this->connect_errno)
                die($this->connect_error);
            $this->set_charset("utf8");
        } catch(\Exception $d)  {
            die($this->connect_error);
        }
    }

    public function db_query($query, $type_return = 'non', $multi_query = false)
    {
        $this->result = null;
        if ($multi_query) {
            $this->result = $this->multi_query($query);
        } else {
            $this->result = $this->query($query);
        }

        if(!$this->result) die($this->error);

        if($type_return == 'non')
        {
            return $this->result;
        } else {
            switch($type_return)
            {
                case 'row':
                    return !!$multi_query ? $this->db_multi_fetch_row() : $this->db_fetch_row() ;
                    break;

                case 'assoc':
                    return !!$multi_query ? $this->db_multi_fetch_assoc() : $this->db_fetch_assoc() ;
                    break;

                default:
                    return $this->result;
                    break;
            }
        }
    }

    private function db_fetch_assoc()
    {
        $return = array();
        $result = $this->result;
        while($row = $result->fetch_assoc())
        {
            $return[] = $row;
        }
        return $return;
    }

    private function db_multi_fetch_assoc()
    {
        $return = array();
        $i = 0;
        do
        {
            if ($result = $this->store_result())
            {
                while ($row = $result->fetch_assoc())
                {
                    $return[$i][] = $row;
                }
                $result->free();
            }
            ++$i;
        } while ($this->more_results() && $this->next_result());
        return $return;
    }

    private function db_fetch_row()
    {
        $return = array();
        $result = $this->result;
        while($row = $result->fetch_row())
        {
            $return[] = $row;
        }
        return $return;
    }

    private function db_multi_fetch_row()
    {
        $return = array();
        $i = 0;
        do {
            if ($result = $this->store_result())
            {
                while ($row = $result->fetch_row())
                {
                    $return[$i][] = $row;
                }
                $result->free();
            }
            ++$i;
        } while ($this->more_results() && $this->next_result());
        return $return;
    }

    public function mysqli_insert_id()
    {
        return $this->insert_id;
    }
}
