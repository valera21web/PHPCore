<?php
namespace lib;

class View
{
    /**
     * Function return complete the html from the file /view/form/$nameTemplate.php
     *
     * @param $nameForm - name of the file with template form[folder /view/form/]
     * @return string - return the html of the template
     */
    public static function getForm($nameForm, $data = array())
    {
        $result = "";
        require_once(getenv("DOCUMENT_ROOT").SP."lib".SP."components".SP."Form.php");
        $file = getenv("DOCUMENT_ROOT").SP.'view'.SP.'form'.SP.$nameForm.'.php';
        if(file_exists($file))
        {
            extract($data);
            ob_start();
            require($file);
            $result = ob_get_contents();
            ob_end_clean();
        }
        return $result;
    }

    /**
     * Function return complete the html from the file /view/menu/$nameTemplate.php
     *
     * @param $nameMenu - name of the file with template menu[folder /view/menu/]
     * @return string
     */
    public static function getMenu($nameMenu) {
        $result = "";
        $file = getenv("DOCUMENT_ROOT").SP.'view'.SP.'menu'.SP.$nameMenu.'.php';
        if(file_exists($file))
        {
            ob_start();
            require($file);
            $result = ob_get_contents();
            ob_end_clean();
        }
        return $result;
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
        $result = "";
        $file = DIR_HOME . SP. 'view'. SP .'button'. SP . $nameButton .'.php';
        if(file_exists($file)) {
            ob_start();
            require($file);
            $result = ob_get_contents();
            ob_end_clean();
        }
        return $result;
    }

    /**
     * Function return complete the html from the file /view/template/$nameTemplate.php
     *
     * @param $nameTemplate - name of the file with template
     * @param array $data - array with variables what use in template[folder /view/template/]
     * @return string - return the text of the template
     */
    public static function getTemplate($nameTemplate, $data = array()) {
        $result = "";
        $file = DIR_HOME . SP. 'view'. SP .'template'. SP . $nameTemplate .'.php';
        if(file_exists($file)) {
            extract($data);
            ob_start();
            require($file);
            $result = ob_get_contents();
            ob_end_clean();
        }
        return $result;
    }
}