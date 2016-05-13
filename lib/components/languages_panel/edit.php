<?php
session_start();
//if(empty($_SESSION['admin']))
//    header("Location: /admin");


define("SP", DIRECTORY_SEPARATOR); // separator for the folders
define("DIR_HOME", str_replace(SP . "lib" . SP . "components" . SP . "languages_panel", "", __DIR__));
require_once(DIR_HOME . SP . "lib" . SP . "Languages.php");

$SETTINGS = simplexml_load_file(DIR_HOME . SP ."config.xml");
$SETTINGS = json_decode(json_encode($SETTINGS), true);
$LANG = new \lib\Languages($SETTINGS['languages']['default'], $SETTINGS['languages']);
$LANG->initAllLanguages();

if(!empty($_GET['page']))
{
    if($_GET['page'] == "save")
    {
        if(!empty($_POST['id_value']))
        {
            if($_POST['id_value'] == 'new') 
            {
                foreach($LANG->getLanguages() AS $language)
                    if(!empty($_POST["textarea_".$language]))
                        $LANG->addValue($_POST['new_id'], $_POST["textarea_".$language], $language);
                header("Location: ". str_replace("?val=new", "?val=".$_POST['new_id'], $_SERVER['HTTP_REFERER']));
            }
            else 
            {
                foreach($LANG->getLanguages() AS $language)
                    if(!empty($_POST["textarea_".$language]))
                        $LANG->setValue($_POST['id_value'], $_POST["textarea_".$language], $language);
                header("Location: ". $_SERVER['HTTP_REFERER']);
            }
        }
    }
    else if($_GET['page'] == "delete" && !empty($_POST['id_value_delete']))
    {
        $a = explode("?", $_SERVER['HTTP_REFERER']);
        $LANG->deleteValue($_POST['id_value_delete']);
        header("Location: ".$a[0]);
    }
}