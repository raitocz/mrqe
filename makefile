run:
	docker-compose up -d

init: run
	docker-compose exec app composer install