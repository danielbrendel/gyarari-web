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
            $tag = trim(strtolower($tag));

            if (strlen($tag) == 0) {
                return;
            }

            $exists = TagsModel::raw('SELECT * FROM `@THIS` WHERE name = ?', [$tag])->first();
            if (!$exists) {
                TagsModel::raw('INSERT INTO `@THIS` (name) VALUES(?)', [
                    $tag
                ]);
            }
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
                    $tags = TagsModel::raw('SELECT * FROM `@THIS` WHERE id < ? AND active = 1 ORDER BY id DESC LIMIT ' . $limit, [
                        $paginate
                    ]);
                } else {
                    $tags = TagsModel::raw('SELECT * FROM `@THIS` WHERE id < ? AND active = 1 ORDER BY id DESC', [
                        $paginate
                    ]);
                }
            } else {
                if ($limit !== 0) {
                    $tags = TagsModel::raw('SELECT * FROM `@THIS` WHERE active = 1 ORDER BY id DESC LIMIT ' . $limit);
                } else {
                    $tags = TagsModel::raw('SELECT * FROM `@THIS` WHERE active = 1 ORDER BY id DESC');
                }
            }
            
            return $tags;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}