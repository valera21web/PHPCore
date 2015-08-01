<?php
namespace lib;


class Languages {

    private $LANG = null;
    private $DEFAULT_LANG = null;
    private $LANGUAGES_SYSTEM = array();

    private $VALUES;

    private $FILE_NAME = null;
    private $FOLDER =  ".land";

    public function __construct($lang, $list)
    {
        $this->FOLDER = __DIR__ . SP . $this->FOLDER. SP;
        $this->DEFAULT_LANG = (String) $list['default'];

        foreach($list->language AS $val)
        {
            $this->LANGUAGES_SYSTEM[] = (String) $val;
        }

        if(in_array($lang, $this->LANGUAGES_SYSTEM))
            $this->LANG = (String) $lang;
        else
            $this->LANG = $this->DEFAULT_LANG;

        $this->setFileName();
        if(!file_exists($this->FOLDER))
            $this->createFiles();

        $this->initLanguageVariable($this->LANG);
    }

    public function initAllLanguages() {
        foreach($this->LANGUAGES_SYSTEM AS $lang)
            if($lang != $this->DEFAULT_LANG)
                $this->initLanguageVariable($lang);
    }

    public function addValue($var, $text, $lang = null)
    {
        if($var !== null)
        {
            if(!$this->isHasVar($var, $lang))
            {
                $lang = $lang !== null ? $lang : $this->DEFAULT_LANG;
                foreach($this->LANGUAGES_SYSTEM AS $_lang)
                {

                    $xml = simplexml_load_file($this->getFileName($_lang));
                    $value =  $xml->addChild(
                        "value",
                        (
                        $lang != $_lang
                            ? ""
                            : htmlspecialchars(mb_convert_encoding($text, 'utf-8', mb_detect_encoding($text)))
                        )
                    );
                    $value->addAttribute("name", $var);
                    $xml->saveXML($this->getFileName($_lang));

                }
                $this->initAllLanguages();
            } else
                $this->setValue($var, $text, $lang);
        }
    }

    public function setValue($var, $newValue, $lang = null)
    {
        if($var !== null)
        {
            if($this->isHasVar($var, $lang))
            {
                $lang = $lang !== null ? $lang : $this->DEFAULT_LANG;
                $this->VALUES[$lang][$var]['value'] = $newValue;
                $fileName = $this->getFileName($lang);

                $xml = new \DOMDocument('1.0', 'UTF-8');
                $values = $xml->createElement('values');

                foreach($this->VALUES[$lang] AS $node) {
                    $value = $xml->createElement('value', $node['value']);
                    $valid_attr = $xml->createAttribute('name');
                    $valid_attr->value = $node['var'];
                    $value->appendChild($valid_attr);
                    $values->appendChild($value);
                }

                $xml->appendChild($values);
                $xml->save($fileName);
                $this->initLanguageVariable($lang);
            } else
                $this->addValue($var, $newValue, $lang);
        }
    }

    public function getValue($var, $lang = null)
    {
        if($var === null)
            return null;

        $lang = $lang === null ? $this->LANG : $lang;
        if(in_array($lang, $this->LANGUAGES_SYSTEM)) {
            if($this->isHasVar($var, $lang)) {
                $value = $this->VALUES[$lang][$var]["value"];
                return (String) htmlspecialchars_decode(mb_convert_encoding($value, 'utf-8', mb_detect_encoding($value)));
            } else {
                $this->initLanguageVariable($lang);
                if($this->isHasVar($var, $lang)) {
                    $value = $this->VALUES[$lang][$var]["value"];
                    return (String)htmlspecialchars_decode(mb_convert_encoding($value, 'utf-8', mb_detect_encoding($value)));
                } else
                    return null;
            }
        } else {
            return null;
        }
    }

    public function getLanguage() {
        return $this->LANG;
    }

    public function deleteValue($_var)
    {
        if($_var != null)
        {
            if($this->isHasVar($_var))
            {
                $this->initAllLanguages();
                foreach($this->LANGUAGES_SYSTEM AS $lang)
                {
                    $xml = new \DOMDocument('1.0', 'UTF-8');
                    $values = $xml->createElement('values');
                    unset($this->VALUES[$lang][$_var]);
                    foreach($this->VALUES[$lang] AS $node)
                    {
                        $value = $xml->createElement('value', $node['value']);
                        $valid_attr = $xml->createAttribute('name');
                        $valid_attr->value = $node['var'];
                        $value->appendChild($valid_attr);
                        $values->appendChild($value);
                    }
                    $xml->appendChild($values);
                    $xml->save($this->getFileName($lang));
                }
                $this->initAllLanguages();
            }
        }
    }

    private function getFileName($lang) {
        $lang = (String) $lang;
        return $this->FOLDER.$lang.".xml";
    }

    public  function  getNames() {
        $arr = array();
        foreach($this->VALUES AS $values)
        {
            foreach ($values AS $key => $value)
            {
                $arr[$key] = $key;
            }
            break;
        }
        return $arr;
    }

    public  function  getLanguages() {
        $arr = array();
        foreach($this->VALUES AS $key => $values)
        {
            $arr[] = $key;
        }
        return $arr;
    }

    public function pr() {
        print_r($this->VALUES);
    }

    private function isHasVar($_var, $lang = null)
    {
        if($_var !== null)
        {
            $lang = $lang === null ? $this->LANG : $lang ;
            if(empty($this->VALUES[$lang]))
                $this->initLanguageVariable($lang);

            return array_key_exists($_var, $this->VALUES[$lang]);
        } else
            return false;

    }

    private function getLang() {
        return $this->LANG;
    }

    private function setFileName() {
        if($this->FILE_NAME === null) {
            $lang = $this->getLang();
            if($lang !== null) {
                $this->FILE_NAME = $this->getFileName($lang);
            }
        }
    }

    private function createFiles()
    {
        mkdir($this->FOLDER);
        foreach($this->LANGUAGES_SYSTEM AS $lang) {
            $filename = $this->getFileName($lang);
            if(!file_exists($filename)) {
                $xml = new \DOMDocument('1.0', 'UTF-8');
                $values = $xml->createElement('values');
                $xml->appendChild($values);
                $xml->save($filename);
            }
        }
    }

    private function initLanguageVariable($lang) {
        $lang = (String) $lang;
        if(in_array($lang, $this->LANGUAGES_SYSTEM))
        {
            $this->VALUES[$lang] = array();
            $obj = simplexml_load_file($this->getFileName($lang));
            foreach ($obj->value AS $val)
            {
                $this->VALUES[$lang][(String)$val['name']] =
                    array(
                        "var" => (String) $val['name'],
                        "value" => (String) $val
                    );
            }
        }
    }
}