<?php
namespace lib;

class ListObj implements \Iterator
{
    private $data = array();

    function __construct($name_class, $params_filter = array())
    {
        $data = null;
        $name = "\\obj\\" . $name_class;
        require_once getenv("DOCUMENT_ROOT")."/lib/obj/" . $name_class . ".php";

        if(!method_exists($name, 'getAll') ) {
            throw new \BadMethodCallException();
        } else {
            $tmp = new $name();
            $data = $tmp->getAll($params_filter);
        }

        if($data != null)
        {
            foreach($data AS $row)
            {
                $this->data[] = new $name($row, "data");
            }
        }
    }

    public function get($id)
    {
        if(isset($this->data[$id]))
            return $this->data[$id];
        else
            return null;
    }

    public function size()
    {
        if($this->data == null)
            return 0;
        return count($this->data);
    }

    public function rewind()
    {
        reset($this->data);
    }

    public function current()
    {
        return current($this->data);
    }

    public function key()
    {
        return key($this->data);
    }

    public function next()
    {
        return next($this->data);
    }

    public function valid()
    {
        $key = key($this->data);
        return ($key !== NULL && $key !== FALSE);
    }
}