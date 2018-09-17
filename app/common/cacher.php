<?php

namespace cacher;

function productsCount()
{
    global $memcached;

    $productsCount = \models\product\count();

    $memcached->set('products_count', $productsCount, 60*60);

    return $productsCount;
}

function productPages() {
    global $config, $memcached;

    if (!$productsCount = $memcached->get('products_count')) {
        $productsCount = productsCount();
    }

    $limit = $config['template']['products_per_page'];

    $lastPage = floor($productsCount / $limit);

    for ($i=1; $i < $lastPage; $i++) {
        $offset = ($i - 1) * $limit;

        $products = \models\product\find('id', 'ASC', $offset, $limit);
        $memcached->set("page_{$i}_id_asc", array_map(function($product) {
            return $product['id'];
        }, $products), 60*60);

        echo '.';
    }

    echo 'Done!'.PHP_EOL;
}
