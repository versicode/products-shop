<?php

namespace models\product;

function find($orderByField, $orderByDirection, $offset = 0, $limit = 10)
{
    global $db;

    $sql = "SELECT *
              FROM products
             ORDER BY {$orderByField} {$orderByDirection}
             LIMIT :limit OFFSET :offset;";

    $query = $db->prepare($sql);
    $query->execute([
        ':limit' => $limit,
        ':offset' => $offset,
    ]);

    return $query->fetchAll();
}

function findOne($id)
{
    global $db;

    if (!$id) {
        return null;
    }

    $sql = 'SELECT *
              FROM products
             WHERE id = ?';

    $query = $db->prepare($sql);
    $query->execute([$id]);

    return $query->fetch();
}

function insertOne($name, $price, $description, $pictureName)
{
    global $db;

    $sql = 'INSERT INTO products (name, price, description, picture_name)
            VALUES (:name, :price, :description, :picture_name)';

    $query = $db->prepare($sql);

    return $query->execute([
        ':name' => $name,
        ':price' => $price,
        ':description' => $description,
        ':picture_name' => $pictureName,
    ]);
}

function updateOne($id, $name, $price, $description, $pictureName)
{
    global $db;

    // var_dump($id, $name, $price, $description, $pictureName);

    $sql = 'UPDATE products
               SET name = :name,
                   price = :price,
                   description = :description,
                   picture_name = :picture_name
             WHERE id = :id';

    $query = $db->prepare($sql);

    return $query->execute([
        ':id' => $id,
        ':name' => $name,
        ':price' => $price,
        ':description' => $description,
        ':picture_name' => $pictureName,
    ]);
}

function removeOne($id)
{
    global $db;

    $sql = 'DELETE
              FROM products
             WHERE id = ?;';

    $query = $db->prepare($sql);

    return $query->execute([$id]);
}
