# Sandbox Symfony

## Requirement

- [docker](https://docs.docker.com/engine/install/)
- [docker-compose](https://docs.docker.com/compose/install/)

## Launch

Launch apapche, php-fpm, mysql and node with :
```
docker-compose up -d
```

Access to [localhost](http://localhost/)

## Install

- Install php dependencies
```
docker exec -it symfony-sandbox_php_1 composer install
```
- Execute doctrine migrations
```
docker exec -it symfony-sandbox_php_1 doctrine:migration:migrate -n
```
- Load fixtures
```
docker exec -it symfony-sandbox_php_1 doctrine:fixtures:load -n
```

## QA tools

Lint with
```
vendor/bin/php-cs-fixer fix
```

Static code analysis with 
```
vendor/bin/phpstan analyse
```
