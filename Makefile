init: docker-down-clear docker-pull docker-build docker-up

docker-up:
	docker-compose up -d

docker-down:
	docker-compose down --remove-orphans

docker-down-clear:
	docker-compose down -v --remove-orphans

docker-build:
	docker-compose build

docker-pull:
	docker-compose pull

docker-bash:
	docker-compose exec -it php-fpm bash

wait-db:
	docker-compose run --rm php-fpm wait-for-it mysql:3306 -t 30