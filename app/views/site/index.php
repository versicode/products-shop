<h1>Main page</h1>

<ul>
<?php foreach ($products as $key => $product): ?>
    <li><?php echo $product['name']; ?></li>
<?php endforeach; ?>
</ul>
