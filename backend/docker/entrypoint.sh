#!/bin/sh

# Start PHP-FPM in the background
php-fpm &

# Start cron in the foreground
cron -f