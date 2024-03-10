PHP_CONTAINER=docker exec -it transfersgo-php-1

# commands from project dir

build:
	@docker compose build --pull --no-cache

startup:
	@docker compose up

stop:
	@docker compose down --remove-orphans

shell:
	@$(PHP_CONTAINER) sh

# commands available inside docker container

php-stan:
	vendor/bin/phpstan analyse -c phpstan.neon --memory-limit=-1

test:
	@make test-unit
	@make test-functional

test-unit:
	vendor/bin/codecept run tests/unit

test-functional:
	vendor/bin/codecept run tests/functional

log-dev:
	tail -f var/log/dev.log

build-dev:
	composer install
	php bin/console doctrine:database:drop --if-exists --force
	php bin/console doctrine:database:create --if-not-exists
	php bin/console doctrine:migrations:migrate --no-interaction
	php bin/console doctrine:fixtures:load --append
