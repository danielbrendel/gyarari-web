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

            $item = ReportModel::raw('SELECT * FROM `' . self::tableName() . '` WHERE token = ? AND photo = ?', [$token, $id])->first();
            if (!$item) {
                ReportModel::raw('INSERT INTO `' . self::tableName() . '` (token, photo) VALUES(?, ?)', [
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
            $data = ReportModel::raw('SELECT COUNT(*) AS count FROM `' . self::tableName() . '` WHERE photo = ?', [$id]);

            return (int)$data->get('count');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Return the associated table name of the migration
     * 
     * @return string
     */
    public static function tableName()
    {
        return 'report';
    }
}