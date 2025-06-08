init: docker-down-clear docker-pull docker-build docker-up composer-install wait-db migrate seed frontend
frontend: npm-install build-css-prod

up: docker-up
down: docker-down

bash: docker-bash

docker-up:
	docker-compose up -d

docker-down:
	docker-compose down --remove-orphans

docker-down-clear:
	docker-compose down -v --remove-orphans

docker-pull:
	docker-compose pull

docker-build:
	docker-compose build

composer-install:
	docker-compose exec php-fpm composer install

docker-bash:
	docker-compose exec -it php-fpm bash

wait-db:
	docker-compose exec php-fpm wait-for-it mysql:3306 -t 30

migrate:
	docker-compose exec php-fpm php bin/migrate migrate

migrate-rollback:
	docker-compose exec php-fpm php bin/migrate rollback

seed:
	docker-compose exec php-fpm php database/seed.php

npm-install:
	npm install

build-css-prod:
	npm run build-css-prod

build-css-watch:
	npm run build-css

cs-fix:
	docker-compose exec php-fpm PHP_CS_FIXER_IGNORE_ENV=1 composer cs-fix

phpstan:
	docker-compose exec php-fpm composer phpstan

phpstan-baseline:
	docker-compose exec php-fpm composer phpstan-baseline
