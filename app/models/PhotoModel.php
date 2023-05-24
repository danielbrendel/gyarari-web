<?php

/**
 * Class PhotoModel
 */ 
class PhotoModel extends \Asatru\Database\Model {
    const FILE_IDENT = 'photo';

    /**
     * @param string $imgFile
     * @return bool
     */
    public static function isValidImage($imgFile)
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

    /**
     * @param $ext
     * @param $file
     * @return mixed|null
     */
    public static function getImageType($ext, $file)
    {
        $imagetypes = array(
            array('png', IMAGETYPE_PNG),
            array('jpg', IMAGETYPE_JPEG),
            array('jpeg', IMAGETYPE_JPEG),
            array('gif', IMAGETYPE_GIF)
        );

        for ($i = 0; $i < count($imagetypes); $i++) {
            if (strtolower($ext) == $imagetypes[$i][0]) {
                if (exif_imagetype($file . '.' . $ext) == $imagetypes[$i][1])
                    return $imagetypes[$i][1];
            }
        }

        return null;
    }

    /**
     * @param $filename
     * @param &$image
     * @return void
     */
    private static function correctImageRotation($filename, &$image)
    {
        $exif = @exif_read_data($filename);

        if (!isset($exif['Orientation']))
            return;

        switch($exif['Orientation'])
        {
            case 8:
                $image = imagerotate($image, 90, 0);
                break;
            case 3:
                $image = imagerotate($image, 180, 0);
                break;
            case 6:
                $image = imagerotate($image, 270, 0);
                break;
            default:
                break;
        }
    }

    /**
     * @param $srcfile
     * @param $imgtype
     * @param $basefile
     * @param $fileext
     * @return bool
     */
    public static function createThumbFile($srcfile, $imgtype, $basefile, $fileext)
    {
        list($width, $height) = getimagesize($srcfile);

        $factor = 1.0;

        if ($width > $height) {
            if (($width >= 800) and ($width < 1000)) {
                $factor = 0.5;
            } else if (($width >= 1000) and ($width < 1250)) {
                $factor = 0.4;
            } else if (($width >= 1250) and ($width < 1500)) {
                $factor = 0.4;
            } else if (($width >= 1500) and ($width < 2000)) {
                $factor = 0.3;
            } else if ($width >= 2000) {
                $factor = 0.2;
            }
        } else {
            if (($height >= 800) and ($height < 1000)) {
                $factor = 0.5;
            } else if (($height >= 1000) and ($height < 1250)) {
                $factor = 0.4;
            } else if (($height >= 1250) and ($height < 1500)) {
                $factor = 0.4;
            } else if (($height >= 1500) and ($height < 2000)) {
                $factor = 0.3;
            } else if ($height >= 2000) {
                $factor = 0.2;
            }
        }

        $newwidth = $factor * $width;
        $newheight = $factor * $height;

        $dstimg = imagecreatetruecolor((int)$newwidth, (int)$newheight);
        if (!$dstimg)
            return false;

        $srcimage = null;
        switch ($imgtype) {
            case IMAGETYPE_PNG:
                $srcimage = imagecreatefrompng($srcfile);
                imagecopyresampled($dstimg, $srcimage, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
                static::correctImageRotation($srcfile, $dstimg);
                imagepng($dstimg, $basefile . "_thumb." . $fileext);
                break;
            case IMAGETYPE_JPEG:
                $srcimage = imagecreatefromjpeg($srcfile);
                imagecopyresampled($dstimg, $srcimage, 0, 0, 0, 0, (int)$newwidth, (int)$newheight, $width, $height);
                static::correctImageRotation($srcfile, $dstimg);
                imagejpeg($dstimg, $basefile . "_thumb." . $fileext);
                break;
            case IMAGETYPE_GIF:
                copy($srcfile, $basefile . "_thumb." . $fileext);
                break;
            default:
                return false;
                break;
        }

        return true;
    }

    /**
     * @param $title
     * @param $name
     * @param $tags
     * @return void
     * @throws \Exception
     */
    public static function store($title, $name, $tags = '')
    {
        try {
            if ($_FILES[self::FILE_IDENT]['error'] !== UPLOAD_ERR_OK) {
                throw new Exception('File upload error: ' . $_FILES[self::FILE_IDENT]['error']);
            }

            if ($_FILES[self::FILE_IDENT]['size'] > env('APP_UPLOADFILESIZELIMIT')) {
                throw new \Exception(__('app.file_size_too_large', ['current' => $_FILES[self::FILE_IDENT]['size'], 'max' => env('APP_UPLOADFILESIZELIMIT')]));
            }

            $newName = md5(random_bytes(55) . date('Y-m-d H:m:i'));
            $fileExt = pathinfo($_FILES[self::FILE_IDENT]['name'], PATHINFO_EXTENSION);

            move_uploaded_file($_FILES[self::FILE_IDENT]['tmp_name'], public_path() . '/img/photos/' . $newName . '.' . $fileExt);

            $baseFile = public_path() . '/img/photos/' . $newName;
            $fullFile = $baseFile . '.' . $fileExt;

            if (static::isValidImage($fullFile)) {
                if (!static::createThumbFile($fullFile, static::getImageType($fileExt, $baseFile), $baseFile, $fileExt)) {
                    throw new \Exception('createThumbFile failed', 500);
                }
            } else {
                unlink($fullFile);
                throw new \Exception(__('app.post_invalid_file_type', ['filetype' => $fileExt]));
            }

            $removal_token = md5(random_bytes(55) . date('Y-m-d H:m:i'));

            $approved = (env('APP_ENABLEPHOTOAPPROVAL')) ? 0 : 1;

            PhotoModel::raw('INSERT INTO `' . self::tableName() . '` (title, name, tags, photo_thumb, photo_full, removal_token, approved) VALUES(?, ?, ?, ?, ?, ?, ?);', [
                $title,
                $name,
                trim(strtolower($tags)),
                $newName . '_thumb' . '.' . $fileExt,
                $newName . '.' . $fileExt,
                $removal_token,
                $approved
            ]);

            $last_item = PhotoModel::raw('SELECT * FROM `' . self::tableName() . '` ORDER BY id DESC LIMIT 1')->first();

            $slug = static::slug($last_item->get('id'), $last_item->get('title'));

            PhotoModel::raw('UPDATE `' . self::tableName() . '` SET slug = ? WHERE id = ?', [
                $slug,
                $last_item->get('id')
            ]);

            $taglist = explode(' ', $tags);
            foreach ($taglist as $tag) {
                TagsModel::addTag(trim(strtolower($tag)));
            }

            return $last_item;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $paginate
     * @return mixed
     * @throws \Exception
     */
    public static function queryPhotos($paginate = null, $search_token = null)
    {
        try {
            $photos = null;

            $limit = env('APP_PHOTOPACKLIMIT', 20);
            $search_token = '%' . $search_token . '%';

            if ($paginate !== null) {
                if ($search_token !== null) {
                    $photos = PhotoModel::raw('SELECT * FROM `' . self::tableName() . '` WHERE approved = 1 AND id < ? AND (title LIKE ? OR name LIKE ? OR tags LIKE ?) ORDER BY id DESC LIMIT ' . $limit, [
                        $paginate,
                        $search_token,
                        $search_token,
                        $search_token
                    ]);
                } else {
                    $photos = PhotoModel::raw('SELECT * FROM `' . self::tableName() . '` WHERE approved = 1 AND id < ? ORDER BY id DESC LIMIT ' . $limit, [
                        $paginate
                    ]);
                }
            } else {
                if ($search_token !== null) {
                    $photos = PhotoModel::raw('SELECT * FROM `' . self::tableName() . '` WHERE approved = 1 AND (title LIKE ? OR name LIKE ? OR tags LIKE ?) ORDER BY id DESC LIMIT ' . $limit, [
                        $search_token,
                        $search_token,
                        $search_token
                    ]);
                } else {
                    $photos = PhotoModel::raw('SELECT * FROM `' . self::tableName() . '` WHERE approved = 1 ORDER BY id DESC LIMIT ' . $limit);
                }
            }

            return $photos;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public static function queryRandom()
    {
        try {
            $photos = null;

            $limit = env('APP_PHOTOPACKLIMIT', 20);

            $photos = PhotoModel::raw('SELECT * FROM `' . self::tableName() . '` WHERE approved = 1 ORDER BY RAND() DESC LIMIT ' . $limit);

            return $photos;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public static function getPhoto($id)
    {
        try {
            $photo = PhotoModel::raw('SELECT * FROM `' . self::tableName() . '` WHERE approved = 1 AND (id = ? OR slug = ?) LIMIT 1', [
                $id,
                $id
            ])->first();

            return $photo;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @return string
     * @throws \Exception
     */
    public static function getInitialPhoto()
    {
        try {
            $photo = PhotoModel::raw('SELECT * FROM `' . self::tableName() . '` ORDER BY id ASC LIMIT 1')->first();

            if ($photo) {
                return asset('img/photos/' . $photo->get('photo_thumb'));
            }

            return '';
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @param $token
     * @return mixed
     * @throws \Exception
     */
    public static function removePhoto($id, $token)
    {
        try {
            $photo = PhotoModel::raw('SELECT * FROM `' . self::tableName() . '` WHERE id = ? AND removal_token = ? LIMIT 1', [
                $id,
                $token
            ])->first();

            if (!$photo) {
                throw new \Exception(__('app.photo_not_found'));
            }

            PhotoModel::raw('DELETE FROM `' . self::tableName() . '` WHERE id = ? AND removal_token = ?', [
                $id,
                $token
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $limit
     * @return mixed
     * @throws \Exception
     */
    public static function getWeekPhotos($limit = 10)
    {
        try {
            $end_date = date('Y-m-d');
            $start_date = date('Y-m-d', strtotime('-7 days'));

            $items = PhotoModel::raw('SELECT * FROM `' . self::tableName() . '` WHERE DATE(created_at) >= ? AND DATE(created_at) <= ? AND approved = 1 ORDER BY RAND() LIMIT ' . $limit, [
                $start_date,
                $end_date
            ]);

            return $items;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $limit
     * @return mixed
     * @throws \Exception
     */
    public static function getApprovalPending($limit = 10)
    {
        try {
            $items = PhotoModel::raw('SELECT * FROM `' . self::tableName() . '` WHERE approved = 0 ORDER BY id ASC LIMIT ' . $limit);
            return $items;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @return void
     * @throws \Exception
     */
    public static function approve($id)
    {
        try {
            $photo = PhotoModel::raw('SELECT * FROM `' . self::tableName() . '` WHERE id = ? AND approved = 0', [$id])->first();
            if ($photo) {
                PhotoModel::raw('UPDATE `' . self::tableName() . '` SET approved = 1 WHERE id = ?', [$id]);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @return void
     * @throws \Exception
     */
    public static function decline($id)
    {
        try {
            $photo = PhotoModel::raw('SELECT * FROM `' . self::tableName() . '` WHERE id = ? AND approved = 0', [$id])->first();
            if ($photo) {
                unlink(public_path() . '/img/photos/' . $photo->get('photo_full'));
                unlink(public_path() . '/img/photos/' . $photo->get('photo_thumb'));

                PhotoModel::raw('DELETE FROM `' . self::tableName() . '` WHERE id = ?', [$id]);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @param $title
     * @return string
     */
    private static function slug($id, $title)
    {
        $title = preg_replace("/[^A-Za-z0-9 ]/", '', $title);
        $title = trim(strtolower($title));
        $title = str_replace(' ', '-', $title);

        return strval($id) . '-' . $title;
    }

    /**
     * Return the associated table name of the migration
     * 
     * @return string
     */
    public static function tableName()
    {
        return 'photo';
    }
}