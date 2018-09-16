<header class="header container">
    <img class="header__logo" src="/img/logo.svg" alt="">

    <a class="header__button button" href="/admin/create">Добавить продукт</a>
    <div class="header__sort sort">

        <p class="sort__text">Sort by:</p>

        <button class="sort__button sort__button--active button button--main button--active">id</button>
        <button class="sort__button button button--main">price</button>
    </div>
</header>

<main class="container">
    <?php foreach ($products as $key => $product): ?>
        <div class="product">
            <picture class="product__picture">
                <img src="/uploads/products/<?=$product['picture_name']; ?>" width="254px" height="254px" alt="">
            </picture>
            <div class="product__body">
                <p class="product__title"><?=$product['name']; ?></p>
                <p class="product__description"><?=strlen($product['description']) > 140 ? substr($product['description'],0,140)."..." : $product['description']; ?></p>
            </div>
            <div class="product__meta">
                <p class="product__price"><?=number_format($product['price'], 2, ',', ' ')?> $</p>
                <a class="product__button button button--main" href="/admin/edit?id=<?=$product['id']?>">Edit product</a>
                <a class="product__button button button--action" href="/admin/delete?id=<?=$product['id']?>" onclick="return confirm('Are you sure?')">Delete product</a>
            </div>
        </div>
    <?php endforeach; ?>
</main>


<footer class="footer container">
    <div class="footer__pager pager">
        <a class="pager__item button" href="/?page=<?=$page-1?>">prev</a>
        <a class="pager__item pager__item--active" href="/?page=<?=$page?>"><?=$page?></a>
        <a class="pager__item" href="/?page=<?=$page+1?>"><?=$page+1?></a>
        <a class="pager__item" href="/?page=<?=$page+2?>"><?=$page+2?></a>
        <a class="pager__item" href="/?page=<?=$page+3?>"><?=$page+3?></a>
        <a class="pager__item button" href="/?page=<?=$page+1?>">next</a>
    </div>

    <p class="footer__copyright">© 2018 product_shop</p>
</footer>
