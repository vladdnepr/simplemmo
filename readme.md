# Online game Simple MMO

## Requirements

1. Linux-based OS
1. Nginx, PHP-FPM via socket, Memcache via TCP/IP, MySQL
2. PHP Extensions: PDO, PDO Mysql, Memcached
3. Composer

## Install

1. All files must be placed at `/var/www/simplemmo.local`
2. In file `/etc/nginx/nginx.conf` in `http` section include file `/var/www/simplemmo.local/config/nginx.conf`
3. Restart `nginx`
4. In file `/etc/hosts` put `127.0.0.1 simplemmo.local`
5. Run `composer install`
6. Create MySQL user `simplemmo` with password `simplemmo`
7. Create database with name `simplemmo` and grant full access for user `simplemmo`
8. Import into database `simplemmo` file `sql/data.sql` from project root.
9. Run bot generation: `./bin/create_bots 1000`
10. Open in browser `simplemmo.local`

## Testing

Run `./vendor/bin/phpunit`
