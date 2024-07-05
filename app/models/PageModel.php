<?php

/**
 * Class PageModel
 */ 
class PageModel extends \Asatru\Database\Model {
    /**
     * @param $ident
     * @return mixed
     * @throws \Exception
     */
    public static function getPage($ident)
    {
        try {
            $item = PageModel::raw('SELECT * FROM `@THIS` WHERE ident = ? AND active = 1 LIMIT 1', [$ident])->first();
            if (!$item) {
                throw new \Exception('Page not found: ' . $ident);
            }

            return $item;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @return array
     */
    public static function getLinkablePages()
    {
        try {
            $result = [];

            $pages = PageModel::raw('SELECT * FROM `@THIS` WHERE active = 1 ORDER BY id ASC');
            foreach ($pages as $page) {
                $result[] = (object)[
                    'url' => url('/page/' . $page->get('ident')),
                    'label' => $page->get('label')
                ];
            }

            return $result;
        } catch (\Exception $e) {
            return array();
        }
    }
}