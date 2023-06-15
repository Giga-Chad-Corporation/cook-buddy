#! /bin/bash
source /home/sebi/.bashrc

../vendor/bin/sail artisan config:cache
../vendor/bin/sail up -d
../vendor/bin/sail logs -f


