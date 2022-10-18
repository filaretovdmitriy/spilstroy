<?php

$q = 100;

$file_path = dirname(__FILE__);
$pic = $_GET['pic'];

$cropp_w = isset($_GET['w']) ? intval($_GET['w']) : 100;
$cropp_h = isset($_GET['h']) ? intval($_GET['h']) : 100;
$transform_type = isset($_GET['tp']) ? intval($_GET['tp']) : 1;
$useDummy =  isset($_GET['d']);

if (file_exists($file_path . '/' . $pic) === false && $useDummy) {
    $dummy = imagecreate($cropp_w, $cropp_h);
    $colorHandle = imagecolorallocate($dummy, 192, 192, 192);
    $colorCross = imageColorAllocate($dummy, 0, 0, 0);
    imageline($dummy, -1, $cropp_h, $cropp_w, -1, $colorCross);
    imageline($dummy, 0, 0, $cropp_w, $cropp_h, $colorCross);
    $sizeString = $cropp_w . ' x ' . $cropp_h;
    $strlen = strlen($sizeString);
    $widthString = ($strlen * 6) + ($strlen - 1);
    $left = round(($cropp_w - $widthString) / 2);
    $top = round(($cropp_h - 16)/2);

    imagefilledrectangle($dummy, $left - 5, $top - 3, $left + $widthString + 5, $top + 19, $colorHandle);
    imagestring($dummy, 3, $left, $top,  $sizeString, $colorCross);
    imagepng($dummy);
    header('Content-type: image/png');
} else {
    $pic_info = getimagesize($file_path . '/' . $pic);

    if ($pic_info === false && preg_match('(^.*\.svg$)', $pic) === 1) {
        header('Content-type: image/svg+xml');
        echo file_get_contents($file_path . '/' . $pic);
        exit(0);
    }

    $src = '';
    $tmp_image = '';
    if ($pic_info[2] == 1) {
        $src = imagecreatefromgif($pic);
    } elseif ($pic_info[2] == 2) {
        $src = imagecreatefromjpeg($pic);
    } elseif ($pic_info[2] == 3) {
        $src = imagecreatefrompng($pic);
    }

    $src_w = $pic_info[0];
    $src_h = $pic_info[1];
    $destroySrc = true;
    if ($src_w < $cropp_w && $src_h < $cropp_h) {
        $dest = $src;
        if ($pic_info[2] == 3) {
            imagealphablending($dest, true);
            imagesavealpha($dest, true);
            $color = imagecolorallocatealpha($dest, 0, 0, 0, 127);
            imagefill($dest, 0, 0, $color);
        }
        $destroySrc = false;
    } else {
        switch ($transform_type) {
            case '1':
                $new_width = $cropp_w;
                $new_height = $src_h * $new_width / $src_w;
                $offset_x = 0;
                $offset_y = 0;
                if ($cropp_h >= $new_height) {
                    $new_height = $cropp_h;
                    $new_width = $src_w * $new_height / $src_h;
                    $offset_x = ($new_width - $cropp_w) / 2;
                    $cropp_h = ($cropp_h >= $new_height) ? $new_height : $cropp_h;
                    $tmp_image = imagecreatetruecolor($new_width, $new_height);
                    $dest = imagecreatetruecolor($cropp_w, $cropp_h);
                    if ($pic_info[2] == 3) {
                        imagealphablending($dest, true);
                        imagesavealpha($dest, true);
                        $color = imagecolorallocatealpha($dest, 0, 0, 0, 127);
                        imagefill($dest, 0, 0, $color);
                        imagealphablending($tmp_image, true);
                        imagesavealpha($tmp_image, true);
                        $color = imagecolorallocatealpha($tmp_image, 0, 0, 0, 127);
                        imagefill($tmp_image, 0, 0, $color);
                    }
                    imagecopyresampled($tmp_image, $src, 0, 0, 0, 0, $new_width, $new_height, $src_w, $src_h);
                    imagecopy($dest, $tmp_image, 0, 0, $offset_x, 0, $cropp_w, $cropp_h);
                }else{
                    $new_width = $cropp_w;
                    $new_height = $src_h * $new_width / $src_w;
                    $offset_y = ($new_height - $cropp_h) / 2;
                    $cropp_w = ($cropp_w >= $new_width) ? $new_width : $cropp_w;
                    $tmp_image = imagecreatetruecolor($new_width, $new_height);
                    $dest = imagecreatetruecolor($cropp_w, $cropp_h);
                    if ($pic_info[2] == 3) {
                        imagealphablending($dest, true);
                        imagesavealpha($dest, true);
                        $color = imagecolorallocatealpha($dest, 0, 0, 0, 127);
                        imagefill($dest, 0, 0, $color);
                        imagealphablending($tmp_image, true);
                        imagesavealpha($tmp_image, true);
                        $color = imagecolorallocatealpha($tmp_image, 0, 0, 0, 127);
                        imagefill($tmp_image, 0, 0, $color);
                    }
                    imagecopyresampled($tmp_image, $src, 0, 0, 0, 0, $new_width, $new_height, $src_w, $src_h);
                    imagecopy($dest, $tmp_image, 0, 0, 0, $offset_y, $cropp_w, $cropp_h);
                }


                break;
            case '2':
                if ($src_w >= $src_h) {
                    $new_width = $cropp_w;
                    $new_height = $src_h * $new_width / $src_w;
                    $cropp_h = ($cropp_h >= $new_height) ? $new_height : $cropp_h;
                    $tmp_image = imagecreatetruecolor($new_width, $new_height);
                    $dest = imagecreatetruecolor($cropp_w, $cropp_h);
                    if ($pic_info[2] == 3) {
                        imagealphablending($dest, false);
                        imagesavealpha($dest, true);
                        $color = imagecolorallocatealpha($dest, 0, 0, 0, 127);
                        imagefill($dest, 0, 0, $color);
                        imagealphablending($tmp_image, true);
                        imagesavealpha($tmp_image, true);
                        $color = imagecolorallocatealpha($tmp_image, 0, 0, 0, 127);
                        imagefill($tmp_image, 0, 0, $color);
                    }
                    imagecopyresampled($tmp_image, $src, 0, 0, 0, 0, $new_width, $new_height, $src_w, $src_h);
                    imagecopy($dest, $tmp_image, 0, 0, 0, 0, $cropp_w, $cropp_h);
                } else {
                    $new_height = $cropp_h;
                    $new_width = $src_w * $new_height / $src_h;
                    $dest = imagecreatetruecolor($new_width, $new_height);
                    if ($pic_info[2] == 3) {
                        imagealphablending($dest, true);
                        imagesavealpha($dest, true);
                        $color = imagecolorallocatealpha($dest, 0, 0, 0, 127);
                        imagefill($dest, 0, 0, $color);
                    }
                    imagecopyresampled($dest, $src, 0, 0, 0, 0, $new_width, $new_height, $src_w, $src_h);
                }
                break;
            case '3':
                if ($src_w >= $src_h) {
                    $new_height = $cropp_h;
                    $new_width = $src_w * $new_height / $src_h;
                    $dest = imagecreatetruecolor($new_width, $new_height);
                    if ($pic_info[2] == 3) {
                        imagealphablending($dest, true);
                        imagesavealpha($dest, true);
                        $color = imagecolorallocatealpha($dest, 0, 0, 0, 127);
                        imagefill($dest, 0, 0, $color);
                    }
                    imagecopyresampled($dest, $src, 0, 0, 0, 0, $new_width, $new_height, $src_w, $src_h);
                } else {
                    $new_width = $cropp_w;
                    $new_height = $src_h * $new_width / $src_w;
                    $cropp_h = ($cropp_h >= $new_height) ? $new_height : $cropp_h;
                    $tmp_image = imagecreatetruecolor($new_width, $new_height);
                    $dest = imagecreatetruecolor($cropp_w, $cropp_h);
                    if ($pic_info[2] == 3) {
                        imagealphablending($dest, true);
                        imagesavealpha($dest, true);
                        $color = imagecolorallocatealpha($dest, 0, 0, 0, 127);
                        imagefill($dest, 0, 0, $color);
                        imagealphablending($tmp_image, true);
                        imagesavealpha($tmp_image, true);
                        $color = imagecolorallocatealpha($tmp_image, 0, 0, 0, 127);
                        imagefill($tmp_image, 0, 0, $color);
                    }
                    imagecopyresampled($tmp_image, $src, 0, 0, 0, 0, $new_width, $new_height, $src_w, $src_h);
                    imagecopy($dest, $tmp_image, 0, 0, 0, 0, $cropp_w, $cropp_h);
                }
                break;
            case '4':
                if ($src_w >= $src_h) {
                    $new_height = $cropp_h;
                    $new_width = $src_w * $new_height / $src_h;
                    $cropp_w = $new_width;
                    $tmp_image = imagecreatetruecolor($new_width, $new_height);
                    $dest = imagecreatetruecolor($cropp_w, $cropp_h);
                    if ($pic_info[2] == 3) {
                        imagealphablending($dest, true);
                        imagesavealpha($dest, true);
                        $color = imagecolorallocatealpha($dest, 0, 0, 0, 127);
                        imagefill($dest, 0, 0, $color);
                        imagealphablending($tmp_image, true);
                        imagesavealpha($tmp_image, true);
                        $color = imagecolorallocatealpha($tmp_image, 0, 0, 0, 127);
                        imagefill($tmp_image, 0, 0, $color);
                    }
                    imagecopyresampled($tmp_image, $src, 0, 0, 0, 0, $new_width, $new_height, $src_w, $src_h);
                    imagecopy($dest, $tmp_image, 0, 0, 0, 0, $cropp_w, $cropp_h);
                } else {
                    $new_height = $cropp_h;
                    $new_width = $src_w * $new_height / $src_h;
                    $dest = imagecreatetruecolor($new_width, $new_height);
                    if ($pic_info[2] == 3) {
                        imagealphablending($dest, true);
                        imagesavealpha($dest, true);
                        $color = imagecolorallocatealpha($dest, 0, 0, 0, 127);
                        imagefill($dest, 0, 0, $color);
                    }
                    imagecopyresampled($dest, $src, 0, 0, 0, 0, $new_width, $new_height, $src_w, $src_h);
                }
                break;
            default:
                $new_width = $cropp_w;
                $new_height = $src_h * $new_width / $src_w;
                $cropp_h = ($cropp_h >= $new_height) ? $new_height : $cropp_h;
                $tmp_image = imagecreatetruecolor($new_width, $new_height);
                $dest = imagecreatetruecolor($cropp_w, $cropp_h);
                if ($pic_info[2] == 3) {
                    imagealphablending($dest, true);
                    imagesavealpha($dest, true);
                    $color = imagecolorallocatealpha($dest, 0, 0, 0, 127);
                    imagefill($dest, 0, 0, $color);
                    imagealphablending($tmp_image, true);
                    imagesavealpha($tmp_image, true);
                    $color = imagecolorallocatealpha($tmp_image, 0, 0, 0, 127);
                    imagefill($tmp_image, 0, 0, $color);
                }
                imagecopyresampled($tmp_image, $src, 0, 0, 0, 0, $new_width, $new_height, $src_w, $src_h);
                imagecopy($dest, $tmp_image, 0, 0, 0, 0, $cropp_w, $cropp_h);
                break;
        }
    }
    if (!empty($tmp_image)) {
        imagedestroy($tmp_image);
    }
    if ($destroySrc) {
        imagedestroy($src);
    }
    header("Content-type: " . image_type_to_mime_type($pic_info[2]));
    if ($pic_info[2] == 1) {
        imagegif($dest);
    } elseif ($pic_info[2] == 2) {
        imagejpeg($dest, null, $q);
    } elseif ($pic_info[2] == 3) {
        imagepng($dest);
    }
    imagedestroy($dest);
}
