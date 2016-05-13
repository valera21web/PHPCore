<?php
namespace lib;


class Links extends DB {
    private $LINKS = array();
    private $LINKS_ADMIN = array();
    private $Lang = "";
    private $isDefaultLang = false;
    private $template = "{linksTemplateId=[ID_NAME]}";

    private static $KEY_CACHE_LINKS = "KEY_LINKS";
    private $DATA = array();
    private $LOAD_LINKS = false;

    public function __construct($Lang)
    {
        parent::__construct();
        $this->Lang = $Lang;
        $this->isDefaultLang = empty($_GET['lang']);
        if(\lib\CacheManager::exist(self::$KEY_CACHE_LINKS))
            $this->DATA = \lib\CacheManager::get(self::$KEY_CACHE_LINKS);
        else
            $this->loadLinks();
    }

    public function addLink($pageName, $admin = 0)
    {
        if(!in_array($pageName, $this->LINKS)) {
            $this->LINKS[] = $pageName;
            if(!!$admin)
                $this->LINKS_ADMIN[] = $pageName;
        }
        return str_replace("ID_NAME", $pageName, $this->template);
    }

    public function replaceLinks($text)
    {
        $links = $this->getListLinks();
        foreach($links AS $name => $link)
        {
            $search = str_replace("ID_NAME", $name, $this->template);
            $text = str_replace($search, $link, $text);
        }
        return $text;
    }

    private function getListLinks()
    {
        if(!empty($this->LINKS))
        {

            $result_ = $this->db_query("
                SELECT p.name, p_i.url, p_i.lang, p.https
                FROM pages AS p
                JOIN pages_info AS p_i ON(p_i.page_id = p.id)
                WHERE p.name IN('". implode("','", $this->LINKS) ."')
                    AND p_i.lang = '" . $this->Lang . "'
                ", "assoc");

            $links = array();

            foreach ($result_ as $row)
            {
                $admin = "";
                if(in_array($row['name'], $this->LINKS_ADMIN))
                    $admin = "admin/";

                $links[$row['name']] = ($this->isDefaultLang ? "" : "/".$row['lang']) . "/". $admin . $row['url'];
            }
            return $links;
        } else
            return array();
    }

    public function getLink($name,  $typeUrl = 0, $admin = 0)
    {
        if(array_key_exists($name, $this->DATA))
        {
            switch($typeUrl)
            {
                case 0:
                    return (!!$admin ? "/admin" : "").($this->isDefaultLang ? "" : "/".$this->Lang)."/".$this->DATA[$name]['url'];
                    break;
                case 1:
                    return $this->DATA[$name]['url'];
                    break;
            }
        }
        else if(!$this->LOAD_LINKS)
        {
            $this->loadLinks();
            return getLink($name,  $typeUrl, $admin);
        }
        return "/404";
    }

    private function loadLinks()
    {
        $result_ = $this->db_query("
                SELECT p.name, p_i.url, p_i.lang
                FROM pages AS p
                JOIN pages_info AS p_i ON(p_i.page_id = p.id)
                WHERE p_i.lang = '" . $this->Lang . "'
                ", "assoc");

        foreach ($result_ as $item)
            $this->DATA[$item['name']] = $item;
        \lib\CacheManager::set(self::$KEY_CACHE_LINKS, $this->DATA);
        $this->LOAD_LINKS = true;
    }
}