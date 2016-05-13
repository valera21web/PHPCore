<?php
namespace lib;

require_once (__DIR__ . DIRECTORY_SEPARATOR ."/CacheManager.php");

class DB extends \mysqli
{
    public static $KEY_CACHE_CONFIG = "KEY_CONFIG";
    private $result = null;
    private static $host;
    private static $database;
    private static $user;
    private static $password;
    private $SETTINGS;

    private $isConnected;

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
                if(\lib\CacheManager::exist(self::$KEY_CACHE_CONFIG))
                {
                    $this->SETTINGS = json_decode(\lib\CacheManager::get(self::$KEY_CACHE_CONFIG));
                }
                else
                {
                    $this->SETTINGS = simplexml_load_file(getenv("DOCUMENT_ROOT")."/config.xml");
                    \lib\CacheManager::set(self::$KEY_CACHE_CONFIG, json_encode($this->SETTINGS));
                }
                DB::$host = (String) $this->SETTINGS->database->host;
                DB::$user = (String) $this->SETTINGS->database->user_name;
                DB::$password = (String) $this->SETTINGS->database->password;
                DB::$database = (String) $this->SETTINGS->database->db_name;
            }
        }
        $this->isConnected = @parent::__construct(DB::$host, DB::$user, DB::$password, DB::$database);
        if ($this->connect_errno)
            throw new \mysqli_sql_exception($this->connect_errno .": ".$this->connect_error);
        $this->set_charset("utf8");
    }

    /**
     * @return mixed
     */
    public function getIsConnected()
    {
        return $this->isConnected;
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
}
