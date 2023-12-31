# hotel-availability-api

A simplified PHP-based REST API service for a hotel availability system using the Symfony framework.

The command `composer fixtures:load` will add fake data to the database. ~~It will generate bookings between tomorrow and 2 months in the future. It will generate 3 hotels with 7 rooms each and 30 bookings per room.~~
To be able to test, it will generate bookings for all rooms for 2023-12-01 until 2023-12-10, so that no rooms are available during those dates.

To startup the application use `docker compose up`

## API Routes

`/api/v1/hotel/availability`

Which accepts the following JSON / form data

```
{
    "hotel_id": 1,
    "check_in": "2023-12-10", //Date in the format YYYY-MM-DD
    "check_out": "2023-12-15", //Date in the format YYYY-MM-DD
}
```

## Tests
Phpunit, phpstan & behat tests can be ran on this project.
```
composer phpunit
composer phpstan
composer behat
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
- behat/behat
- friends-of-behat/symfony-extension

## Scripts

- `composer make:entity` | calls Doctrine make:entity command
- `composer make:migration` | calls Doctrine make:migration command
- `composer make:controller` | calls Doctrine make:controller command
- `composer migrate` | calls Doctrine doctrine:migrations:migrate command
- `composer cache:clear` | calls Doctrine cache:clear command
- `composer fixtures:load` | calls Doctrine doctrine:fixtures:load command
- `composer phpunit` | starts the phpunit tests
- `composer phpstan` | starts the phpstan analysis

