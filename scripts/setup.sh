#! /bin/bash

cd ..
composer require laravel/sail --dev
php artisan sail:install --with=mariadb
