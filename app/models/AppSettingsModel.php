<?php

/**
 * Class AppSettingsModel
 */ 
class AppSettingsModel extends \Asatru\Database\Model {
    /**
     * @param $name
     * @param $row
     * @return mixed
     * @throws \Exception
     */
    public static function getSetting($name, $row = 1)
    {
        try {
            $data = AppSettingsModel::raw('SELECT ' . $name . ' FROM `' . self::tableName() . '` WHERE id = ? LIMIT 1', [$row])->first();

            return $data->get($name);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @return string
     * @throws \Exception
     */
    public static function getHeadCode()
    {
        try {
            $value = AppSettingsModel::getSetting('head_code');
            if ($value === null) {
                return '';
            }

            return $value;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @return string
     * @throws \Exception
     */
    public static function getCookieNotice()
    {
        try {
            $value = AppSettingsModel::getSetting('cookie_notice');
            if ($value === null) {
                return '';
            }

            return $value;
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
        return 'appsettings';
    }
}