<?php

/* cache warmup */

function run()
{
    global $memcached;

    cacher\productsCount();
    cacher\productPages();
    // print_r($memcached->getstats());
    // print_r($memcached->flush());
}
