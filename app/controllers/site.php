<?php

/* @var $action */

$action('index', function (&$params) {
    global $memcached, $config;

    $buildUrl = function ($query) use ($params) {
        return router\makeAbsoluteUrl('?'.http_build_query(array_merge($params, $query)));
    };

    $page = isset($params['page']) ? router\parseIdentifier($params['page'], 1) : 1;
    $sortBy = isset($params['sort']) ? $params['sort'] : 'id_asc';

    $possibleSorts = [
        'id_asc' => [
            'field' => 'id',
            'direction' => 'ASC',
        ],
        'id_desc' => [
            'field' => 'id',
            'direction' => 'DESC',
        ],
        'price_asc' => [
            'field' => 'price',
            'direction' => 'ASC',
        ],
        'price_desc' => [
            'field' => 'price',
            'direction' => 'DESC',
        ],
    ];

    $targetSort = array_filter($possibleSorts, function ($item, $key) use ($sortBy) {
        return $key === $sortBy;
    }, ARRAY_FILTER_USE_BOTH);

    ['field' => $orderByField, 'direction' => $orderByDirection] = reset($targetSort);

    $limit = $config['template']['products_per_page'];
    $offset = ($page - 1) * $limit;

    // try get products from cache
    if ($productsIds = $memcached->get("page_{$page}_{$orderByField}_{$orderByDirection}")) {
        // var_dump('from cache');
        $data['products'] = models\product\findByIds($productsIds, $orderByField, $orderByDirection);
    } else {
        $data['products'] = models\product\find($orderByField, $orderByDirection, $offset, $limit);
    }

    $data['page'] = $page;
    $data['sort'] = $sortBy;

    if (!$productsCount = $memcached->get('products_count')) {
        $productsCount = cacher\productsCount();

        $memcached->set('products_count', $productsCount, 60 * 60);
    }

    $lastPage = floor($productsCount / $limit);

    $data['urls'] = [
        'id_sort'    => $sortBy === 'id_asc'    ? $buildUrl(['sort' => 'id_desc'])    : $buildUrl(['sort' => 'id_asc']),
        'price_sort' => $sortBy === 'price_asc' ? $buildUrl(['sort' => 'price_desc']) : $buildUrl(['sort' => 'price_asc']),

        'prev_page'    =>  $page > 1              ? $buildUrl(['page' =>  $page - 1])  : false,
        'next_page'    =>  $page < $lastPage      ? $buildUrl(['page' =>  $page + 1])  : false,
        'next_page_2'  =>  $page + 2 < $lastPage  ? $buildUrl(['page' =>  $page + 2])  : false,
        'next_page_50' =>  $page + 50 < $lastPage ? $buildUrl(['page' =>  $page + 50]) : false,
        'first_page'   =>  $page > 1              ? $buildUrl(['page' =>  1])          : false,
        'last_page'    =>  $page < $lastPage      ? $buildUrl(['page' =>  $lastPage])  : false,
    ];

    render\view('site/index', $data, 'site_layout');
});

$action('not-found', function () {
    render\view('site/404', [], 'site_layout');
});
