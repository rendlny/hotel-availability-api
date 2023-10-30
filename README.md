# hotel-availability-api

A simplified PHP-based REST API service for a hotel availability system using the Symfony framework.

## Packages

- jms/serializer-bundle
- friendsofsymfony/rest-bundle
- symfony/orm-pack
- symfony/validator

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

## Tests

```
composer phpunit
composer phpstan
```

## API Routes

`api/hotel/availability`
