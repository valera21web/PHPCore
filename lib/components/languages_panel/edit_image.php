<?php
$result = 0;
$data = '';
define("D", DIRECTORY_SEPARATOR);
define("HM", getenv("DOCUMENT_ROOT"));
define("HMI", HM.'view'.D.'images');

if(!empty($_GET['event']) && !empty($_POST['image']))
{
    $pathImage = HMI.str_replace("/", D, $_POST['image']);
    if($_GET['event'] == "delete")
    {
        $result = unlink($pathImage) ? 1 : 0;
    }
    else if($_GET['event'] == "move")
    {
        $pathNew = D;
        if($_POST['new_folder'] != "root")
            $pathNew .= $_POST['new_folder'].D;
        $tmp = explode("/", $_POST['image']); // /nav/about_us_active_bg.png
        $pathNew .= $tmp[count($tmp)-1];
        $result = rename($pathImage, HMI.$pathNew) ? 1 : 0;
        $data = str_replace("\\","/",$pathNew);
    }
}

echo json_encode(array('status' => $result, 'data' => $data));