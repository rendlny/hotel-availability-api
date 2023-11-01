# hotel-availability-api

A simplified PHP-based REST API service for a hotel availability system using the Symfony framework.


## API Routes

`/api/v1/hotel/availability`

## Tests
Phpunit & phpstan tests can be ran on this project.
```
composer phpunit
composer phpstan
```


## Packages

- jms/serializer-bundle
- friendsofsymfony/rest-bundle
- symfony/orm-pack
- symfony/validator
- nesbot/carbon

### dev-dependencies

- symfony/maker-bundle
- phpunit/phpunit
- doctrine/doctrine-fixtures-bundle | For adding fake data
- phpstan/phpstan | For bug checking

## Scripts

- `composer make:entity` | calls Doctrine make:entity command
- `composer make:migration` | calls Doctrine make:migration command
- `composer make:controller` | calls Doctrine make:controller command
- `composer migrate` | calls Doctrine doctrine:migrations:migrate command
- `composer cache:clear` | calls Doctrine cache:clear command
- `composer fixtures:load` | calls Doctrine doctrine:fixtures:load command
- `composer phpunit` | starts the phpunit tests
- `composer phpstan` | starts the phpstan analysis

