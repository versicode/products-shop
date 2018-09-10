<?php

/* @var $action */

$action('index', function () {
    // $data['products'] = product\getAllProducts();
    // $data['products'] = ['test'];
    $data = [
        'welcome' => 'Hello world!',
    ];

    return render\view('site/index', $data);
});

$action('not-found', function () {
    print_r('site/not-found');

    // return view('site/not-found', $data);
});
