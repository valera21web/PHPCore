<?php
require_once (__DIR__ . SP . "System.php");

function getLink($pageName, $admin = 0)
{
   global $SYSTEM;
   return $SYSTEM->getLink($pageName, $admin);
}

function printLink($pageName)
{
   global $SYSTEM;
   $SYSTEM->printLink($pageName);
}

function getInfoPage($pageName)
{
   global $SYSTEM;
   return $SYSTEM->getInfoPage($pageName);
}

function openPage($src)
{
   global $SYSTEM;
   return $SYSTEM->openPage($src);
}

function getMenu($nameMenu) {
   return \lib\View::getMenu($nameMenu);
}

function getButton($nameButton, $text = "", $link = "", $id = "", $title = "", $target = "")
{
   return \lib\View::getButton($nameButton, $text, $link, $id, $title, $target) ;
}

function getTemplate($nameTemplate, $data = array())
{
   return \lib\View::getTemplate($nameTemplate, $data);
}

function getVariable($nameVariable)
{
   global $SYSTEM;
   return $SYSTEM->getVariable($nameVariable);
}

function __($var, $lang = null)
{
   global $SYSTEM;
   return $SYSTEM->getValueLanguageLib($var, $lang);
}