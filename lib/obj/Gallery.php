<?php
namespace obj;

require_once(getenv("DOCUMENT_ROOT") . "/lib/Validation.php");

use lib\Validation;
use lib\View;

class Gallery
{
    private $data = array(
        "id" => 0,
        "name" => null,
        "name_id" => null,
        "gallery" => null,
        "date_event" => null,
        "visible" => null,
        "created" => null
    );

    private $keys_for_filter = array("id", "name", "name_id", "date_event", "visible");
    private $sql_query_start = "SELECT * FROM gallery WHERE 1=1 ";

    public function __construct($data = null, $type_data = "filter")
    {
        global $SYSTEM;
        if($data != null && is_array($data))
        {
            if($type_data == "filter")
            {
                $sql_query = $this->sql_query_start;
                foreach($data AS $key => $value)
                {
                    if(in_array($key, $this->keys_for_filter))
                    {
                        $sql_query .= "AND `".$key."` = '". ($value) ."' ";
                    }
                }
                $sql_query .= " LIMIT 1";
                $result_data = $SYSTEM->db_query($sql_query, "assoc");
                if(count($result_data) > 0)
                {
                    $this->merge($result_data['0']);
                }
            } else if($type_data == "data")
            {
                $this->merge($data);
            }
        }
    }

    public function getAll($data)
    {
        global $SYSTEM;
        $sql_query = $this->sql_query_start;
        foreach($data AS $key => $value)
        {
            if(in_array($key, $this->keys_for_filter))
            {
                $sql_query .= "AND `".$key."` = '". ($value) ."' ";
            }
        }
        if (!empty($data['order'])) {
            if (is_array($data['order'])) {
                $sql_query .= " ORDER BY `" . $data['order']['0'] . "` " . ($data['order']['1'] == "DESC" ? "DESC" : "ASC");
            } else {
                $sql_query .= " ORDER BY `" . $data['order'] . "`";
            }
        }
        if (!empty($data['limit'])) {
            if (is_array($data['limit'])) {
                $sql_query .= " LIMIT " . (int)$data['limit']['0'] . "," . (int)$data['limit']['1'];
            } else {
                $sql_query .= " LIMIT " . (int)$data['limit'];
            }
        }
        $result_data = $SYSTEM->db_query($sql_query, "assoc");
        if(count($result_data) > 0)
            return $result_data;
        else
            return null;

    }

    private function merge($data)
    {
        $this->data['id'] = isset($data['id']) ? $data['id'] : $this->data['id'];
        $this->data['name'] = isset($data['name']) ? $data['name'] : $this->data['name'];
        $this->data['name_id'] = isset($data['name_id']) ? $data['name_id'] : $this->data['name_id'];
        $this->data['date_event'] = isset($data['date_event']) ? $data['date_event'] : $this->data['date_event'];
        $this->data['visible'] = isset($data['visible']) ? $data['visible'] : $this->data['visible'];
        $this->data['created'] = isset($data['created']) ? $data['created'] : $this->data['created'] ;
    }

    public static function navByLimit($activeSubPage, $filter, $sizeItemOnPage = 10, $templateButton = "nav_sub_pages", $sizeButtonsActive = 5)
    {
        global $SYSTEM;
        $sql_query = 'SELECT COUNT(`id`) AS `count` FROM gallery WHERE 1=1 ';
        foreach($filter AS $key => $value)
        {
            if(in_array($key, array("id", "name", "name_id", "date_event", "visible")))
            {
                $sql_query .= "AND `".$key."` = '". ($value) ."' ";
            }
        }
        $result_data = $SYSTEM->db_query($sql_query, "assoc");
        if(isset($result_data['0']['count'])) {
            $allSize = (int)$result_data['0']['count'];
            return $SYSTEM->navSubPages($activeSubPage, $allSize, $templateButton, $sizeItemOnPage, $sizeButtonsActive);
        }
            else return "";
    }

    public function isEmpty()
    {
        return $this->data['id'] == 0;
    }

    public function save()
    {
        global $SYSTEM;
        $sql_query =  'INSERT INTO gallery SET'.
            ' `name` = "'. ($this->getName()) .'",'.
            ' `name_id` = "'. ($this->getNameId()) .'",'.
            ' `date_event` = "'. ($this->getDateEvent()) .'",'.
            ' `visible` = "'. ($this->getVisible()) .'",'.
            ' `created` = NOW()';
        return $SYSTEM->db_query($sql_query);
    }

    public function getId()
    {
        return $this->data['id'];
    }

    public function setName($value)
    {
        $this->data['name'] = $value;
        return ;
    }

    public function getName()
    {
        return $this->data['name'];
    }

    public function setNameId($value)
    {
        $this->data['name_id'] = $value;
        return ;
    }

    public function getNameId()
    {
        return $this->data['name_id'];
    }

    public function setDateEvent($value)
    {
        if(Validation::validDate($value))
        {
            $this->data['date_event'] = $value;
            return true;
        } else {
            return false;
        }
    }

    public function getDateEvent()
    {
        return $this->data['date_event'];
    }

    public function setVisible($value)
    {
        $this->data['visible'] = $value == 1 ? 1 : 0;
        return ;
    }

    public function getVisible()
    {
        return $this->data['visible'];
    }

    public function getCreated()
    {
        return $this->data['created'];
    }
}