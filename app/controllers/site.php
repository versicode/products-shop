<?php

/* @var $action */

$action('index', function () {
    $data['products'] = models\product\find();

    render\view('site/index', $data, 'site_layout');
});

$action('not-found', function () {
    render\view('site/404', $data, 'site_layout');
});
