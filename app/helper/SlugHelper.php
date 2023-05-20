<?php

/**
 * Create a slug
 * 
 * @param $id
 * @param $title
 * @return string
 */
function slug($id, $title)
{
    $title = preg_replace("/[^A-Za-z0-9 ]/", '', $title);
    $title = trim(strtolower($title));
    $title = str_replace(' ', '-', $title);

    return strval($id) . '-' . $title;
}