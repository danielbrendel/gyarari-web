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
    array('/photo/{id}/report', 'ANY', 'index@reportPhoto'),
    array('/photo/{id}/remove/{token}', 'ANY', 'index@removePhoto'),
    array('/photos/query', 'ANY', 'index@queryPhotos'),
    array('/page/{ident}', 'GET', 'index@viewPage'),
    array('/newsletter/subscribe', 'POST', 'index@subscribeNewsletter'),
    array('/newsletter/unsubscribe', 'ANY', 'index@unsubscribeNewsletter'),
    array('/newsletter/process', 'ANY', 'index@processNewsletter'),
    array('/admin', 'GET', 'index@admin'),
    array('/admin/photo/{id}/approve', 'ANY', 'index@adminPhotoApprove'),
    array('/admin/photo/{id}/decline', 'ANY', 'index@adminPhotoDecline'),
    array('/admin/photo/{id}/report/safe', 'ANY', 'index@adminPhotoSafe'),
    array('/admin/photo/{id}/report/delete', 'ANY', 'index@adminPhotoDelete'),
    array('$404', 'ANY', 'error404@index')
];
