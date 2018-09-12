<?php

namespace models\product;

function find($limit = 20)
{
    global $db;

    $sql = <<<SQL
        SELECT *
          FROM products
         LIMIT ?;
SQL;

    $stmt = $db->prepare($sql);
    $stmt->execute([$limit]);

    return $stmt->fetchAll();
}

function findOne($id)
{
    global $db;

    $sql = <<<SQL
        SELECT *
          FROM products
         WHERE id = ?
SQL;

    $stmt = $db->prepare($sql);
    $stmt->execute([$id]);

    return $stmt->fetch();
}
