<?php


namespace components;


class XML {
    private $FILE = null;
    private $NODE = null;
    private $NODE_ITEM = null;
    private $ROOT = null;
    private $XML = null;


    public function __construct($fullUrlFile, $rootNode = null, $subRootNode = null)
    {
        $this->FILE = $fullUrlFile;
        $this->$NODE = $rootNode;
        $this->$NODE_ITEM = $subRootNode;
        $this->XML = new \DOMDocument('1.0', 'UTF-8');
        $this->ROOT = $this->XML->createElement($this->NODE);
    }

    public function read($attributes = array())
    {
        $value = array();
        $obj = simplexml_load_file($this->FILE);
        foreach ($obj->$this->NODE_ITEM AS $val)
        {
            $value[]['value'] =  (String) $val;
            if(count($attributes) > 0)
                foreach($attributes AS $attribute)
                    $value[][$attribute] =  (String) $val[$attribute];
        }
    }

    public function addToRoot($NodeName, $value, $attributes = array())
    {
        $NodeName = $NodeName === true ? $this->$NODE_ITEM : $NodeName;
        $node = $this->XML->createElement($NodeName, $value);
        if(count($attributes) > 0)
            foreach($attributes AS $attribute)
            {
                $valid_attr = $this->XML->createAttribute($attribute['id']);
                $valid_attr->value = $attribute['value'];
                $node->appendChild($valid_attr);
            }
        $this->ROOT->appendChild($node);
    }

    public function save()
    {
        $this->XML->appendChild($this->ROOT);
        $this->XML->save($this->FILE);
    }
}