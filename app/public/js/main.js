/* Lazy Load */
document.querySelectorAll('img[data-src].lazy').forEach((img) => {
    img.setAttribute('src', img.getAttribute('data-src'))
    img.onload = function() {
        img.removeAttribute('data-src')
    }
})
