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


    // Used to make cache warmup faster
    $cached = [];
    $getProductsIdsWithCache = function ($field, $direction, $controlValue, $limit) use (&$cached) {
        if (count($cached) === 0) {
            $cached = \models\product\findIds($field, $direction, $controlValue, $limit * 100);
        }

        return array_splice($cached, 0, $limit);
    };


    $lastPage = floor($productsCount / $limit) + 1;

    warmById($lastPage, $limit, $getProductsIdsWithCache);
    warmByPrice($lastPage, $limit, $getProductsIdsWithCache);

    echo PHP_EOL.'Done!'.PHP_EOL;
}

function warmById($lastPage, $limit, $getProductsIdsWithCache)
{
    global $memcached;

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

function warmByPrice($lastPage, $limit, $getProductsIdsWithCache)
{
    global $memcached;

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

        $itemsForNextLoopCycle = [];

        $controlValue = $sort['initialValue'];
        for ($p = 1; $p < $lastPage; ++$p) {
            $items = [];

            $itemsForNextLoopCycleCount = count($itemsForNextLoopCycle);

            if ($itemsForNextLoopCycleCount === 0) {
                // If no items from previous loop cycle - just take new items
                // $ids = \models\product\findIds($sort['field'], $sort['direction'], $controlValue);
                $ids = $getProductsIdsWithCache($sort['field'], $sort['direction'], $controlValue, $limit);
            } elseif ($itemsForNextLoopCycleCount > $limit) {
                // Take from array only $limit items, because can be more
                // than $limit items with same price
                $items = array_splice($itemsForNextLoopCycle, 0, $limit);
                $ids = $items;
            } elseif ($itemsForNextLoopCycleCount > 0) {
                $items = $itemsForNextLoopCycle;
                $itemsForNextLoopCycle = [];

                // Select from DB as much as we need
                // $ids = \models\product\findIds($sort['field'], $sort['direction'], $controlValue, $limit - count($items));
                $ids = $getProductsIdsWithCache($sort['field'], $sort['direction'], $controlValue, $limit - count($items));

                // Collect items from previous loop cycle + new selected from DB
                $ids = array_merge($items, $ids);
            }

            $memcached->set("page_{$p}_{$sort['field']}_{$sort['direction']}", $ids, 0);

            if ($p % 1000 === 0) {
                echo '.';
            }

            $controlValue = \models\product\findPrice(end($ids));

            // Take only products not cached yet
            $itemsForNextLoopCycle = \models\product\findIdsByPrice($controlValue, $ids);
        }
    }

    echo PHP_EOL.'price sort warmup done!'.PHP_EOL;
}
