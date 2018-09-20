<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Products Shop</title>
    <link rel="stylesheet" href="/css/bootstrap-reboot.min.css">
    <link rel="stylesheet" href="/css/site.css">
</head>
<body>

    <?php require_once $templateFile; ?>

    <script src="/js/webfont.js"></script>
    <script>
    WebFont.load({
        google: {
            families: ['Roboto:400,500']
        }
    });
    </script>

    <script>
    /* Lazy Load */
    document.querySelectorAll('img[data-src].lazy').forEach((img) => {
        img.setAttribute('src', img.getAttribute('data-src'))
        img.onload = function() {
            img.removeAttribute('data-src')
        }
    })
    </script>
</body>
</html>
