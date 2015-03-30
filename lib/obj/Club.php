<?php
namespace obj;

require_once(DIR_HOME . "/lib/Validation.php");

use lib\Validation;

class Club
{
    private $data = array(
        "id" => 0,
        "url" => null,
        "name" => null,
        "image" => null,
        "description" => null,
        "info_data" => null,
        "url_club" => null,
        "visible" => 0,
        "created" => null
    );

    private $keys_for_filter = array("id","url","visible","url_club","name");

    public function __construct($data = null, $type_data = "filter")
    {
        global $SYSTEM;
        if($data != null && is_array($data))
        {
            if($type_data == "filter")
            {
                $sql_query = "SELECT * FROM clubs WHERE 1=1 ";
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
        $sql_query = "SELECT * FROM clubs WHERE 1=1 ";
        foreach($data AS $key => $value)
        {
            if(in_array($key, $this->keys_for_filter))
            {
                $sql_query .= "AND `".$key."` = '". ($value) ."' ";
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
        $this->data['name'] = isset($data['name']) ? $data['name'] : $this->data['name'];
        $this->data['image'] = isset($data['image']) ? $data['image'] : $this->data['image'];
        $this->data['description'] = isset($data['description']) ? $data['description'] : $this->data['description'];
        $this->data['info_data'] = isset($data['info_data']) ? $data['info_data'] : $this->data['info_data'];
        $this->data['url_club'] = isset($data['url_club']) ? $data['url_club'] : $this->data['url_club'];
        $this->data['visible'] = isset($data['visible']) ? $data['visible'] : $this->data['visible'];
        $this->data['created'] = isset($data['created']) ? $data['created'] : $this->data['created'] ;
    }

    public function isEmpty()
    {
        return $this->data['id'] == 0;
    }


    public function save()
    {
        global $SYSTEM;
        $sql_query =  'INSERT INTO clubs SET'.
            ' `name` = "'. $this->escape_string($this->getName()) .'",'.
            ' `login` = "'. $this->escape_string($this->getLogin()) .'",'.
            ' `password` = "'. $this->escape_string($this->data['password']) .'",'.
            ' `email` = "'. $this->escape_string($this->getEmail()) .'",'.
            ' `created` = NOW()';
        return $SYSTEM->db_query("");
    }

    public function getId()
    {
        return $this->data['id'];
    }

    public function setUrl($value)
    {
        if(Validation::validPageName($value))
        {
            $this->data['url'] = $value;
            return true;
        } else {
            return false;
        }
    }

    public function getUrl()
    {
        return $this->data['url'];
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

    public function getDescription()
    {
        return $this->data['description'];
    }

    public function setInfoData($value)
    {
        $this->data['info_data'] = $value;
        return ;
    }

    public function getInfoData()
    {
        return $this->data['info_data'];
    }

    public function setUrlClub($value)
    {
        $this->data['url_club'] = $value;
        return ;
    }

    public function getUrlClub()
    {
        return $this->data['url_club'];
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