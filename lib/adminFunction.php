<?php
require_once (DIR_HOME.SP."lib".SP."function.php");


function getInfoAdminPage($pageName) {
   global $DB;
   if(Validation::validPageName($pageName) && file_exists("pages/admin/". $pageName .".php")) {
      $result_ = $DB->db_query("
         SELECT * FROM pages
         WHERE `name` = '". $pageName ."' AND `admin` = 1
         LIMIT 1
      ", "assoc");

      if(empty($result_['0']['template'])) {
         return array(0 => array(
            "name" => $pageName,
            "title" => "",
            "description" => "",
            "template" => "default"
         ));
      } else {
         if($result_['0']['visible'] != "0") {
            $result = $result_['0'];
            return $result;
         } else {
            return false;
         }
      }
   } else {
      return false;
   }
}

function openAdminPage($src) {
   $page_content = "";
   $pageInfo = getInfoAdminPage($src);
   if($pageInfo != false) {
      $result = "";
      ob_start();
      require('pages/admin/'. $src .'.php');
      $page_content = ob_get_contents();
      ob_end_clean();

      ob_start();
      require('templates/'. $pageInfo['template'] .'.php');
      $result = ob_get_contents();
      ob_end_clean();

      return $result;
   } else {
      header("Location: 404.html");
   }
}

function getAdminListUsers() {
   global $SYSTEM;
   $result = "";
   $rows = $SYSTEM->db_query("
      SELECT
         `login`,
         `name`,
         `position`,
         `avatar_sm` AS image
      FROM users
      ORDER BY created DESC
   ", "assoc");

   $result = "<div id='listAdminItemsUsers'>";
   foreach($rows AS $user) {
      $user['link'] = "/admin/admin_users/". $user['login'];
      $result .= getTemplate("itemAdminUsers", $user);
   }
   $result .= "</div>";

   return $result;
}

function getAdminListNews() {
   global $DB;
   $result = "";
   $rows = $DB->db_query("
      SELECT
         `id`,
         `title`,
         `description`,
         `image`
      FROM news
      WHERE birthDay = 0
      ORDER BY startShow DESC
   ", "assoc");

   $result = "<div id='listAdminItemsNews'>";
   foreach($rows AS $news) {
      $news['link'] = "/admin/admin_news/".$news['id'];
      $result .= getTemplate("newsAdmin", $news);
   }
   $result .= "</div>";

   return $result;
}

function getAdminGallery() {
   global $DB;
   $result = "";
   $rows = $DB->db_query("
      SELECT
         `index`,
         `photo`,
         `visible`
      FROM gallery
      ORDER BY `index`
   ", "assoc");

   $i = 0;
   $result .= "<div class='fullGallery'>";
   $result .= '<table cellspacing="0" cellpadding="0" border="0" class="photoInGallery"><tr>';
   foreach($rows AS $photo) {
      if($i == 3) {
         $i = 0;
         $result .= "</tr><tr>";
      } else {
         ++$i;
      }
      $result .= "<td>";
      $result .= '
         <table cellspacing="0" cellpadding="0" border="0">
            <tr>
               <td colspan="2" style="height: 180px;">
                  <img class="contestImgAdmin" src="/images/gallery/'. $photo['photo'] .'" />
               </td>
            </tr>
            <tr>
               <td colspan="2" style="height: 10px;"></td>
            </tr>
            <tr>
               <td colspan="2">
                  <span style="padding: 0 5px;">Номер: </span>
                  <input type="text" name="indexPhotoInGallery" class="indexPhotoInGallery" value="'. $photo['index'] .'" />
               </td>
            </tr>
            <tr>
               <td colspan="2">
                  <span style="padding: 0 5px;">Отображать: </span>
                  <input type="checkbox" name="visiblePhoto" id="visiblePhoto"  '. ((int)$photo['visible'] == 1 ? "checked" : "").' />
               </td>
            </tr>
            <tr>
               <td>
                  <input type="button" name="updatePhotoInGallery" class="updatePhotoInGallery" value="Обновить" />
               </td>
               <td>
                  <input type="button" name="deletePhotoInGallery" class="deletePhotoInGallery" value="Удалить" />
               </td>
            </tr>
         </table>
      ';
      $result .= "</td>";
   }
   $result .= "</tr></table>";
   $result .= "</div>";

   return $result;
}