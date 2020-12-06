#!/bin/bash

cp -n .env.example .env

docker-compose up -d --build

wait

sleep 5

docker-compose exec php composer install

docker-compose exec php php artisan key:generate

docker-compose exec php php artisan migrate

docker-compose exec php php artisan db:seed

docker-compose exec php php artisan cache:clear

sleep 5

docker-compose run --rm node yarn

docker-compose run --rm node npm run development
