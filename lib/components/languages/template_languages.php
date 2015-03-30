<!DOCTYPE html>
<html>
   <head>
      <meta charset=utf-8>
      <title>Languages</title>
      <script src="/js/jquery.js" type="text/javascript"></script>
      <style type="text/css">
          a, li, ul {
              text-decoration: none;
              list-style: none;
          }

          body {
              width: 960px;
              margin: 50px auto;
              background: #999999;
          }

          aside {
              display: block;
              float: left;
              width: 200px;
              min-height: 300px;
              margin: 0 20px 0 0;
              background: #CCCCCC;
          }

          aside ul {
              max-height: 500px;
              overflow-y: auto;
          }

          section {
              display: block;
              float: left;
              width: 740px;
              min-height: 300px;
              background: #CCCCCC;
              margin: 0;
          }

          section form {
              margin: 20px 0 0 50px;
          }

          section form,
          section form textarea {
              width: 400px;
              min-height: 100px;
          }

          section form div {
              padding: 10px 0;
          }

          #search_lang {
              margin: 10px 0 0 37px;
          }
      </style>
      <script type="text/javascript">
          var list_lang_vars =  Array();
          $(function()
          {
              $("aside > nav > ul > li > a").each(function(k,v)
              {
                  var tmp = $(this).text();
                  if(tmp != "New") {
                      list_lang_vars[k] = {"index": k,"text": tmp}
                  }
              });

              $("#search_lang").keyup(function()
              {
                  filterLanguagesVarsBySearch($(this).val());
              });
          });

          function filterLanguagesVarsBySearch(str)
          {
              if(str != null && str != "") {
                  str = str.toLowerCase();
                  $("aside > nav > ul > li > a").each(function()
                  {
                      var tmp = $(this).text().toLowerCase();
                      if(tmp != "new")
                      {
                          if(tmp.indexOf(str) == -1)
                          {
                              $(this).parent("li").hide();
                          }
                      } else {
                          $(this).parent("li").hide();
                      }
                  });
              } else {
                  $("aside > nav > ul > li").show();
              }
          }
      </script>
   </head>
   <body>
      <aside>
         <nav>
             <input type="search" id="search_lang" name="search_lang" placeholder="Search" />
            <ul>
                <li><a href="/lang/?lang=new" target="_self" >New</a></li>
<?php
    $list = $LANG->getNames();
    asort($list, SORT_STRING);
    foreach($list AS $lang) {
        echo '<li><a href="/lang/?lang='. $lang .'" target="_self" >'. $lang .'</a></li>';
    }
?>
            </ul>
         </nav>
      </aside>
      <section>
         <?=$page_content;?>
      </section>
   </body>
</html>