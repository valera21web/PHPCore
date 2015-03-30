<?php
namespace components;

use \lib\DB;
use \lib\Validation;

class FAQ  extends DB {
    private $DB_TABLE = "";
    private $TEMPLATE_FAQ = "";
    private $IS = false;

    public function __construct($DBTable, $templateFAQ)
    {
        parent::__construct();
        $this->DB_TABLE = $DBTable;
        if ($this->db_query("SELECT * FROM " . $DBTable ." LIMIT 1", "assoc"))
        {
            $this->IS = true;
        } else {
            throw new \mysqli_sql_exception("Hasn't TABLE[". $this->DB_TABLE ."] in database. ERROR[File:".__FILE__." Line:".__LINE__);
        }
        if(Validation::validName($templateFAQ))
        {
            $this->TEMPLATE_FAQ = $templateFAQ;
        }
    }

    public function get($NameFAQ, $templateFAQ = null)
    {
        if($this->IS && $NameFAQ != null && Validation::validName($NameFAQ))
        {
            $result = $this->db_query(""
                ." SELECT * FROM " . $this->DB_TABLE
                ." WHERE name_id = '". $this->escape_string($NameFAQ) ."'"
                ." AND `visible` = '1'"
                ." LIMIT 1"
                , "assoc");
            if(!empty($result['0']))
            {
                $templateFAQ = $templateFAQ == null ? $this->TEMPLATE_FAQ : $templateFAQ;
                return getTemplate($templateFAQ, $result['0']);
            } else {
                return "";
            }
        } else {
            throw new \mysqli_sql_exception("Hasn't TABLE[". $this->DB_TABLE ."]in database");
        }
    }

    public function getAll($templateFAQ = null)
    {
        if($this->IS)
        {
            $result = $this->db_query(""
                ." SELECT * FROM " . $this->DB_TABLE
                ." WHERE visible` = '1'"
                , "assoc");
            if(!empty($result))
            {
                $templateFAQ = $templateFAQ == null ? $this->TEMPLATE_FAQ : $templateFAQ;
                $string = "";
                foreach($result AS $row) {
                    $string .= getTemplate($templateFAQ, $row);
                }
                return $string;
            } else {
                return "";
            }
        } else {
            throw new \mysqli_sql_exception("Hasn't TABLE[". $this->DB_TABLE ."] in database");
        }
    }

    public function getInfo($NameFAQ)
    {
        if($this->IS && $NameFAQ != null && Validation::validName($NameFAQ))
        {
            $result = $this->db_query(""
                ." SELECT * FROM " . $this->DB_TABLE
                ." WHERE name_id = '". $this->escape_string($NameFAQ) ."'"
                ." AND `visible` = '1'"
                ." LIMIT 1"
                , "assoc");
            if(!empty($result['0']))
            {
                return $result['0'];
            } else {
                return null;
            }
        } else {
            throw new \mysqli_sql_exception("Hasn't TABLE[". $this->DB_TABLE ."]in database");
        }
    }

    public function getAllInfo()
    {
        if($this->IS)
        {
            $result = $this->db_query(""
                ." SELECT * FROM " . $this->DB_TABLE
                ." WHERE visible` = '1'"
                , "assoc");
            if(!empty($result))
            {
                return $result;
            } else {
                return null;
            }
        } else {
            throw new \mysqli_sql_exception("Hasn't TABLE[". $this->DB_TABLE ."] in database");
        }
    }

    public function add($values)
    {
        if($this->IS)
        {
            $line = __LINE__ + 1;
            if(!empty($values['name_id']) && isset($values['question']) && isset($values['asked']) && isset($values['visible']))
            {
                $image = isset($values['images']) ? $values['images'] : "";
                $line = __LINE__ + 1;
                $result = $this->db_query(''.
                    'INSERT INTO '. $this->DB_TABLE .' SET'.
                    ' name_id = "'. $this->escape_string($values['name_id']) .'",'.
                    ' question = "'. $this->escape_string($values['title']) .'",'.
                    ' asked = "'. $this->escape_string($values['description']) .'",'.
                    ' visible = "'. $this->escape_string($values['visible']) .'",'.
                    ' created = NOW()'
                );
                if($result)
                {
                    return true;
                } else {
                    throw new \mysqli_sql_exception("Error with save to DB info. ERROR[File:".__FILE__." Line:".$line);
                }
            } else {
                throw new \Exception("Not correct input values. ERROR[File:".__FILE__." Line:".$line);
            }
        } else {
            throw new \mysqli_sql_exception("Hasn't TABLE[". $this->DB_TABLE ."] in database");
        }
    }

    public function edit($nameIdFAQ, $values)
    {
        if($this->IS)
        {
            $line = __LINE__ + 1;
            if(!empty($nameIdFAQ) && isset($values['question']) && isset($values['asked']) && isset($values['visible']))
            {
                $line = __LINE__ + 1;
                $result = $this->db_query(''.
                    'UPDATE '. $this->DB_TABLE .' SET'.
                    ' question = "'. $this->escape_string($values['title']) .'",'.
                    ' asked = "'. $this->escape_string($values['description']) .'",'.
                    ' visible = "'. $this->escape_string($values['visible']) .'",'.
                    ' WHERE name_id = "'. $this->escape_string($nameIdFAQ) .'"');
                if($result)
                {
                    return true;
                } else {
                    throw new \mysqli_sql_exception("Error with update to DB info. ERROR[File:".__FILE__." Line:".$line);
                }
            } else {
                throw new \Exception("Not correct input values. ERROR[File:".__FILE__." Line:". $line);
            }
        } else {
            throw new \mysqli_sql_exception("Hasn't TABLE[". $this->DB_TABLE ."] in database");
        }
    }

    public function delete($NameFAQ)
    {
        if($this->IS && $NameFAQ != null && Validation::validName($NameFAQ))
        {
            return !!($this->db_query("DELETE FROM " . $this->DB_TABLE ." WHERE name_id = '". $this->escape_string($NameFAQ) ."'"));
        } else {
            throw new \mysqli_sql_exception("Hasn't TABLE[". $this->DB_TABLE ."]in database");
        }
    }

    public function deleteAll()
    {
        if($this->IS)
        {
            return !!($this->db_query("DELETE FROM " . $this->DB_TABLE));
        } else {
            throw new \mysqli_sql_exception("Hasn't TABLE[". $this->DB_TABLE ."]in database");
        }
    }
}