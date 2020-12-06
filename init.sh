#!/bin/bash

cp -n .env.example .env

docker-compose up -d --build

wait

docker-compose exec php composer install

docker-compose exec php php artisan key:generate

docker-compose exec php php artisan migrate

docker-compose exec php php artisan db:seed

sleep 5

docker-compose run --rm node npm install

docker-compose run --rm node npm run development
