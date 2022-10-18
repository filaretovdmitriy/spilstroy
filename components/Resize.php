<?php

namespace app\components;

class Resize
{

    const DEBUG = false;

    private $_imagePath = null;
    private $_width = 100;
    private $_height = 100;
    private $_type = 1;
    private $_waterMark = false;
    private $_quality = 100;
    private $_info = [];
    private $_source = null;
    private $_result = null;
    private $_defaultWatermarkOptions = [
        'center' => false,
        'fill' => false,
        'x' => 10,
        'y' => 10,
    ];

    function getImagePath()
    {
        return $this->_imagePath;
    }

    function getWidth()
    {
        return $this->_width;
    }

    function setWidth($width)
    {
        $this->_width = $width;
        return $this;
    }

    function getHeight()
    {
        return $this->_height;
    }

    function setHeight($height)
    {
        $this->_height = $height;
        return $this;
    }

    function getType()
    {
        return $this->_type;
    }

    function setType($type)
    {
        $this->_type = $type;
        return $this;
    }

    function getWaterMark()
    {
        return $this->_waterMark;
    }

    function setWaterMark($waterMark)
    {
        $this->_waterMark = $waterMark;
        return $this;
    }

    function getQuality()
    {
        return $this->_quality;
    }

    function setQuality($quality)
    {
        $this->_quality = $quality;
        return $this;
    }

    public function __construct($imagePath)
    {
        $this->_imagePath = $imagePath;

        $imageInfo = getimagesize($imagePath);
        if ($imageInfo[2] == IMAGETYPE_GIF) {
            $this->_source = imagecreatefromgif($imagePath);
        } elseif ($imageInfo[2] == IMAGETYPE_JPEG) {
            $this->_source = imagecreatefromjpeg($imagePath);
        } elseif ($imageInfo[2] == IMAGETYPE_PNG) {
            $this->_source = imagecreatefrompng($imagePath);
        }

        if (is_null($this->_source) === true) {
            throw new \yii\web\ServerErrorHttpException('Неподдерживаемый формат изображения - ' . $imagePath);
        }

        if ($this->_source === false) {
            throw new \yii\web\ServerErrorHttpException('Не удалось открыть изображение - ' . $imagePath);
        }

        $this->_info = $imageInfo;
    }

    public function __destruct()
    {
        if (is_null($this->_source) === false) {
            imagedestroy($this->_source);
        }

        if (is_null($this->_result) === false) {
            imagedestroy($this->_result);
        }
    }

    public function copyOriginal()
    {
        $this->_result = imagecreatetruecolor($this->_info[0], $this->_info[1]);
        imagecopy($this->_result, $this->_source, 0, 0, 0, 0, $this->_info[0], $this->_info[1]);
        if ($this->_info[2] == IMAGETYPE_PNG) {
            imagealphablending($this->_result, true);
            imagesavealpha($this->_result, true);
            $color = imagecolorallocatealpha($this->_result, 0, 0, 0, 127);
            imagefill($this->_result, 0, 0, $color);
        }
    }

    public function resize()
    {
        $sourceWidth = $this->_info[0];
        $sourceHeight = $this->_info[1];

        if ($sourceWidth < $this->_width && $sourceHeight < $this->_height && $this->_type != 5) {
            $this->copyOriginal();
        } else {
            switch ($this->_type) {
                case 1:
                    $newWidth = $this->_width;
                    $newHeight = $sourceHeight * $newWidth / $sourceWidth;
                    $offsetX = $offsetY = 0;
                    if ($this->_height >= $newHeight) {
                        $newHeight = $this->_height;
                        $newWidth = $sourceWidth * $newHeight / $sourceHeight;
                        $offsetX = ($newWidth - $this->_width) / 2;
                        $this->_height = ($this->_height >= $newHeight) ? $newHeight : $this->_height;
                        $tempImage = imagecreatetruecolor($newWidth, $newHeight);
                        $this->_result = imagecreatetruecolor($this->_width, $this->_height);
                        if ($this->_info[2] == IMAGETYPE_PNG) {
                            imagealphablending($this->_result, true);
                            imagesavealpha($this->_result, true);
                            $color = imagecolorallocatealpha($this->_result, 0, 0, 0, 127);
                            imagefill($this->_result, 0, 0, $color);
                            imagealphablending($tempImage, true);
                            imagesavealpha($tempImage, true);
                            $color = imagecolorallocatealpha($tempImage, 0, 0, 0, 127);
                            imagefill($tempImage, 0, 0, $color);
                        }
                        imagecopyresampled($tempImage, $this->_source, 0, 0, 0, 0, $newWidth, $newHeight, $sourceWidth, $sourceHeight);
                        imagecopy($this->_result, $tempImage, 0, 0, $offsetX, 0, $this->_width, $this->_height);
                    } else {
                        $newWidth = $this->_width;
                        $newHeight = $sourceHeight * $newWidth / $sourceWidth;
                        $offsetY = ($newHeight - $this->_height) / 2;
                        $this->_width = ($this->_width >= $newWidth) ? $newWidth : $this->_width;
                        $tempImage = imagecreatetruecolor($newWidth, $newHeight);
                        $this->_result = imagecreatetruecolor($this->_width, $this->_height);
                        if ($this->_info[2] == IMAGETYPE_PNG) {
                            imagealphablending($this->_result, true);
                            imagesavealpha($this->_result, true);
                            $color = imagecolorallocatealpha($this->_result, 0, 0, 0, 127);
                            imagefill($this->_result, 0, 0, $color);
                            imagealphablending($tempImage, true);
                            imagesavealpha($tempImage, true);
                            $color = imagecolorallocatealpha($tempImage, 0, 0, 0, 127);
                            imagefill($tempImage, 0, 0, $color);
                        }
                        imagecopyresampled($tempImage, $this->_source, 0, 0, 0, 0, $newWidth, $newHeight, $sourceWidth, $sourceHeight);
                        imagecopy($this->_result, $tempImage, 0, 0, 0, $offsetY, $this->_width, $this->_height);
                    }
                    break;
                case 2:
                    if ($sourceWidth >= $sourceHeight) {
                        $newWidth = $this->_width;
                        $newHeight = $sourceHeight * $newWidth / $sourceWidth;
                        $this->_height = ($this->_height >= $newHeight) ? $newHeight : $this->_height;
                        $tempImage = imagecreatetruecolor($newWidth, $newHeight);
                        $this->_result = imagecreatetruecolor($this->_width, $this->_height);
                        if ($this->_info[2] == IMAGETYPE_PNG) {
                            imagealphablending($this->_result, false);
                            imagesavealpha($this->_result, true);
                            $color = imagecolorallocatealpha($this->_result, 0, 0, 0, 127);
                            imagefill($this->_result, 0, 0, $color);
                            imagealphablending($tempImage, true);
                            imagesavealpha($tempImage, true);
                            $color = imagecolorallocatealpha($tempImage, 0, 0, 0, 127);
                            imagefill($tempImage, 0, 0, $color);
                        }
                        imagecopyresampled($tempImage, $this->_source, 0, 0, 0, 0, $newWidth, $newHeight, $sourceWidth, $sourceHeight);
                        imagecopy($this->_result, $tempImage, 0, 0, 0, 0, $this->_width, $this->_height);
                    } else {
                        $newHeight = $this->_height;
                        $newWidth = $sourceWidth * $newHeight / $sourceHeight;
                        $this->_result = imagecreatetruecolor($newWidth, $newHeight);
                        if ($this->_info[2] == IMAGETYPE_PNG) {
                            imagealphablending($this->_result, true);
                            imagesavealpha($this->_result, true);
                            $color = imagecolorallocatealpha($this->_result, 0, 0, 0, 127);
                            imagefill($this->_result, 0, 0, $color);
                        }
                        imagecopyresampled($this->_result, $this->_source, 0, 0, 0, 0, $newWidth, $newHeight, $sourceWidth, $sourceHeight);
                    }
                    break;
                case 3:
                    if ($sourceWidth >= $sourceHeight) {
                        $newHeight = $this->_height;
                        $newWidth = $sourceWidth * $newHeight / $sourceHeight;
                        $this->_result = imagecreatetruecolor($newWidth, $newHeight);
                        if ($this->_info[2] == IMAGETYPE_PNG) {
                            imagealphablending($this->_result, true);
                            imagesavealpha($this->_result, true);
                            $color = imagecolorallocatealpha($this->_result, 0, 0, 0, 127);
                            imagefill($this->_result, 0, 0, $color);
                        }
                        imagecopyresampled($this->_result, $this->_source, 0, 0, 0, 0, $newWidth, $newHeight, $sourceWidth, $sourceHeight);
                    } else {
                        $newWidth = $this->_width;
                        $newHeight = $sourceHeight * $newWidth / $sourceWidth;
                        $this->_height = ($this->_height >= $newHeight) ? $newHeight : $this->_height;
                        $tempImage = imagecreatetruecolor($newWidth, $newHeight);
                        $this->_result = imagecreatetruecolor($this->_width, $this->_height);
                        if ($this->_info[2] == IMAGETYPE_PNG) {
                            imagealphablending($this->_result, true);
                            imagesavealpha($this->_result, true);
                            $color = imagecolorallocatealpha($this->_result, 0, 0, 0, 127);
                            imagefill($this->_result, 0, 0, $color);
                            imagealphablending($tempImage, true);
                            imagesavealpha($tempImage, true);
                            $color = imagecolorallocatealpha($tempImage, 0, 0, 0, 127);
                            imagefill($tempImage, 0, 0, $color);
                        }
                        imagecopyresampled($tempImage, $this->_source, 0, 0, 0, 0, $newWidth, $newHeight, $sourceWidth, $sourceHeight);
                        imagecopy($this->_result, $tempImage, 0, 0, 0, 0, $this->_width, $this->_height);
                    }
                    break;
                case 4:
                    if ($sourceWidth >= $sourceHeight) {
                        $newHeight = $this->_height;
                        $newWidth = $sourceWidth * $newHeight / $sourceHeight;
                        $this->_width = $newWidth;
                        $tempImage = imagecreatetruecolor($newWidth, $newHeight);
                        $this->_result = imagecreatetruecolor($this->_width, $this->_height);
                        if ($this->_info[2] == IMAGETYPE_PNG) {
                            imagealphablending($this->_result, true);
                            imagesavealpha($this->_result, true);
                            $color = imagecolorallocatealpha($this->_result, 0, 0, 0, 127);
                            imagefill($this->_result, 0, 0, $color);
                            imagealphablending($tempImage, true);
                            imagesavealpha($tempImage, true);
                            $color = imagecolorallocatealpha($tempImage, 0, 0, 0, 127);
                            imagefill($tempImage, 0, 0, $color);
                        }
                        imagecopyresampled($tempImage, $this->_source, 0, 0, 0, 0, $newWidth, $newHeight, $sourceWidth, $sourceHeight);
                        imagecopy($this->_result, $tempImage, 0, 0, 0, 0, $this->_width, $this->_height);
                    } else {
                        $newHeight = $this->_height;
                        $newWidth = $sourceWidth * $newHeight / $sourceHeight;
                        $this->_result = imagecreatetruecolor($newWidth, $newHeight);
                        if ($this->_info[2] == IMAGETYPE_PNG) {
                            imagealphablending($this->_result, true);
                            imagesavealpha($this->_result, true);
                            $color = imagecolorallocatealpha($this->_result, 0, 0, 0, 127);
                            imagefill($this->_result, 0, 0, $color);
                        }
                        imagecopyresampled($this->_result, $this->_source, 0, 0, 0, 0, $newWidth, $newHeight, $sourceWidth, $sourceHeight);
                    }
                    break;
                case 5:
                    $boxWidth = $this->_width;
                    $boxHeight = $this->_height;
                    $box = imagecreatetruecolor($boxWidth, $boxHeight);

                    if ($boxWidth == $sourceWidth && $boxHeight == $sourceHeight) {
                        $this->copyOriginal();
                        break;
                    }

                    if ($boxWidth < $sourceWidth && $boxHeight < $sourceHeight) {
                        //И ширина и высота бокса меньше исходного изображения
                        if ($boxHeight / $sourceHeight > $boxWidth / $sourceWidth) {
                            $newWidth = $boxWidth;
                            $newHeight = round($sourceHeight * $boxWidth / $sourceWidth);
                        } else {
                            $newWidth = round($sourceWidth * $boxHeight / $sourceHeight);
                            $newHeight = $boxHeight;
                        }
                    } elseif ($boxWidth < $sourceWidth) {
                        //Ширина бокса меньше исходного изображения
                        $newWidth = $boxWidth;
                        $newHeight = round($sourceHeight * $boxWidth / $sourceWidth);
                    } elseif ($boxHeight < $sourceHeight) {
                        //Высота бокса меньше исходного изображения
                        $newWidth = round($sourceWidth * $boxHeight / $sourceHeight);
                        $newHeight = $boxHeight;
                    } elseif ($boxWidth >= $sourceWidth && $boxHeight >= $sourceHeight) {
                        $newWidth = $sourceWidth;
                        $newHeight = $sourceHeight;
                    }

                    $tempImage = imagecreatetruecolor($newWidth, $newHeight);
                    $editedImage = imagecreatetruecolor($newWidth, $newHeight);
                    if ($this->_info[2] == IMAGETYPE_PNG) {
                        imagealphablending($tempImage, true);
                        imagesavealpha($tempImage, true);
                        $color = imagecolorallocatealpha($tempImage, 0, 0, 0, 127);
                        imagefill($tempImage, 0, 0, $color);
                        imagealphablending($editedImage, true);
                        imagesavealpha($editedImage, true);
                        $color = imagecolorallocatealpha($editedImage, 0, 0, 0, 127);
                        imagefill($editedImage, 0, 0, $color);
                    }
                    if ($this->_info[2] == IMAGETYPE_GIF) {
                        $color = imagecolorallocate($tempImage, 255, 255, 255);
                        imagecolortransparent($tempImage, $color);
                        imagefill($tempImage, 0, 0, $color);
                        $color = imagecolorallocate($editedImage, 255, 255, 255);
                        imagecolortransparent($editedImage, $color);
                        imagefill($editedImage, 0, 0, $color);
                    }

                    imagecopyresampled($tempImage, $this->_source, 0, 0, 0, 0, $newWidth, $newHeight, $sourceWidth, $sourceHeight);
                    imagecopy($editedImage, $tempImage, 0, 0, 0, 0, $newWidth, $newHeight);

                    $coordinateX = $boxWidth > $newWidth ? round($boxWidth / 2) - round($newWidth / 2) : 0;
                    $coordinateY = $boxHeight > $newHeight ? round($boxHeight / 2) - round($newHeight / 2) : 0;

                    if ($this->_info[2] == IMAGETYPE_PNG) {
                        imagealphablending($box, true);
                        imagesavealpha($box, true);
                        $color = imagecolorallocatealpha($box, 0, 0, 0, 127);
                        imagefill($box, 0, 0, $color);
                    } elseif ($this->_info[2] == IMAGETYPE_GIF) {
                        $color = imagecolorallocate($box, 255, 255, 255);
                        imagecolortransparent($box, $color);
                        imagefill($box, 0, 0, $color);
                    } else {
                        $color = imagecolorallocate($box, 255, 255, 255);
                        imagefill($box, 0, 0, $color);
                    }
                    imagecopy($box, $editedImage, $coordinateX, $coordinateY, 0, 0, $newWidth, $newHeight);
                    $this->_result = $box;

                    break;
            }
        }

        return $this;
    }

    public function watermark()
    {

        if (is_array($this->_waterMark) === false && $this->_waterMark === true) {
            $this->_waterMark = [];
        }

        if (isset($this->_waterMark['path']) === false) {
            $stampPath = $this->getWaterMarkImage();
        } else {
            $stampPath = $this->_waterMark['path'];
        }
        $stamp = imagecreatefrompng($stampPath);

        $stampWidth = imagesx($stamp);
        $stampHeight = imagesy($stamp);

        if (is_array($this->_waterMark) === true) {
            $options = \yii\helpers\ArrayHelper::merge($this->_defaultWatermarkOptions, $this->_waterMark);

            if ($options['center'] == true) {

                $centerX = floor($this->_info[0] / 2) - floor($stampWidth / 2);
                $centerY = floor($this->_info[1] / 2) - floor($stampHeight / 2);

                imagecopy($this->_source, $stamp, $centerX, $centerY, 0, 0, $stampWidth, $stampHeight);
            } elseif ($options['fill'] == true) {

                $maxWidth = $this->_info[0] + $stampWidth + $options['x'];
                $maxHeight = $this->_info[1] + $stampHeight + $options['y'];

                for ($fillX = $options['x']; $fillX < $maxWidth; $fillX += $options['x'] + $stampWidth) {

                    for ($fillY = $options['y']; $fillY < $maxHeight; $fillY += $options['y'] + $stampHeight) {

                        imagecopy($this->_source, $stamp, $fillX, $fillY, 0, 0, $stampWidth, $stampHeight);
                    }
                }
            } else {
                imagecopy($this->_source, $stamp, $options['x'], $options['y'], 0, 0, $stampWidth, $stampHeight);
            }
        }

        imagedestroy($stamp);

        return $this;
    }

    public function getWaterMarkImage()
    {
        // Напиши сам откуда брать изображение водяного знака
        throw new \yii\base\NotSupportedException('Не реализовано');
    }

    public function save($path)
    {
        if (is_null($this->_result) === true) {
            $this->copyOriginal();
        }

        if ($this->_info[2] == IMAGETYPE_GIF) {
            return imagegif($this->_result, $path);
        } elseif ($this->_info[2] == IMAGETYPE_JPEG) {
            return imagejpeg($this->_result, $path, $this->_quality);
        } elseif ($this->_info[2] == IMAGETYPE_PNG) {
            return imagepng($this->_result, $path, 9, PNG_ALL_FILTERS);
        }
    }

    static function getExtention($fileName)
    {
        return preg_replace('/(^.*\.)/', '', $fileName);
    }

    static function start($picPath, $width = 100, $height = 100, $type = 1, $waterMark = false)
    {
        if (file_exists($picPath) === false) {
            return '';
        }
        if ($width === false || $height === false || $type === false) {
            $width = $height = $type = false;
        }

        $fileName = md5($picPath . filemtime($picPath) . $width . $height . $type . serialize($waterMark)) . '.' . self::getExtention($picPath);
        $cacheFolder = \Yii::getAlias('@image_cache/');
        if (file_exists($cacheFolder) === false) {
            mkdir($cacheFolder, 0777, true);
        }

        $cachePath = $cacheFolder . $fileName;

        if (file_exists($cachePath) === false || Resize::DEBUG === true) {

            if (\yii\helpers\FileHelper::getMimeType($picPath) === 'image/svg+xml') {
                copy($picPath, $cachePath);

                return \Yii::getAlias('@image_cache/web/') . $fileName;
            }

            $resize = new Resize($picPath);
            if ($waterMark !== false) {
                $resize->setWaterMark($waterMark)->watermark();
            }

            if ($width !== false && $height !== false && $type !== false) {
                $resize->setWidth($width)->setHeight($height)->setType($type)->resize();
            }

            $resize->save($cachePath);
        }
        return \Yii::getAlias('@image_cache/web/') . $fileName;
    }

}
