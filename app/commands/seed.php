<?php

require_once ROOT_PATH.'/helpers/faker.php';

function run()
{
    global $db;

    $sql = "SHOW TABLES LIKE 'products'";

    // Check is table exists
    if (!$db->query($sql)->fetch()) {
        echo 'creating products table...'.PHP_EOL;

        $sql = 'CREATE TABLE `products` 
             ( 
                 `id`   INT(11) NOT NULL AUTO_INCREMENT, 
                 `name` VARCHAR(255) NOT NULL, 
                 `description` text NOT NULL, 
                 `price`       DECIMAL(10,3) NOT NULL, 
                 `picture_name` VARCHAR(255) NOT NULL,
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
        return " ('".faker\generateRandomString(mt_rand(8, 16))
              ."','".faker\generateRandomString(500)
              ."', ".faker\generateRandomFloat(1, 30000)
              .", '".faker\generateRandomPictureName()."')";
    }

    for ($i = 0; $i < 200; ++$i) {
        $sql = 'INSERT INTO `products` (`name`, `description`, `price`, `picture_name`) VALUES';

        for ($j = 1; $j < 5000; ++$j) {
            $sql .= generateInsertString().', ';
        }

        $sql .= generateInsertString().';';
        $db->exec($sql);
        echo '.';
    }

    echo PHP_EOL.'1000000 products addded!'.PHP_EOL;
}
