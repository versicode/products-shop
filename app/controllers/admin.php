<?php

/* @var $action */

$action('create', function (&$params, &$body) {
    if ($body['submit']) {
        ['name' => $name, 'price' => $price, 'description' => $description] = input\cleanFlatArray($body);

        $pictureName = input\uploadProductPicture('picture');

        global $memcached;
        if (models\product\insertOne($name, $price, $description, $pictureName)) {
            $memcached->increment('products_count');
        }

        router\redirect('site/index');
    }

    render\view('admin/create', [], 'admin_layout');
});

$action('edit', function (&$params, &$body) {
    $id = isset($params['id']) ? router\parseIdentifier($params['id']) : null;

    $product = models\product\findOne($id);

    if (!$product) {
        router\redirect('admin/create');
    }

    if (isset($body['submit'])) {
        ['name' => $name, 'price' => $price, 'description' => $description] = input\cleanFlatArray($body);

        $pictureName = $product['picture_name'];

        if ($_FILES['picture']['tmp_name']) {
            $pictureName = input\uploadProductPicture('picture', $product['picture_name']);
        }

        models\product\updateOne($id, $name, $price, $description, $pictureName);

        router\redirect('site/index');
    }

    $data = [
        'product' => $product,
    ];

    render\view('admin/edit', ['product' => $product], 'admin_layout');
});

$action('delete', function (&$params) {
    $id = isset($params['id']) ? router\parseIdentifier($params['id']) : null;

    $product = models\product\findOne($id);

    if (!$product) {
        router\redirect('site/index');
    }

    global $memcached;
    if (models\product\removeOne($id)) {
        $memcached->decrement('products_count');
    }

    router\redirect('site/index');
});
