# Онлайн игра Simple MMO

## Требования

1. Операционная система Linux
1. Nginx, PHP-FPM, Memcache, MySQL
2. Расширения РHP: PDO, PDO Mysql, Memcached
3. Composer

## Установка

1. Код проекта должна располагаться в директории `/var/www/simplemmo.local`
2. В файле `/etc/nginx/nginx.conf` в секции `http` нужно подключить файл `/var/www/simplemmo.local/config/nginx.conf`
3. В файле `/etc/hosts` нужно прописать `127.0.0.1 simplemmo.local`
4. Запустить в директории проекта `composer install`
5. Создать пользователя MySQL `simplemmo` с паролем `simplemmo`
6. Создать базу с именем `simplemmo` и дать пользователю `simplemmo` полные права не нее
7. Импортировать в базу `simplemmo` файл `sql/data.sql` из директории проекта
8. Запустить генерацию игроков-ботов: `./bin/create_bots 1000`
9. Открыть в браузере `simplemmo.local`

## Тестирование

Запустить в директории проекта `./vendor/bin/phpunit`