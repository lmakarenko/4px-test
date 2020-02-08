<p align="center"><img src="https://res.cloudinary.com/dtfbvvkyp/image/upload/v1566331377/laravel-logolockup-cmyk-red.svg" width="400"></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## Ручная настройка окружения для приложения

- Клонировать репозиторий: git clone https://github.com/lmakarenko/4px-test
- Перейти в папку приложения: cd 4px-test
- Запуск контейнеров докера: docker-compose up -d
- Зайти в контейнер php-fpm: docker-compose exec php-fpm bash
- Выполнить в контейнере php-fpm установку пакетов через composer: composer install
- Запустить миграции и посев: php artisan migrate:fresh --seed
