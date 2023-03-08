.RECIPEPREIX +=
.DEFAULT_GOAL := help
PROJECT_NAME=jump
include .env
help:
	@echo "Welcome to IT Support."
install:
	@composer install
up:
	@docker-compose up -d
down:
	@docker-compose down
test-cover:
	docker exec -e XDEBUG_MODE=coverage catalog_app vendor/bin/phpunit --coverage-html .reports/

test:
	@docker exec $(PROJECT_NAME)_app php artisan test
migrate:
	@docker exec $(PROJECT_NAME)_app php artisan migrate
seed:
	@docker exec $(PROJECT_NAME)_app php artisan db:seed
analyse:
	./vendor/bin/phpstan analyse --xdebug
generate:
	@docker exec $(PROJECT_NAME)_app php artisan ide-helper:models --nowrite
app-bash:
	@docker exec -it $(PROJECT_NAME)_app bash
nginx-bash:
	@docker exec -it $(PROJECT_NAME)_nginx /bin/sh
mysql-bash:
	@docker exec -it $(PROJECT_NAME)_db /bin/bash
redis-bash:
	@docker exec -it $(PROJECT_NAME)_redis /bin/sh
