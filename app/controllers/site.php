<?php

/* @var $action */

$action('index', function () {
    // $data['products'] = product\getAllProducts();
    // $data['products'] = ['test'];
    print_r('site/index');

    // return view('admin/index', $data);
});

$action('not-found', function () {
    print_r('site/not-found');

    // return view('site/not-found', $data);
});
