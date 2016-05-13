<?php


namespace components;


class Image
{
    /**
     * @param $_file - $_FILES['file']
     * @param $max_width
     * @param $max_height
     * @param $src_save
     * @param int $max_size - max size of the file in MB
     * @param $rename = true
     * @return null|string
     */
    public static function reSize($_file, $max_width, $max_height, $src_save, $max_size = 5, $rename = true)
    {
        try
        {
            $fileName = null;
            $file = $_file['tmp_name'];
            $size = filesize($file);
            list(, $typeImg) = explode("/", $_file['type']);
            $typeImg = strtolower($typeImg);
            if ($size <= ((int)$max_size * 1024 * 1024) && in_array($typeImg, array("jpg", "jpeg", "png", "gif"))) {
                if ($typeImg == "jpg" || $typeImg == "jpeg") {
                    $src = imagecreatefromjpeg($file);
                } else if ($typeImg == "png") {
                    $src = imagecreatefrompng($file);
                } else {
                    $src = imagecreatefromgif($file);
                }

                list($width, $height) = getimagesize($file);
                $koef_img = $height / $width;
                $koef_new_img = $max_height / $max_width;
                if ($koef_img >= $koef_new_img) {
                    $newHeight = $max_height;
                    $newWidth = $max_height / $koef_img;
                } else {
                    $newWidth = $max_width;
                    $newHeight = $max_width * $koef_img;
                }
                $tmp = imagecreatetruecolor($newWidth, $newHeight);

                imagecopyresampled($tmp, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

                $fileName = Image::CorrectName($_file["name"], $typeImg, $rename);
                $fileNameSrc = $src_save.$fileName;

                imagejpeg($tmp, $fileNameSrc, 100);
                imagedestroy($src);
                imagedestroy($tmp);
            }
            return $fileName;
        }
        catch (\Exception $e)
        {
            return null;
        }
    }

    /**
     * @param $_file - $_FILES['file']
     * @param $src_save
     * @param $rename = true
     * @return null|string
     */
    public static function Save($_file, $src_save, $rename = true)
    {
        $fileName = null;
        list(, $typeImg) = explode("/", $_file['type']);
        $typeImg = strtolower($typeImg);
        if (in_array($typeImg, array("jpg", "jpeg", "png", "gif")))
        {
            $fileName = Image::CorrectName($_file["name"], $typeImg, $rename);
            move_uploaded_file($_file['tmp_name'], $src_save.$fileName);
        }
        return $fileName;
    }

    public static function CorrectName($nameImg, $typeImg, $rename)
    {
        $newName = "";
        if(!$rename)
        {
            $nameImg = strtolower($nameImg);
            $length = strlen($nameImg);
            for ($i = 0; $i < $length; ++$i)
            {
                if(($nameImg[$i] >= 'a' && $nameImg[$i] <= 'z') || ($nameImg[$i] >= '0' && $nameImg[$i] <= '9'))
                    $newName .= "".$nameImg[$i];
                else if($nameImg[$i] == ' ' || $nameImg[$i] == '_' || $nameImg[$i] == '-')
                    $newName .= "_";
            }
        }

        if($newName == "")
            $newName = md5($nameImg.date("Y-m-d H:i:s").$typeImg);
        return $newName.".".$typeImg;
    }
}