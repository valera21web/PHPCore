<?php
session_start();
define("SP", "/"); // separator for the folders
define("DIR_HOME", __DIR__);
require_once (DIR_HOME . SP . "lib" . SP . "function.php");


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
