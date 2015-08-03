<?php

session_start();

//if(empty($_SESSION['admin']['id']))
//    header("Location: /admin/admin_authorization");


define("SP", DIRECTORY_SEPARATOR); // separator for the folders
define("DIR_HOME", str_replace(SP."lib".SP."components".SP."languages", "",__DIR__));
require_once(DIR_HOME . SP . "lib" . SP . "Languages.php");
require_once(DIR_HOME . SP . "lib" . SP . "components" . SP ."Form.php");

$SETTINGS = simplexml_load_file(DIR_HOME . SP ."config.xml");
$LANG = new \lib\Languages($SETTINGS->laguages['default'], $SETTINGS->laguages);
$LANG->initAllLanguages();

$page_content = "";
ob_start();
require(__DIR__ . SP . 'page_languages.php');
$page_content = ob_get_contents();
ob_end_clean();

ob_start();
require(__DIR__ . SP . 'template_languages.php');
$result = ob_get_contents();
ob_end_clean();

echo $result;
