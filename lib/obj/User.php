<?php
namespace obj;

require_once(getenv("DOCUMENT_ROOT") . "lib\\DB.php");
require_once(getenv("DOCUMENT_ROOT") . "lib\\Validation.php");

use lib\DB;
use lib\Validation;

class User extends DB
{
    private $data = array(
        "id" => 0,
        "name" => null,
        "login" => null,
        "password" => null,
        "email" => null,
        "active" => 0,
        "active_code" => null,
        "created" => null
    );

    private $keys_for_filter = array("id","name","login","password","email","active","active_code");

    public function __construct($data = null, $type_data = "filter")
    {
        parent::__construct();
        if($data != null && is_array($data))
        {
            if($type_data == "filter")
            {
                $sql_query = "SELECT * FROM users WHERE 1=1 ";
                foreach($data AS $key => $value)
                {
                    if(in_array($key, $this->keys_for_filter))
                    {
                        $sql_query .= "AND `".$key."` = '". $this->escape_string($value) ."' ";
                    }
                }
                $sql_query .= " LIMIT 1";
                $result_data = $this->db_query($sql_query, "assoc");
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
        $sql_query = "SELECT * FROM users WHERE 1=1 ";
        foreach($data AS $key => $value)
        {
            if(in_array($key, $this->keys_for_filter))
            {
                $sql_query .= "AND `".$key."` = '". $this->escape_string($value) ."' ";
            }
        }
        $result_data = $this->db_query($sql_query, "assoc");
        if(count($result_data) > 0)
            return $result_data;
        else
            return null;

    }

    private function merge($data) {
        $this->data['id'] = isset($data['id']) ? $data['id'] : $this->data['id'];
        $this->data['name'] = isset($data['name']) ? $data['name'] : $this->data['name'];
        $this->data['login'] = isset($data['login']) ? $data['login'] : $this->data['login'];
        $this->data['password'] = isset($data['password']) ? $data['password'] : $this->data['password'];
        $this->data['email'] = isset($data['email']) ? $data['email'] : $this->data['email'];
        $this->data['active'] = isset($data['active']) ? $data['active'] : $this->data['active'];
        $this->data['active_code'] = isset($data['active_code']) ? $data['active_code'] : $this->data['active_code'];
        $this->data['created'] = isset($data['created']) ? $data['created'] : $this->data['created'] ;
    }

    public function isEmpty()
    {
        return $this->data['id'] == 0;
    }

    public function isHasLogin()
    {
        if($this->getLogin() != null)
        {

            $sql_query = "SELECT `id` FROM users WHERE `login` = '". $this->getLogin() ."' ";
            $result_data = $this->db_query($sql_query, "assoc");
            return !empty($result_data['0']) && !empty($result_data['0']['id']);
        } else
            return false;
    }

    public function isHasEmail()
    {
        if($this->getEmail() != null)
        {

            $sql_query = "SELECT `id` FROM users WHERE `email` = '". $this->getEmail() ."' ";
            $result_data = $this->db_query($sql_query, "assoc");
            return !empty($result_data['0']) && !empty($result_data['0']['id']);
        } else
            return false;
    }

    public function save()
    {
        $sql_query =  'INSERT INTO users SET'.
            ' `name` = "'. $this->escape_string($this->getName()) .'",'.
            ' `login` = "'. $this->escape_string($this->getLogin()) .'",'.
            ' `password` = "'. $this->escape_string($this->data['password']) .'",'.
            ' `email` = "'. $this->escape_string($this->getEmail()) .'",'.
            ' `created` = NOW()';
        return $this->db_query($sql_query);
    }

    public function activeCode()
    {
        $active_code = md5($this->getName().$this->getLogin().$this->getEmail());
        $sql_query =
            'UPDATE users SET `active_code` = "'. $active_code .'"'.
            ' WHERE `login` = "'. $this->escape_string($this->getLogin()) .'"';
        $this->db_query($sql_query);
        return $active_code;
    }

    public function sendActivatedCode()
    {
        $message = "
            For activated profile link <a href='http://". $_SERVER['HTTP_HOST'] ."/registration?active=".  $this->activeCode() ."'></>
        ";
        $this->sendMail("Activated profile", $message);
    }

    public function activatedAccount()
    {
        $sql_query =
            'UPDATE users SET `active_code` = "0", `active` = "1"'.
            ' WHERE `id` = '. $this->getId();
        echo $sql_query;
        $this->db_query($sql_query);
    }

    public function sendMail($subject,$message)
    {
        if($this->getEmail() != null)
        {
            $headers = 'From: admin@movie.com' . "\r\n" .
                'Reply-To: admin@movie.com' . "\r\n";
            mail($this->getEmail(), $subject, $message, $headers);
        }
    }

    public function getId()
    {
        return $this->data['id'];
    }

    public function setName($name)
    {
        $this->data['name'] = $name;
        return ;
    }

    public function getName()
    {
        return $this->data['name'];
    }

    public function setLogin($login)
    {
        if(Validation::validLogin($login))
        {
            $this->data['login'] = $login;
            return true;
        } else {
            return false;
        }
    }

    public function getLogin()
    {
        return $this->data['login'];
    }

    public function setEmail($email)
    {
        if(Validation::validEmail($email))
        {
            $this->data['email'] = $email;
            return true;
        } else {
            return false;
        }
    }

    public function getEmail()
    {
        return $this->data['email'];
    }

    public function setActive($active)
    {
        $this->data['active'] = $active == 1 ? 1 : 0;
        return ;
    }

    public function getActive()
    {
        return $this->data['active'];
    }

    public function getCreated()
    {
        return $this->data['created'];
    }
}