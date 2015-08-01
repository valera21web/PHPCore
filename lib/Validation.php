<?php
namespace lib;

class Validation {

   static public function validLogin($variable) {
      return !!preg_match("/^[0-9a-zA-Z_]{3,}$/", $variable);
   }

   static public function validPassword($variable) {
      return !!preg_match("/^[0-9a-zA-Z_-]{8,}$/", $variable);
   }

   static public function validPageName($variable) {
      return !!preg_match("/^[0-9a-z_]{3,}$/", $variable);
   }

   static public function validEmail($variable)
   {
       return !!preg_match("/^[A-Za-z0-9\_\-]{3,}@[a-z0-9\-\_]{1,}\.[A-Za-z]{2,3}$/", $variable);
   }

   static public function validPhone($variable) {
      return !!preg_match("/^((\+|00)(\ |\-|)[0-9]{2}|[0-9]{2}|)(\ |\-|)[0-9]{3}(\ |\-|)[0-9]{3}(\ |\-|)[0-9]{3}$/", $variable);
   }

   static public function validInteger($variable) {
      return !!filter_var($variable, FILTER_VALIDATE_INT);
   }

   static public function validYear($variable) {
      return !!preg_match("/^[0-9]{4}$/", $variable);
   }

   static public function validDate($variable) {
      return !!preg_match("/^[0-9]{4}\-[0-9]{2}\-[0-9]{2}$/", $variable);
   }

   static public function validDateTime($variable) {
      return !!preg_match("/^[0-9]{4}\-[0-9]{2}\-[0-9]{2}\ [0-9]{2}\:[0-9]{2}\:[0-9]{2}$/", $variable);
   }

   static public function validImageName($variable) {
      return !!preg_match("/^[0-9a-z_]{1,}\.[0-9a-z]{3,4}$/", $variable);
   }

}