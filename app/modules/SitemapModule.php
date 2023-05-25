<?php

/**
 * Class SitemapModule
 */
class SitemapModule {
    /**
     * @return array
     * @throws \Exception
     */
    private static function generateSitemap($incl_photos = false)
    {
        try {
            $sites = [];

            $sites[] = url('/');
            $sites[] = url('/recent');
            $sites[] = url('/random');
            $sites[] = url('/search');
            $sites[] = url('/upload');

            $pages = PageModel::getLinkablePages();
            foreach ($pages as $page) {
                $sites[] = $page->url;
            }

            if ($incl_photos) {
                $photos = PhotoModel::where('approved', '=', 1)->orderBy('id', 'DESC')->get();
                foreach ($photos as $photo) {
                    $sites[] = url('/photo/' . $photo->get('slug'));
                }
            }

            return $sites;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @return string
     * @throws \Exception
     */
    public static function generateXml()
    {
        try {
            $xml = '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">{%URLS%}</urlset>';
            $node = '<url><loc>{%URL%}</loc></url>';

            $sites = static::generateSitemap(true);

            $all_urls = '';

            foreach ($sites as $url) {
                $all_urls .= str_replace('{%URL%}', $url, $node);
            }

            $xml = str_replace('{%URLS%}', $all_urls, $xml);

            return $xml;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
