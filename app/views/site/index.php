<h1>Main page</h1>

<ul>
<?php foreach ($products as $key => $product): ?>
    <li>
    
        <h4><?php echo $product['name']; ?></h4>
        <p><?php echo $product['description']; ?></p>
    <a href="/admin/edit?id=<?=$product['id']; ?>">Edit</a>
    <a href="/admin/delete?id=<?=$product['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
    <img src="/uploads/products/<?=$product['picture_name']; ?>" height="300px" width="300px" alt="">
    </li>
<?php endforeach; ?>
</ul>
