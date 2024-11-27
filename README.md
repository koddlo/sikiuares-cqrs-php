# SIKIAURES (cqrs-php)

### Description
This is demo version of code from e-book: Sikiuares - CQRS w PHP.

If you are from Poland, you can read more [here](https://sklep.koddlo.pl).

### Installation
Create and complete the configuration file: ```cp .env.local.dist .env.local```.

Optionally, adapt the containers to your own configurations: ```docker-compose.yml```. 

Run containers: ```docker-compose up -d```. 

Install dependencies: ```docker exec php composer install```.

Run database migrations:
```
docker exec php bin/console doctrine:database:drop --if-exists --force
docker exec php bin/console doctrine:database:create --if-not-exists
docker exec php bin/console doctrine:migration:migrate --no-interaction
```

Create three sample working days using CLI:
```
docker exec php bin/console koddlo:booking:create-sample-working-days
```

### Testing
- ECS
```
docker exec php ./vendor/bin/ecs check --config ecs.php
```

- PHPSTAN
```
docker exec php ./vendor/bin/phpstan analyse -l 10 -c phpstan.neon
```

- DEPTRAC
```
docker exec php ./vendor/bin/deptrac analyse --config-file=deptrac.yaml
```

- PHPUNIT
```
docker exec php ./vendor/bin/phpunit -c phpunit.xml
```

- BEHAT:
```
docker exec php bin/console --env=test doctrine:database:drop --if-exists --force
docker exec php bin/console --env=test doctrine:database:create --if-not-exists
docker exec php bin/console --env=test doctrine:migration:migrate --no-interaction
docker exec php ./vendor/bin/behat -c behat.yml -f progress
```