<?php
if(!empty($_FILES)&& !empty($_FILES['upload']))
{
    define("D", DIRECTORY_SEPARATOR);
    define("HM", getenv("DOCUMENT_ROOT"));
    require_once(HM.'lib'.D.'components'.D.'Image.php');

    $path = HM.'view'.D.'images'.D.'uploads'.D;
    $file_name = \components\Image::Save($_FILES['upload'], $path);
    echo "/img/uploads/".$file_name;
}
else
{
    echo 'sorry, by not upload file';
}