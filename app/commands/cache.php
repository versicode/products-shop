<?php

/* cache warmup */

function run()
{
    global $memcached;

    // cacher\productsCount();
    // cacher\productPages();
    // echo $memcached->get('products_count');
    // echo print_r($memcached->get('page_2_id_asc'));
    // echo PHP_EOL;
    print_r($memcached->getstats());
}
