<?php
namespace components;

/*
$Gallery = new \lib\Gallery("Main", array(
    "template" => "gallery_template",
    "template_item" => "gallery_template_item",
    "source" => DIR_HOME.SP."view".SP."images".SP."gallery".SP
));
echo $Gallery->printGallery();
*/


class Gallery {

    public $SETTINGS = array();
    private $VALUES;
    private $NODE = "images";
    private $NODE_ITEM = "image";

    function __construct($name, $settings = array())
    {
        $this->SETTINGS = array(
            "name" => $name,
            "template" => (!empty($settings['template']) ? $settings['template'] : ""),
            "template_item" => (!empty($settings['template_item']) ? $settings['template_item'] : ""),
            "source" => (!empty($settings['source']) ? $settings['source'] : "")
        );

        $this->SETTINGS['config_file'] = $this->SETTINGS['source'].$name."_config.xml";

        if(!file_exists($this->SETTINGS['config_file']))
            $this->createFiles();

        $this->initImages();
    }

    public function printGallery() {
        $images = "";
        $topImage = null;
        foreach ($this->VALUES as $image)
        {
            if(!empty($image['id']))
            {
                if($topImage == null)
                    $topImage = $image;
                $images .= \lib\View::getTemplate($this->SETTINGS['template_item'], $image);
            }
        }
        return \lib\View::getTemplate(
            $this->SETTINGS['template'],
            array(
                "name" => $this->SETTINGS['name'],
                "images" => $images,
                "topImage" => $topImage
            )
        );
    }

    public function addImage($image_name, $data = array())
    {
        if($image_name !== null)
        {
            $image_id = strtolower(substr($image_name, 0, strripos($image_name, ".")));
            if(!$this->isHasVar($image_id))
            {
                $xml = simplexml_load_file($this->SETTINGS['config_file']);
                $value =  $xml->addChild(
                    $this->NODE_ITEM,
                    htmlspecialchars(mb_convert_encoding($image_name, 'utf-8', mb_detect_encoding($image_name)))
                );
                $index = !empty($data['index']) ? $data['index'] : "";
                $alt = !empty($data['alt']) ? $data['alt'] : "";
                $value->addAttribute("id", $image_id);
                $value->addAttribute("index", $index);
                $value->addAttribute("alt", $alt);
                $xml->saveXML($this->SETTINGS['config_file']);

                $this->initImages();
            } else
                $this->setImage($image_name, $data);
        }
    }

    public function setImage($image_name, $data = array())
    {
        if($image_name !== null)
        {
            $image_id = substr($image_name,0, strripos($image_name, "."));
            if($this->isHasVar($image_id))
            {
                $this->VALUES[$image_id]['index'] = (!empty($data['index']) ? $data['index'] : 1);
                $this->VALUES[$image_id]['alt'] = (!empty($data['alt']) ? $data['alt'] : "");

                $xml = new \DOMDocument('1.0', 'UTF-8');
                $values = $xml->createElement($this->NODE);

                foreach($this->VALUES AS $node) {
                    $image = $xml->createElement($this->NODE_ITEM, $node['image']);
                    $valid_attr_id = $xml->createAttribute('id');
                    $valid_attr_id->value = $node['id'];
                    $valid_attr_index = $xml->createAttribute('index');
                    $valid_attr_index->value = $node['index'];
                    $valid_attr_atr = $xml->createAttribute('atr');
                    $valid_attr_atr->value = $node['atr'];
                    $image->appendChild($valid_attr_id);
                    $image->appendChild($valid_attr_index);
                    $image->appendChild($valid_attr_atr);
                    $values->appendChild($image);
                }

                $xml->appendChild($values);
                $xml->save($this->SETTINGS['config_file']);
                $this->initImages();
            } else
                $this->addImage($image_name, $data);
        }
    }

    public function getImage($image_name)
    {
        if ($image_name == null)
            return null;

        $image_id = substr($image_name,0 , strripos($image_name, "."));
        if ($this->isHasVar($image_id))
            return $this->VALUES[$image_id];
        else
            return null;
    }

    public function deleteImage($image_name)
    {
        if($image_name != null)
        {
            $image_id = substr($image_name, 0, strripos($image_name, "."));
            if($this->isHasVar($image_id))
            {
                $this->initImages();
                $xml = new \DOMDocument('1.0', 'UTF-8');
                $values = $xml->createElement($this->NODE);
                unset($this->VALUES[$image_id]);
                foreach($this->VALUES AS $key => $node)
                {
                    $image = $xml->createElement($this->NODE_ITEM, basename($node['image']));
                    $valid_attr_id = $xml->createAttribute('id');
                    $valid_attr_id->value = $node['id'];
                    $valid_attr_index = $xml->createAttribute('index');
                    $valid_attr_index->value = $node['index'];
                    $valid_attr_atr = $xml->createAttribute('atr');
                    $valid_attr_atr->value = $node['atr'];
                    $image->appendChild($valid_attr_id);
                    $image->appendChild($valid_attr_index);
                    $image->appendChild($valid_attr_atr);
                    $values->appendChild($image);
                }
                $xml->appendChild($values);
                $xml->save($this->SETTINGS['config_file']);
                $this->initImages();
            }
        }
    }

    private function initImages() {
        $this->VALUES[] = array();
        $obj = simplexml_load_file($this->SETTINGS['config_file']);
        foreach ($obj->image AS $val)
        {
            $this->VALUES[(String) $val['id']] =
                array(
                    "id" => (String) $val['id'],
                    "image" => '/img/gallery/'.(String) $val,
                    "index" => (String) $val['index'],
                    "alt" => (String) $val['alt']
                );
        }
    }

    private function isHasVar($image_id)
    {
        if($image_id !== null)
            return !empty($this->VALUES[$image_id]);
        else
            return false;
    }

    private function createFiles()
    {
        if(!file_exists($this->SETTINGS['source']))
            mkdir($this->SETTINGS['source']);

        if(!file_exists($this->SETTINGS['config_file'])) {
            $xml = new \DOMDocument('1.0', 'UTF-8');
            $settings = $xml->createElement('files');
            $xml->appendChild($settings);
            $xml->save($this->SETTINGS['config_file']);
        }
    }
}