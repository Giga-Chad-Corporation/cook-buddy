#! /bin/sh

os=$(uname -s)

if [ "$os" = "Darwin" ]; then
    echo "Distribution : macOS"
    cd ..
    php artisan migrate
else
    ../vendor/bin/sail artisan migrate
fi
