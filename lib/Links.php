<?php
namespace lib;


class Links extends DB {
    private $LINKS = array();
    private $LINKS_ADMIN = array();
    private $Lang = "";
    private $isDefaultLang = false;
    private $template = "{linksTemplateId=[ID_NAME]}";

    public function __construct($Lang)
    {
        parent::__construct();
        $this->Lang = $Lang;
        $this->isDefaultLang = empty($_GET['lang']);
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
                SELECT p.name, p_i.url, p_i.lang
                FROM pages AS p
                JOIN pages_info AS p_i ON(p_i.page_id = p.id)
                WHERE p.name IN('". implode("','", $this->LINKS) ."')
                    AND p_i.lang = '" . $this->Lang . "'
                ", "assoc");
            $links = array();

            foreach ($result_ as $row)
            {
                $admin = "";
                if(in_array($row['name'], $this->LINKS_ADMIN)){
                    $admin = "admin/";
                }
                $links[$row['name']] = "http://" . $_SERVER['HTTP_HOST'] . ($this->isDefaultLang ? "" : $row['lang']) . "/". $admin . $row['url'];
            }
            return $links;
        } else
            return array();
    }

}