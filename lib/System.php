<?php
namespace lib;

require_once (__DIR__ . SP ."DB.php");
require_once (__DIR__ . SP ."Languages.php");
require_once (__DIR__ . SP ."Links.php");
require_once (__DIR__ . SP ."View.php");
require_once (__DIR__ . SP ."Validation.php");
require_once (__DIR__ . SP ."ListObj.php");
require_once (__DIR__ . SP ."function.php");


abstract class System
{
    private $SETTINGS;
    private $LANGUAGES;
    private $LINKS;
    private $DB;
    private $ADMIN = false;
    private $PAGE_INFO = null;

    public function __construct($admin = false)
    {
        global $SYSTEM;
        $this->ADMIN = !!$admin;
        $this->SETTINGS = simplexml_load_file("config.xml");
        $lang = !empty($_GET['lang']) ? $_GET['lang'] : $this->SETTINGS->laguages['default'];
        $this->LANGUAGES = new Languages($lang, $this->SETTINGS->laguages);
        $this->DB = new DB(
            (String) $this->SETTINGS->database->host,
            (String) $this->SETTINGS->database->db_name,
            (String) $this->SETTINGS->database->user_name,
            (String) $this->SETTINGS->database->password
        );

        $this->LINKS = new Links($this->LANGUAGES->getLanguage());
        $SYSTEM = $this;
        $this->run();
    }
    /**
     *
     */
    abstract public function run();

    /**
     * Function return value of the setting
     *
     * @param $name - name setting from the XML file what you want get
     * @param $subName - sub name setting from the XML file what you want get
     * @return null|string - if this setting is, return it else return NULL
     */
    public function getSetting($name, $subName = null)
    {
        if(!empty($this->SETTINGS->$name))
            if($subName != null && !empty($this->SETTINGS->$name->$subName))
            {
                return (String) $this->SETTINGS->$name->$subName;
            } else {
                return (String) $this->SETTINGS->$name;
            }
        else
            return null;
    }

    /**
     * Function return language of the system
     *
     * @return string - return language of the system
     */
    public function getLanguage()
    {
        return $this->LANGUAGES->getLanguage();
    }

    /**
     * @param String $urlPage - url of the page for the system language
     * @return bool|array - if this page is, return info about it else return false
     */
    public function getInfoPage($urlPage)
    {
        $result_ = $this->DB->db_query("
                SELECT p.`id`, p.file_name, p_i.title, p_i.description, p.template, p.visible
                FROM pages AS p
                JOIN pages_info AS p_i ON(p_i.page_id = p.id)
                WHERE p_i.url = '". $this->DB->escape_string($urlPage) ."' AND p_i.lang = '". $this->LANGUAGES->getLanguage() ."'
                    AND `admin` = ".($this->ADMIN ? 1 : 0)."
                LIMIT 1
            ", "assoc");
        if(!empty($result_['0']['file_name']) &&
            file_exists("pages".SP.($this->ADMIN ? "admin".SP : "").$result_['0']['file_name'].".php")
            && $result_['0']['visible'] != "0")
        {
            return $result_['0'];
        } else {
            return false;
        }
    }

    /**
     * @param $urlPage - url of the page for the system language
     * @return array - return the text of the page for print else if the system hasn't the page than ERROR-404
     */
    public function openPage($urlPage)
    {
        $pageInfo = $this->getInfoPage($urlPage);
        if($pageInfo != false)
        {
            $this->PAGE_INFO = $pageInfo;
            $page_content = "";
            ob_start();
            require('pages'. SP . ($this->ADMIN ? "admin".SP : ""). $pageInfo['file_name'] .'.php');
            $page_content = ob_get_contents();
            ob_end_clean();

            ob_start();
            require('templates'. SP . $pageInfo['template'] .'.php');
            $result = ob_get_contents();
            ob_end_clean();

            return $this->LINKS->replaceLinks($result);
        } else {
            header("Location: 404.html");
            return null;
        }
    }

    public function getPagesContent($namePage)
    {
        $srt = "";

        $result = $this->DB->db_query("
                SELECT `content` FROM pages_content
                WHERE `name` = '". $this->DB->escape_string($namePage) ."'
            ", "assoc");
        if(!empty($result['0']['content']))
        {
            return htmlspecialchars_decode($result['0']['content']);
        } else {
            return null;
        }
    }

    public function setPagesContent($namePage, $html)
    {
        $this->DB->db_query("
            INSERT INTO pages_content SET
              `content` = '". $this->DB->escape_string($html) ."'
            WHERE `name` = '". $this->DB->escape_string($namePage) ."'
        ");
    }

    public function getPageProperty($name)
    {
        if($this->PAGE_INFO != null && !empty($this->PAGE_INFO[$name]))
        {
            return $this->PAGE_INFO[$name];
        } else {
            return null;
        }
    }

    /**
     * Function get value of the variable from the DB table `variables`
     *
     * @param $nameVariable - name of the variable from the BD table `variables`
     * @return string - value of the variable from the BD table `variables`
     */
    public function getVariable($nameVariable) {
        if(Validation::validVariable($nameVariable)) {
            $result = $this->DB->db_query("
                 SELECT `value`
                 FROM variables
                 WHERE `name` = '". $nameVariable ."'
              ", "assoc");
            return !empty($result['0']['value']) ? $result['0']['value'] : "";
        } else {
            return "";
        }
    }

    /**
     * Function return complete the text from the file /view/menu/$nameTemplate.php
     *
     * @param $nameMenu - name of the file with template menu[folder /view/menu/]
     * @return string
     */
    public static function getMenu($nameMenu) {
        return View::getMenu($nameMenu);
    }

    /**
     * @param $nameButton
     * @param string $text
     * @param string $link
     * @param string $id
     * @param string $title
     * @param string $target
     * @return string
     */
    public static function getButton($nameButton, $text = "", $link = "", $id = "", $title = "", $target = "")
    {
        return View::getButton($nameButton, $text = "", $link = "", $id = "", $title = "", $target = "") ;
    }

    /**
     * Function return complete the text from the file /view/template/$nameTemplate.php
     *
     * @param $nameTemplate - name of the file with template
     * @param array $data - array with variables what use in template[folder /view/template/]
     * @return string - return the text of the template
     */
    public static function getTemplate($nameTemplate, $data = array())
    {
        return View::getTemplate($nameTemplate, $data = array());
    }

    /**
     * Function create new variable in the system library of languages
     *
     * @param $var - name for new the variable in the system library of languages
     * @param $text - value of new variable in the system library of languages
     * @param null $lang - language for new variable, if null that get system language
     */
    public function addValueLanguageLib($var, $text, $lang = null)
    {
        $this->LANGUAGES->addValue($var, $text, $lang);
    }

    /**
     * Function change value of the variable $var in the system library of languages
     *
     * @param $var - name of the variable from the system library of languages
     * @param $text - new value for variable in the system library of languages
     * @param null $lang - language for new variable, if null that get system language
     */
    public function setValueLanguageLib($var, $text, $lang = null)
    {
        $this->LANGUAGES->setValue($var, $text, $lang);
    }

    /**
     * Function delete variable $var from the system library of languages
     *
     * @param $var - name of the variable from the system library of languages
     */
    public function deleteValueLanguageLib($var)
    {
        $this->LANGUAGES->deleteValue($var);
    }

    /**
     * Function return value of the variable $var from the system library of languages
     *
     * @param $var - name of the variable from the system library of languages
     * @param null $lang - language for new variable, if null that get system language
     * @return null|string - return value of the variable $var from the system library of languages
     */
    public function getValueLanguageLib($var, $lang = null)
    {
        return $this->LANGUAGES->getValue($var, $lang);
    }

    /**
     * Function for debug
     * print_r(all values from the languages library)
     */
    public function printLanguageLib() {
        $this->LANGUAGES->pr();
    }

    /**
     * @param $pageName
     * @param int $admin
     * @return mixed
     */
    public function getLink($pageName, $admin = 0)
    {
        return $this->LINKS->addLink($pageName, $admin);
    }

    /**
     * @param $pageName
     */
    public function printLink($pageName)
    {
        echo $this->LINKS->addLink($pageName);
    }

    /**
     * @param $query
     * @param string $type_return
     * @param bool $multi_query
     * @return array|bool|\mysqli_result|null
     */
    public function db_query($query, $type_return = 'non', $multi_query = false)
    {
        return $this->DB->db_query($query, $type_return, $multi_query);
    }

    public function navSubPages($activeSubPage, $allSize, $templateButton = "nav_sub_pages", $sizeItemOnPage = 10, $sizeButtonsActive = 5)
    {
        if($allSize > $sizeItemOnPage)
        {
            $sizeSubPages = (int)($allSize / $sizeItemOnPage) + ($allSize % $sizeItemOnPage != 0 ? 1 : 0);
            $arrayButtons = array();
            if($sizeSubPages <= $sizeButtonsActive)
            {
                for($i = 0; $i < $sizeSubPages; ++$i)
                    $arrayButtons[] = $i;

            } else if($activeSubPage < $sizeButtonsActive - 2) {
                $arrayButtons = array(0, 1, 2, 3, -1, $sizeSubPages-1);

            } else if($activeSubPage >= ($sizeSubPages - $sizeButtonsActive - 2)) {
                $arrayButtons = array(0, -1, $sizeSubPages-4, $sizeSubPages-3, $sizeSubPages-2, $sizeSubPages-1);

            } else {
                $arrayButtons = array(0, -1, $activeSubPage - 1, $activeSubPage, $activeSubPage + 1, -1, $sizeSubPages - 1);

            }

            $str = '<div class="count_button_'.count($arrayButtons).'">';
            foreach($arrayButtons AS $button)
            {

                if($button == -1)
                {
                    $str .= '<div>...</div>';
                } else
                    $str .= View::getButton($templateButton, $button + 1, '?p='. $button, "nav_".$button, "page " . ($button + 1));
            }
            echo $str;
        }

    }
}