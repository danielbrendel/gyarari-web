<?php

/**
 * Class ViewCountModel
 */ 
class ViewCountModel extends \Asatru\Database\Model {
    /**
     * @param $id
     * @return int
     * @throws \Exception
     */
    public static function viewForItem($id)
    {
        try {
            $count = 0;
            $token = md5($_SERVER['REMOTE_ADDR']);

            $item = ViewCountModel::where('photo', '=', $id)->where('token', '=', $token)->first();
            if (!$item) {
                ViewCountModel::raw('INSERT INTO `@THIS` (photo, token) VALUES(?, ?)', [
                    $id,
                    $token
                ]);
            }

            $count = CacheModel::remember('view_for_photo_' . $id, 60 * 15, function() use ($id) {
                return ViewCountModel::where('photo', '=', $id)->count()->get();
            });

            return (int)$count;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}