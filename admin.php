<?php
session_start();
define("SP", DIRECTORY_SEPARATOR);
define("DIR_HOME", __DIR__);
require_once (DIR_HOME . SP . "lib" . SP . "System.php");

class Main extends \lib\System
{
   function __construct() { parent::__construct(true); }

   public function run()
   {
      if(!empty($_SESSION['admin']['id']))
      {
         echo $this->openPage(
             !empty($_GET['page']) ?
                 trim($_GET['page']) :
                 $this->getSetting("default_page_admin")
         );
      } else {
         if(!empty($_GET['page']) && $_GET['page'] == "admin_authorization") {
            echo $this->openPage("admin_authorization");
         } else {
            header("Location: /admin/admin_authorization");
         }
      }
   }
} new Main();