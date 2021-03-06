<?php
session_start();

//if(empty($_SESSION['admin']))
//    header("Location: /admin");


define("SP", DIRECTORY_SEPARATOR); // separator for the folders
define("DIR_HOME", str_replace(SP . "lib" . SP . "components" . SP . "languages_panel", "", __DIR__));
require_once(DIR_HOME . SP . "lib" . SP . "Languages.php");
require_once(DIR_HOME . SP . "lib" . SP . "components" . SP ."Form.php");

$SETTINGS = simplexml_load_file(DIR_HOME . SP ."config.xml");
$SETTINGS = json_decode(json_encode($SETTINGS), true);

//echo '<pre>';
//print_r($SETTINGS);
//return;

$LANG = new \lib\Languages($SETTINGS['languages']['@attributes']['default'], $SETTINGS['languages']);
$LANG->initAllLanguages();

$page_content = "";
ob_start();
require(__DIR__ . SP . 'page.php');
$page_content = ob_get_contents();
ob_end_clean();

ob_start();
require(__DIR__ . SP . 'template.php');
$result = ob_get_contents();
ob_end_clean();

echo $result;