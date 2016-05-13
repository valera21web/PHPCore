<?php
namespace lib;

if(!defined("SP"))
    define("SP", DIRECTORY_SEPARATOR);
require_once (__DIR__ . SP ."CacheManager.php");

class Languages
{
    private $LANG = null;
    private $DEFAULT_LANG = null;
    private $LANGUAGES_SYSTEM = array();

    public $VALUES;

    private $FILE_NAME = null;
    private $FOLDER =  ".land";
    private static $KEY_LANGUAGE = "KEY_LANGUAGE";

    public function __construct($lang, $list)
    {
        $this->FOLDER = __DIR__ . SP . $this->FOLDER. SP;
        $this->DEFAULT_LANG = (String) $list['@attributes']['default'];
        foreach($list['language'] AS $val)
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

    public function initAllLanguages($force = false)
    {
        foreach($this->LANGUAGES_SYSTEM AS $lang)
            $this->initLanguageVariable($lang, $force);
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
                    if($_lang == "ur")
                        continue;
                    $text = str_ireplace("\"","'", $text);
                    $xml = simplexml_load_file($this->getFileName($_lang));
                    $value = $xml->addChild("value",($lang != $_lang ? "" : bin2hex($text)));
                    $value->addAttribute("name", $var);
                    $xml->saveXML($this->getFileName($_lang));

                }
                $this->initAllLanguages(true);
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
                $newValue = str_ireplace("\"","'", $newValue);
                $this->VALUES[$lang][$var]['value'] = bin2hex($newValue);
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
                $this->initLanguageVariable($lang, true);
            } else
                $this->addValue($var, $newValue, $lang);
        }
    }

    public function getValue($var, $lang = null)
    {
        if($var === null)
            return null;

        $lang = $lang === null ? $this->LANG : $lang;
        if($lang == "ur")
            return "`".$var."`";
        if(in_array($lang, $this->LANGUAGES_SYSTEM)) {
            if($this->isHasVar($var, $lang)) {
                return hex2bin($this->VALUES[$lang][$var]["value"]);
            } else {
                $this->initLanguageVariable($lang);
                if($this->isHasVar($var, $lang)) {
                    return hex2bin($this->VALUES[$lang][$var]["value"]);
                } else
                    return null;
            }
        } else {
            return null;
        }
    }

    public function getValuesStartOf($var, $lang = null)
    {
        if($var === null)
            return null;
        $lang = $lang === null ? $this->LANG : $lang;
        if(in_array($lang, $this->LANGUAGES_SYSTEM))
        {
            if(!$this->isHasVar($var, $lang))
                $this->initLanguageVariable($lang);
            if(!$this->isHasVar($var, $lang))
            {
                $res = array();
                foreach ($this->VALUES[$lang] AS $key => $variable)
                {
                    if(strpos($key, $var) === 0)
                        $res[] = hex2bin($this->VALUES[$lang][$key]["value"]);
                }
                return $res;
            }
        } else {
            return null;
        }
    }

    public function getLanguage()
    {
        return $this->LANG == "ur" ? $this->DEFAULT_LANG : $this->LANG;
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
                $this->initAllLanguages(true);
            }
        }
    }

    private function getFileName($lang) {
        $lang = (String) $lang;
        return $this->FOLDER.$lang.".xml";
    }

    public function getNames() {
        $arr = array();
        foreach($this->VALUES AS $values)
        {
            foreach ($values AS $key => $value)
                $arr[$key] = $key;
            break;
        }
        return $arr;
    }

    public function getLanguages() {
        $arr = array();
        foreach($this->VALUES AS $key => $values)
            $arr[] = $key;
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

    private function initLanguageVariable($lang, $force = false)
    {
        $lang = (String) $lang;
        $lang = $lang == "ur" ? "en" : $lang;
        if(in_array($lang, $this->LANGUAGES_SYSTEM))
        {
            $this->VALUES[$lang] = array();
            $key = self::$KEY_LANGUAGE."_".$lang;
            if(!\lib\CacheManager::exist($key) || $force)
            {
                $xmlTmp = simplexml_load_file($this->getFileName($lang));
                $tmp = array();
                foreach ($xmlTmp->value AS $val)
                {
                    $tmp[(String) $val['name']] =
                        array( "var" => (String) $val['name'], "value" => (String) $val );
                }
                \lib\CacheManager::set($key, json_encode($tmp));
            }
            $this->VALUES[$lang] = json_decode(\lib\CacheManager::get($key), true);

        }
    }

    public function getLANGUAGES_SYSTEM()
    {
        return $this->LANGUAGES_SYSTEM;
    }
}