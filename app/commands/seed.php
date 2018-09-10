<?php

require_once ROOT_PATH.'/helpers/faker.php';

const PRODUCTS_TABLE_NAME = 'products';

function run()
{
    global $db;

    $sql = "SHOW TABLES LIKE '".PRODUCTS_TABLE_NAME."'";

    // Check is table exists
    if (!$db->query($sql)->fetch()) {
        echo 'creating products table...'.PHP_EOL;

        $sql = 'CREATE TABLE `'.PRODUCTS_TABLE_NAME.'` 
             ( 
                 `id`   INT(11) NOT NULL AUTO_INCREMENT, 
                 `name` VARCHAR(255) NOT NULL, 
                 `description` text NOT NULL, 
                 `price`       DECIMAL(10,3) NOT NULL, 
                 `picture_url` VARCHAR(255) NOT NULL,
                 PRIMARY KEY (`id`)
             ) 
             engine = InnoDB;';

        $db->exec($sql);

        fillProductsTableWithData();
    } else {
        echo 'All ok, table ready!'.PHP_EOL;
    }
}

function fillProductsTableWithData()
{
    global $db;

    function generateInsertString()
    {
        return " ('".faker\generateRandomString(64)
              ."','".faker\generateRandomString(500)
              ."', ".faker\generateRandomFloat(1, 30000)
              .", '".faker\generateRandomPictureUrl()."')";
    }

    for ($i = 0; $i < 200; ++$i) {
        $sql = 'INSERT INTO `products` (`name`, `description`, `price`, `picture_url`) VALUES';

        for ($j = 0; $j < 5000; ++$j) {
            $sql .= generateInsertString().', ';
        }

        $sql .= generateInsertString().';';

        var_dump($db->exec($sql));
    }
}
