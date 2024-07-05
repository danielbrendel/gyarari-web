<?php

/**
 * Class ReportModel
 */ 
class ReportModel extends \Asatru\Database\Model {
    /**
     * @param $id
     * @return void
     * @throws \Exception
     */
    public static function addReport($id)
    {
        try {
            $token = md5($_SERVER['REMOTE_ADDR']);

            $item = ReportModel::raw('SELECT * FROM `@THIS` WHERE token = ? AND photo = ?', [$token, $id])->first();
            if (!$item) {
                ReportModel::raw('INSERT INTO `@THIS` (token, photo) VALUES(?, ?)', [
                    $token,
                    $id
                ]);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @return int
     * @throws \Exception
     */
    public static function getReportCount($id)
    {
        try {
            $data = ReportModel::raw('SELECT COUNT(*) AS count FROM `@THIS` WHERE photo = ?', [$id]);

            return (int)$data->get('count');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $limit
     * @return mixed
     * @throws \Exception
     */
    public static function getReportPack($limit = 10)
    {
        try {
            $items = ReportModel::raw('SELECT id, photo, COUNT(photo) AS count FROM `@THIS` GROUP BY photo ORDER BY count DESC LIMIT ' . $limit);
            return $items;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $photo
     * @return int
     * @throws \Exception
     */
    public static function safe($photo)
    {
        try {
            ReportModel::raw('DELETE FROM `@THIS` WHERE photo = ?', [$photo]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $photo
     * @return int
     * @throws \Exception
     */
    public static function remove($photo)
    {
        try {
            $item = PhotoModel::raw('SELECT * FROM `' . PhotoModel::tableName() . '` WHERE id = ? LIMIT 1', [$photo])->first();
            if ($item) {
                PhotoModel::raw('DELETE FROM `' . PhotoModel::tableName() . '` WHERE id = ?', [$photo]);

                unlink(public_path() . '/img/photos/' . $item->get('photo_thumb'));
                unlink(public_path() . '/img/photos/' . $item->get('photo_full'));
            }

            ReportModel::raw('DELETE FROM `@THIS` WHERE photo = ?', [$photo]);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}