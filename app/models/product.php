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
             WHERE id = ?;';

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

function count()
{
    global $db;

    $sql = 'SELECT COUNT(*)
              FROM products;';

    return $db->query($sql)->fetchColumn();
}

/* ======== */
/* Not safe */
/* ======== */

function findByIds($ids, $field, $direction)
{
    global $db;

    $ids = implode(',', $ids);

    $sql = "SELECT *
              FROM products
              WHERE id IN ({$ids})
              ORDER BY {$field} {$direction};";

    return $db->query($sql)->fetchAll();
}

function findIds($field, $direction, $controlValue, $limit = 10)
{
    global $db;

    $symbol = $direction === 'ASC' ? '>' : '<';

    $sql = "SELECT id
              FROM products
             WHERE {$field} {$symbol} {$controlValue}
             ORDER BY {$field} {$direction}
             LIMIT {$limit};";

    return $db->query($sql)->fetchAll(\PDO::FETCH_COLUMN, 0);
}

function findIdsByPrice($price, $notInIds)
{
    global $db;

    $sql = "SELECT id
              FROM products
              WHERE price = {$price}
              AND id NOT IN(".implode(',', $notInIds).');';

    return $db->query($sql)->fetchAll(\PDO::FETCH_COLUMN, 0);
}

function findMax($field)
{
    global $db;

    $sql = "SELECT MAX({$field})
              FROM products;";

    return $db->query($sql)->fetchColumn();
}

function findPrice($id)
{
    global $db;

    $sql = "SELECT price
              FROM products
             WHERE id = {$id};";

    return $db->query($sql)->fetchColumn();
}
