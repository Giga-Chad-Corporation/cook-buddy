#! /bin/bash

../vendor/bin/sail up -d
../vendor/bin/sail artisan config:cache
