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
     * @return null|string
     */
    public static function reSize($_file, $max_width, $max_height, $src_save, $max_size = 5)
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

            $fileName = md5($_file["name"].date("Y-m-d H:i:s").$typeImg.$newWidth.$newHeight) . "." . $typeImg;
            $fileNameSrc = $src_save. $fileName;

            imagejpeg($tmp, $fileNameSrc, 100);
            imagedestroy($src);
            imagedestroy($tmp);
        }
        return $fileName;
    }

    /**
     * @param $_file - $_FILES['file']
     * @param $src_save
     * @return null|string
     */
    public static function Save($_file, $src_save)
    {
        $fileName = null;
        list(, $typeImg) = explode("/", $_file['type']);
        $typeImg = strtolower($typeImg);
        if (in_array($typeImg, array("jpg", "jpeg", "png", "gif")))
        {
            $fileName = md5($_file["name"].date("Y-m-d H:i:s").$typeImg) . "." . $typeImg;
            move_uploaded_file($_file['tmp_name'], $src_save.$fileName);
        }
        return $fileName;
    }

}