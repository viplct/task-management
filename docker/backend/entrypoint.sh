#!/bin/sh

php artisan config:cache
php artisan route:cache

# Run DB migrations
php artisan migrate --force

# Start Laravel dev server
php artisan serve --host=0.0.0.0 --port=8000