<?php

/**
 * Class UtilsModule
 */
class UtilsModule {
    /**
     * @return string
     * @throws \Exception
     */
    public static function getRandomHeaderImage()
    {
        try {
            $images = [];

            $files = scandir(public_path() . '/img/header');
            foreach ($files as $file) {
                if ((strlen($file) > 3) && (static::isValidImage(public_path() . '/img/header/' . $file))) {
                    $images[] = asset('img/header/' . $file);
                }
            }

            return $images[rand(0, count($images) - 1)];;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param string $imgFile
     * @return bool
     */
    private static function isValidImage($imgFile)
    {
        $imagetypes = array(
            IMAGETYPE_PNG,
            IMAGETYPE_JPEG,
            IMAGETYPE_GIF
        );

        if (!file_exists($imgFile)) {
            return false;
        }

        foreach ($imagetypes as $type) {
            if (exif_imagetype($imgFile) === $type) {
                return true;
            }
        }

        return false;
    }
}
