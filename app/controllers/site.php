<?php

/* @var $action */

$action('index', function ($params) {
    $page = isset($params['page']) ? router\parseIdentifier($params['page'], 1) : 1;
    $sortBy = isset($params['sort']) ? $params['sort'] : 'id_desc';

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
            'direction' => 'DESC',
        ],
        'price_desc' => [
            'field' => 'price',
            'direction' => 'DESC'
        ],
    ];

    ['field' => $orderByField, 'direction' => $orderByDirection] = reset(array_filter($possibleSorts, function($item, $key) use($sortBy) {
        // var_dump($key.' and '.$sortBy.'<br>');
        return $key === $sortBy;
    }, ARRAY_FILTER_USE_BOTH));

    $limit = 10;
    $offset = ($page - 1) * $limit;

    $data['products'] = models\product\find($orderByField, $orderByDirection, $offset, $limit);
    $data['page'] = $page;

    render\view('site/index', $data, 'site_layout');
});

$action('not-found', function () {
    render\view('site/404', [], 'site_layout');
});
