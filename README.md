# SIKIAURES (cqrs-php)

### Description
This is demo version of code from e-book: Sikiuares - CQRS w PHP.

If you are from Poland, you can read more [here](https://sklep.koddlo.pl).

### Installation
Create and complete the configuration file: ```cp .env .env.local```.

Optionally, adapt the containers to your own configurations: ```docker-compose.yml```. 

Run containers: ```docker-compose up -d```. 

Install dependencies: ```docker exec php composer install```.

Run database migrations:
```
bin/console doctrine:database:drop --if-exists --force
bin/console doctrine:database:create --if-not-exists
bin/console doctrine:migration:migrate --no-interaction
```

Create three sample working days using CLI:
```
bin/console koddlo:booking:create-sample-working-days
```

### Testing
- ECS
```
./vendor/bin/ecs check --config ecs.php
```

- PHPSTAN
```
./vendor/bin/phpstan analyse -l 9 -c phpstan.neon
```

- DEPTRAC
```
./vendor/bin/deptrac analyse --config-file=deptrac.yaml
```

- PHPUNIT
```
./vendor/bin/phpunit -c phpunit.xml
```

- BEHAT:
```
bin/console --env=test doctrine:database:drop --if-exists --force
bin/console --env=test doctrine:database:create --if-not-exists
bin/console --env=test doctrine:migration:migrate --no-interaction
./vendor/bin/behat -c behat.yml -f progress
```