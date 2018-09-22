<?php

namespace cacher;

function productsCount()
{
    global $memcached;

    $productsCount = \models\product\count();

    $memcached->set('products_count', $productsCount, 60 * 60);

    return $productsCount;
}

function productPages()
{
    global $config, $memcached;

    if (!$productsCount = $memcached->get('products_count')) {
        $productsCount = productsCount();
    }

    $limit = $config['template']['products_per_page'];

    $lastPage = floor($productsCount / $limit) + 1;

    warmById($lastPage, $limit);
    warmByPrice($lastPage, $limit);

    echo PHP_EOL.'Done!'.PHP_EOL;
}

function warmById($lastPage, $limit)
{
    global $memcached;

    // Used to make cache warmup faster
    $cached = [];
    $getProductsIdsWithCache = function ($field, $direction, $controlValue, $limit) use (&$cached) {
        if (count($cached) === 0) {
            $cached = \models\product\findIds($field, $direction, $controlValue, $limit * 100);
        }

        return array_splice($cached, 0, $limit);
    };

    $possibleSorts = [
        'id_asc' => [
            'field' => 'id',
            'direction' => 'ASC',
            'initialValue' => 0,
        ],
        'id_desc' => [
            'field' => 'id',
            'direction' => 'DESC',
            'initialValue' => \models\product\findMax('id'),
        ],
    ];

    foreach ($possibleSorts as $key => $sort) {
        echo PHP_EOL."Cache warming {$key}".PHP_EOL;

        $controlValue = $sort['initialValue'];
        for ($p = 1; $p < $lastPage; ++$p) {
            // $ids = \models\product\findIds($sort['field'], $sort['direction'], $controlValue);
            $ids = $getProductsIdsWithCache($sort['field'], $sort['direction'], $controlValue, $limit);
            $memcached->set("page_{$p}_{$sort['field']}_{$sort['direction']}", $ids, 0);

            if ($p % 1000 === 0) {
                echo '.';
            }

            $controlValue = end($ids);
        }
    }

    echo PHP_EOL.'id sort warmup done!'.PHP_EOL;
}

function warmByPrice($lastPage, $limit)
{
    global $memcached;

    // Get all not unique prices
    $groupedPrices = \models\product\findGroupedPrices();

    // Used to make cache warmup faster
    $cached = [];

    $getProductsIdsChunk = function ($field, $direction, $controlValue, $limit, $count = null) use (&$groupedPrices, &$getProductsIdsChunk) {
        $ids = \models\product\findIds($field, $direction, $controlValue, $limit * 1000);

        $idsCount = count($ids);
        if (in_array(\models\product\findPrice(end($ids)), $groupedPrices) && $count !== $idsCount) {
            return $getProductsIdsChunk($field, $direction, $controlValue, $limit + 10, $idsCount);
        } else {
            return $ids;
        }
    };

    $getProductsIdsWithCache = function ($field, $direction, $controlValue, $limit) use (&$cached, &$getProductsIdsChunk) {
        if (count($cached) === 0) {
            $cached = $getProductsIdsChunk($field, $direction, $controlValue, $limit);
        }

        return array_splice($cached, 0, $limit);
    };

    $possibleSorts = [
        'price_asc' => [
            'field' => 'price',
            'direction' => 'ASC',
            'initialValue' => 0,
        ],
        'price_desc' => [
            'field' => 'price',
            'direction' => 'DESC',
            'initialValue' => \models\product\findMax('price'),
        ],
    ];

    foreach ($possibleSorts as $key => $sort) {
        echo PHP_EOL."Cache warming {$key}".PHP_EOL;

        $controlValue = $sort['initialValue'];
        for ($p = 1; $p < $lastPage; ++$p) {
            $ids = $getProductsIdsWithCache($sort['field'], $sort['direction'], $controlValue, $limit);

            $memcached->set("page_{$p}_{$sort['field']}_{$sort['direction']}", $ids, 0);

            if ($p % 1000 === 0) {
                echo '.';
            }

            $controlValue = \models\product\findPrice(end($ids));
        }
    }

    echo PHP_EOL.'price sort warmup done!'.PHP_EOL;
}
