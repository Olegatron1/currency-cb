1) git clone repo
2) composer install
3) ./vendor/bin/sail up
4) ./vendor/bin/sail artisan migrate
5) open in postman [Currency.postman_collection.json](Currency.postman_collection.json)
6) use GetRate http://0.0.0.0:80/api/rate?date=2024-05-08&quote=USD&base=RUR