#! /bin/bash
source /home/sebi/.bashrc
clear

../vendor/bin/sail up -d
../vendor/bin/sail artisan config:clear
../vendor/bin/sail artisan config:cache

until ../vendor/bin/sail exec -T mariadb mysqladmin ping -h"127.0.0.1" -P3306 -ucook_master -ppassword --silent; do
    sleep 1
done

./migrate.sh
../vendor/bin/sail logs -f
