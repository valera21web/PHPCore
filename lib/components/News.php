<?php
namespace components;

use \lib\DB;
use \lib\Validation;
use \lib\View;

class News extends DB {

    private $DB_TABLE = "";
    private $TEMPLATE_NEWS = "";
    private $IS = false;
    private $IS_ADMIN = false;

    public function __construct($DBTable, $templateNews, $isAdmin = false)
    {
        parent::__construct();
        $this->DB_TABLE = $DBTable;
        if ($this->db_query("SELECT * FROM " . $DBTable ." LIMIT 1", "assoc"))
        {
            $this->IS = true;
        } else {
            throw new \mysqli_sql_exception("Hasn't TABLE[". $this->DB_TABLE ."] in database. ERROR[File:".__FILE__." Line:".__LINE__);
        }
        $this->IS_ADMIN = $isAdmin;
        if(Validation::validName($templateNews))
        {
            $this->TEMPLATE_NEWS = $templateNews;
        }
    }

    public function get($NameNews, $templateNews = null)
    {
        if($this->IS && $NameNews != null && Validation::validName($NameNews))
        {
            $result = $this->db_query(""
                ." SELECT * FROM " . $this->DB_TABLE
                ." WHERE name_id = '". $NameNews ."'"
                    ." AND `visible` = '1'"
                    .($this->IS_ADMIN ? "" : " AND NOW() BETWEEN `startShow` AND `finishShow`")
                ." LIMIT 1"
                , "assoc");
            if(!empty($result['0']))
            {
                $templateNews = $templateNews == null ? $this->TEMPLATE_NEWS : $templateNews;
                return View::getTemplate($templateNews, $result['0']);
            } else {
                return "";
            }
        } else {
            throw new \mysqli_sql_exception("Hasn't TABLE[". $this->DB_TABLE ."]in database");
        }
    }

    public function getAll($templateNews = null)
    {
        if($this->IS)
        {
            $result = $this->db_query(""
                ." SELECT * FROM " . $this->DB_TABLE
                ." WHERE `visible` = '1' "
                .($this->IS_ADMIN ? "" : " AND NOW() BETWEEN `startShow` AND `finishShow`")
                , "assoc");
            if(!empty($result))
            {
                $templateNews = $templateNews == null ? $this->TEMPLATE_NEWS : $templateNews;
                $string = "";
                foreach($result AS $row) {
                    $string .= View::getTemplate($templateNews, $row);
                }
                return $string;
            } else {
                return "";
            }
        } else {
            throw new \mysqli_sql_exception("Hasn't TABLE[". $this->DB_TABLE ."] in database");
        }
    }

    public function getInfo($NameNews)
    {
        if($this->IS && $NameNews != null && Validation::validName($NameNews))
        {
            $result = $this->db_query(""
                ." SELECT * FROM " . $this->DB_TABLE
                ." WHERE name_id = '". $NameNews ."'"
                ." AND `visible` = '1'"
                .($this->IS_ADMIN ? "" : " AND NOW() BETWEEN `startShow` AND `finishShow`")
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
                ." WHERE visible` = '1' "
                .($this->IS_ADMIN ? "" : "AND NOW() BETWEEN `startShow` AND `finishShow`")
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
            if(!empty($values['name_id']) && isset($values['title']) && isset($values['description'])
                && isset($values['startShow']) && isset($values['finishShow']) && isset($values['visible'])
            )
            {
                $image = isset($values['images']) ? $values['images'] : "";
                $line = __LINE__ + 1;
                $result = $this->db_query(''.
                    'INSERT INTO '. $this->DB_TABLE .' SET'.
                        ' name_id = "'. Validation::validName($values['name_id']) .'",'.
                        ' title = "'. Validation::validName($values['title']) .'",'.
                        ' description = "'. ($values['description']) .'",'.
                        ' image = "'. Validation::validName($image) .'",'.
                        ' startShow = "'. ($values['startShow']) .'",'.
                        ' finishShow = "'. ($values['finishShow']) .'",'.
                        ' visible = "'. ($values['visible']) .'",'.
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

    public function edit($nameIdNews, $values)
    {
        if($this->IS)
        {
            $line = __LINE__ + 1;
            if(!empty($nameIdNews) && isset($values['title']) && isset($values['description'])
                && isset($values['startShow']) && isset($values['finishShow']) && isset($values['visible'])
            )
            {
                $image = isset($values['images']) ? $values['images'] : "";
                $line = __LINE__ + 1;
                $result = $this->db_query(''.
                    'UPDATE '. $this->DB_TABLE .' SET'.
                        ' title = "'. Validation::validName($values['title']) .'",'.
                        ' description = "'. ($values['description']) .'",'.
                        ' image = "'. Validation::validName($image) .'",'.
                        ' startShow = "'. ($values['startShow']) .'",'.
                        ' finishShow = "'. ($values['finishShow']) .'",'.
                        ' visible = "'. ($values['visible']) .'",'.
                    ' WHERE name_id = "'. Validation::validName($nameIdNews) .'"');
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

    public function delete($NameNews)
    {
        if($this->IS && $NameNews != null && Validation::validName($NameNews))
        {
            return !!($this->db_query("DELETE FROM " . $this->DB_TABLE ." WHERE name_id = '". Validation::validName($NameNews) ."'"));
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