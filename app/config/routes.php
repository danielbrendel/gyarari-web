<?php

/*
    Asatru PHP - routes configuration file

    Add here all your needed routes.

    Schema:
        [<url>, <method>, controller_file@controller_method]
    Example:
        [/my/route, get, mycontroller@index]
        [/my/route/with/{param1}/and/{param2}, get, mycontroller@another]
    Explanation:
        Will call index() in app\controller\mycontroller.php if request is 'get'
        Every route with $ prefix is a special route
*/

return [
    array('/', 'GET', 'index@index'),
    array('/recent', 'GET', 'index@viewRecent'),
    array('/random', 'GET', 'index@viewRandom'),
    array('/search', 'GET', 'index@viewSearch'),
    array('/upload', 'GET', 'index@viewUpload'),
    array('/upload', 'POST', 'index@upload'),
    array('/photo/{id}', 'GET', 'index@showPhoto'),
    array('/photos/query', 'ANY', 'index@queryPhotos'),
    array('$404', 'ANY', 'error404@index')
];
