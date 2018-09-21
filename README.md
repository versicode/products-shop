# Products Shop Challenge

### Tech: php, mysql, memcached
### Tools: docker, nginx, php-fpm

![Project preview](https://github.com/versicode/products-shop/raw/master/screen.jpg "Project preview")

**Installation**:
- clone repo
- ```cp app/config/main.php.dist app/config/main.php```
- set required params in app/config/main.php or keep as is
- seed database with test data (it may take a while) ```docker exec -it proshop-php-fpm sh -c "php command.php seed"```
- ```docker-compose up```
- warmup a cache (it may take a while too) ```docker exec -it proshop-php-fpm sh -c "php command.php cache"```
- Open browser at http://localhost:3400/
