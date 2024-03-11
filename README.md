# Notification - Task Service

## Getting Started

`docker compose build --no-cache` to build fresh images

`docker compose up` - to run application

Setup application:

```shell
docker exec -it transfersgo-php-1 sh
composer install
bin/console doctrine:database:create
bin/console doctrine:database:create --env=test
bin/console doctrine:fixtures:load
bin/console doctrine:fixtures:load --env=test
```

## Run tests

```shell
vendor/bin/codecept run Unit
vendor/bin/codecept run Functional
vendor/bin/phpstan
```

## Provider Setup

There are currently two providers: "Twilio" and "Mocker".

In .env file set `TWILIO_SID` and `TWILIO_TOKEN` parameters.

## Retry

There is no supervisor installed. We have to manually run the command to send scheduled notifications ( e.g. due to retry ).

`bin/console worker:notification:send-scheduled`

## Endpoints

Download postman and import `postman/notification.postman_collection.json`.

There are two endpoints:

1. create notification
2. update provider configuration

# What is done
