#! /bin/bash

os=$(uname -s)

if [ "$os" = "Darwin" ]; then
    echo "Distribution : macOS"
    cd ..
    php artisan db:seed
else
    ../vendor/bin/sail artisan db:seed
fi
