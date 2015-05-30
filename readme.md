# Онлайн игра Simple MMO

## Требования

1. Операционная система Linux
1. Nginx, PHP-FPM via socket, Memcache via TCP/IP, MySQL
2. Расширения РHP: PDO, PDO Mysql, Memcached
3. Composer

## Установка

1. Код проекта должна располагаться в директории `/var/www/simplemmo.local`
2. В файле `/etc/nginx/nginx.conf` в секции `http` нужно подключить файл `/var/www/simplemmo.local/config/nginx.conf`
3. Перезапустить `nginx`
4. В файле `/etc/hosts` нужно прописать `127.0.0.1 simplemmo.local`
5. Запустить в директории проекта `composer install`
6. Создать пользователя MySQL `simplemmo` с паролем `simplemmo`
7. Создать базу с именем `simplemmo` и дать пользователю `simplemmo` полные права не нее
8. Импортировать в базу `simplemmo` файл `sql/data.sql` из директории проекта
9. Запустить генерацию игроков-ботов: `./bin/create_bots 1000`
10. Открыть в браузере `simplemmo.local`

## Тестирование

Запустить в директории проекта `./vendor/bin/phpunit`