#!/bin/bash

chgrp -R www-data storage boostrap/cache && \
    chown -R www-data storage bootstrap/cache && \
    chmod -R ug+rwx storage bootstrap/cache && \

touch storage/logs/laravel.log && chmod 775 storage/logs/laravel.log && chown www-data storage/logs/laravel.log

composer install && php artisan key:generate && yarn install

php artisan config:cache
php artisan migrate:fresh --seed && echo "Done..."
