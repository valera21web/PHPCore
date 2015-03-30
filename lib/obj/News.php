<?php
namespace obj;

require_once(getenv("DOCUMENT_ROOT") . "/lib/Validation.php");

use lib\Validation;

class News
{
    private $keys_for_filter = array(
        "id",
        "url",
        "type",
        "visible"
    );

    private $data = array(
        "id" => 0,
        "url" => null,
        "type" => null,
        "typeId" => null,
        "title" => null,
        "description" => null,
        "image" => null,
        "start_show" => null,
        "finish_show" => null,
        "visible" => 0
    );
    private $sql_query_start = "
            SELECT
                n.*,
                nt.`name` AS `type_name`
            FROM news AS n
            JOIN news_type AS nt ON(nt.id = n.type)
            WHERE 1=1 ";


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
                        $sql_query .= "AND n.`".$key."` = '". $value ."' ";
                    }
                }
                $sql_query .= " GROUP BY n.id ";
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

    public function getAll($data = null)
    {
        global $SYSTEM;
        $sql_query = $this->sql_query_start;
        if($data != null) {
            foreach ($data AS $key => $value) {
                if (in_array($key, $this->keys_for_filter)) {
                    $sql_query .= "AND n.`" . $key . "` = '" . $value . "' ";
                }
            }
            if (isset($data['archive'])) {
                if (!!$data['archive']) {
                    $sql_query .= " AND n.`finish_show` < NOW() ";
                } else {
                    $sql_query .= " AND n.`start_show` <= NOW() AND n.`finish_show` >= NOW() ";
                }
            }
            $sql_query .= " GROUP BY n.id ";
            if (!empty($data['order'])) {
                if (is_array($data['order'])) {
                    $sql_query .= " ORDER BY n.`" . $data['order']['0'] . "` " . ($data['order']['1'] == "DESC" ? "DESC" : "ASC");
                } else {
                    $sql_query .= " ORDER BY n.`" . $data['order'] . "`";
                }
            }
            if (!empty($data['limit'])) {
                if (is_array($data['limit'])) {
                    $sql_query .= " LIMIT " . (int)$data['limit']['0'] . "," . (int)$data['limit']['1'];
                } else {
                    $sql_query .= " LIMIT " . (int)$data['limit'];
                }
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
        $this->data['url'] = isset($data['url']) ? $data['url'] : $this->data['url'];
        $this->data['type_name'] = isset($data['type_name']) ? $data['type_name'] : $this->data['type_name'];
        $this->data['type'] = isset($data['type']) ? $data['type'] : $this->data['type'];
        $this->data['title'] = isset($data['title']) ? $data['title'] : $this->data['title'];
        $this->data['description'] = isset($data['description']) ?
            $data['description'] : $this->data['description'];
        $this->data['image'] = isset($data['image']) ? $data['image'] : $this->data['image'];
        $this->data['start_show'] = isset($data['start_show']) ? $data['start_show'] : $this->data['start_show'];
        $this->data['finish_show'] = isset($data['finish_show']) ? $data['finish_show'] : $this->data['finish_show'];
        $this->data['visible'] = isset($data['visible']) ? $data['visible'] : $this->data['visible'];
    }

    public function getTypes()
    {
        global $SYSTEM;
        $result_data = $SYSTEM->db_query('SELECT * FROM news_type', "assoc");
        if(count($result_data) > 0)
        {
            return $result_data;
        } else
            return array();
    }

    public static function navByLimit($activeSubPage, $filter, $sizeItemOnPage = 10, $templateButton = "nav_sub_pages", $sizeButtonsActive = 5)
    {
        global $SYSTEM;
        $sql_query = 'SELECT COUNT(`id`) AS `count` FROM news WHERE 1=1 ';
        foreach($filter AS $key => $value)
        {
            if(in_array($key, array("type", "visible")))
            {
                $sql_query .= "AND `".$key."` = '". ($value) ."' ";
            }
        }
        if (isset($filter['archive'])) {
            if (!!$filter['archive']) {
                $sql_query .= " AND `finish_show` < NOW() ";
            } else {
                $sql_query .= " AND `start_show` <= NOW() AND `finish_show` >= NOW() ";
            }
        }
        $result_data = $SYSTEM->db_query($sql_query, "assoc");
        if(isset($result_data['0']['count'])) {
            $allSize = (int)$result_data['0']['count'];
            return $SYSTEM->navSubPages($activeSubPage, $allSize, $templateButton, $sizeItemOnPage, $sizeButtonsActive);
        }
        else return "";
    }

    public function getId()
    {
        return $this->data['id'];
    }

    public function setURL($value)
    {
        if(Validation::validPageName($value))
        {
            $this->data['url'] = $value;
            return true;
        } else {
            return false;
        }
    }

    public function getURL()
    {
        return $this->data['url'];
    }

    public function setType($value)
    {
        $this->data['type_name'] = $value;
    }

    public function getType()
    {
        return $this->data['type_name'];
    }

    public function setTypeId($value)
    {
        if(Validation::validInteger($value))
        {
            $this->data['type'] = $value;
            return true;
        } else {
            return false;
        }
    }

    public function getTypeId()
    {
        return $this->data['type'];
    }

    public function setTitle($value)
    {
        $this->data['title'] = $value;
        return ;
    }

    public function getTitle()
    {
        return $this->data['title'];
    }

    public function setImage($value)
    {
        if(Validation::validImageName($value))
        {
            $this->data['image'] = $value;
            return true;
        } else {
            return false;
        }
    }

    public function getImage()
    {
        return $this->data['image'];
    }

    public function setDescription($value)
    {
        $this->data['description'] = $value;
        return ;
    }

    public function getDescription($len = null)
    {
        if($len != null && Validation::validInteger($len) && strlen($this->data['description']) > $len)
        {
            $str = substr($this->data['description'], 0, $len);
            $str = substr($str, 0, strripos($str, " "));
            return $str . (strlen($this->data['description']) > strlen($str) ? "..." : "" );
        }
        else
            return $this->data['description'];
    }

    public function setStartShow($value)
    {
        if(Validation::validDate($value))
        {
            $this->data['start_show'] = $value;
            return true;
        } else {
            return false;
        }
    }

    public function getStartShow()
    {
        return $this->data['start_show'];
    }

    public function setFinishShow($value)
    {
        if(Validation::validDate($value))
        {
            $this->data['finish_show'] = $value;
            return true;
        } else {
            return false;
        }
    }

    public function getFinishShow()
    {
        return $this->data['finish_show'];
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


}