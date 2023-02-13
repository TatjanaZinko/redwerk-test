#!/bin/sh
set -euo pipefail

user=www-data
group=www-data

if [ ! -e index.php ] && [ ! -e wp-includes/version.php ]; then
    echo "Copy Wordpress core"
    tar --exclude wp-config.php --exclude wp-content --create --file - --directory /usr/src/wordpress --owner "$user" --group "$group" . | tar --extract --file -
else
    echo "Wordpress core is existing"
    echo "Skip copying"
fi

echo "Starting PHPFPM..."
php-fpm