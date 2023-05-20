<?php

/**
 * Class TagsModel
 */ 
class TagsModel extends \Asatru\Database\Model {
    /**
     * @param $tag
     * @return void
     * @throws \Exception
     */
    public static function addTag($tag)
    {
        try {
            TagsModel::raw('INSERT INTO `' . self::tableName() . '` (name) VALUES(?)', [
                $tag
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $paginate
     * @param $limit
     * @return mixed
     * @throws \Exception
     */
    public static function getTagList($paginate = null, $limit = 0)
    {
        try {
            $tags = null;

            if ($paginate !== null) {
                if ($limit !== 0) {
                    $tags = TagsModel::raw('SELECT * FROM `' . self::tableName() . '` WHERE id < ? AND active = 1 ORDER BY id DESC LIMIT ' . $limit, [
                        $paginate
                    ]);
                } else {
                    $tags = TagsModel::raw('SELECT * FROM `' . self::tableName() . '` WHERE id < ? AND active = 1 ORDER BY id DESC', [
                        $paginate
                    ]);
                }
            } else {
                if ($limit !== 0) {
                    $tags = TagsModel::raw('SELECT * FROM `' . self::tableName() . '` WHERE active = 1 ORDER BY id DESC LIMIT ' . $limit);
                } else {
                    $tags = TagsModel::raw('SELECT * FROM `' . self::tableName() . '` WHERE active = 1 ORDER BY id DESC');
                }
            }
            
            return $tags;
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
        return 'tags';
    }
}