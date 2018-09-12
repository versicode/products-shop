<?php

/* @var $action */

$action('index', function () {
    render\view('admin/index', $data, 'admin_layout');
});

$action('product', function ($params) {
    $id = isset($params['id']) ? router\parseIdentifier($params['id']) : false;

    $data = [];

    if ($id) {
        $data['product'] = models\product\findOne($id);
    }

    if ($data['product']) {
        render\view('admin/edit', $data, 'admin_layout');
    } else {
        render\view('admin/create', $data, 'admin_layout');
    }
});
