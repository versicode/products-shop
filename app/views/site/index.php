<?php
$idButtonClass = '';
$priceButtonClass = '';
$topClass = ' sort__button--active sort__button--top button--active';
$bottomClass = ' sort__button--active button--active';

switch ($sort) {
    case 'id_asc':
        $idButtonClass = $topClass;
        break;
    case 'id_desc':
        $idButtonClass = $bottomClass;
        break;
    case 'price_asc':
        $priceButtonClass = $topClass;
        break;
    case 'price_desc':
        $priceButtonClass = $bottomClass;
        break;
}

?>
<header class="header container">
    <img class="header__logo" src="/img/logo.svg" alt="">

    <a class="header__button button" href="/admin/create">Добавить продукт</a>
    <div class="header__sort sort">

        <p class="sort__text">Sort by:</p>

        <a class="sort__button button button--main<?=$idButtonClass; ?>"
           href="<?=$urls['id_sort']; ?>">id</a>
        <a class="sort__button button button--main<?=$priceButtonClass; ?>"
           href="<?=$urls['price_sort']; ?>">price</a>
    </div>
</header>

<main class="container">
    <?php foreach ($products as $key => $product): ?>
        <div class="product">
            <picture class="product__picture">
                <img class="lazy" data-src="/uploads/products/<?=$product['picture_name']; ?>" width="254px" height="254px" alt="">
            </picture>
            <div class="product__body">
                <p class="product__title"><?=$product['name']; ?></p>
                <p class="product__description"><?=strlen($product['description']) > 140 ? substr($product['description'], 0, 140).'...' : $product['description']; ?></p>
            </div>
            <div class="product__meta">
                <p class="product__price"><?=number_format($product['price'], 2, ',', ' '); ?> $</p>
                <a class="product__button button button--main" href="/admin/edit?id=<?=$product['id']; ?>">Edit product</a>
                <a class="product__button button button--action" href="/admin/delete?id=<?=$product['id']; ?>" onclick="return confirm('Are you sure?')">Delete product</a>
            </div>
        </div>
    <?php endforeach; ?>
</main>


<footer class="footer container">
    <div class="footer__pager pager">

        <?php if ($urls['first_page']): ?>
            <a class="pager__item button" href="<?=$urls['first_page']; ?>">first</a>
        <?php endif; ?>

        <?php if ($urls['prev_page']): ?>
            <a class="pager__item button" href="<?=$urls['prev_page']; ?>">prev</a>
        <?php endif; ?>

        <span class="pager__item pager__item--active"><?=$page; ?></span>

        <?php if ($urls['next_page']): ?>
            <a class="pager__item" href="<?=$urls['next_page']; ?>"><?=$page + 1; ?></a>
        <?php endif; ?>

        <?php if ($urls['next_page_2']): ?>
            <a class="pager__item" href="<?=$urls['next_page_2']; ?>"><?=$page + 2; ?></a>
        <?php endif; ?>

        <?php if ($urls['next_page_50']): ?>
            <span class="pager__item">...</span>
            <a class="pager__item" href="<?=$urls['next_page_50']; ?>"><?=$page + 50; ?></a>
        <?php endif; ?>

        <?php if ($urls['next_page']): ?>
            <a class="pager__item button" href="<?=$urls['next_page']; ?>">next</a>
        <?php endif; ?>

        <?php if ($urls['last_page']): ?>
            <a class="pager__item button" href="<?=$urls['last_page'];?>">last</a>
        <?php endif; ?>
    </div>

    <p class="footer__copyright">© 2018 product_shop</p>
</footer>
