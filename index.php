<?php
session_start();
if(!defined("SP"))
    define("SP", DIRECTORY_SEPARATOR); // separator for the folders

if(!defined("DIR_HOME"))
    define("DIR_HOME", __DIR__);

require_once (DIR_HOME . SP . "lib" . SP . "System.php");

class Main extends \lib\System
{
    public function run()
    {
        echo $this->openPage(
            !empty($_GET['page']) ?
                trim($_GET['page']) :
                $this->getSetting("default_page")
        );
    }
} new Main();