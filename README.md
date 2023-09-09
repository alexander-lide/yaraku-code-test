## Yaraku code test
An app where you can add, edit and delete books and authors as well as download a list of all books or authors as a .csv or xml file.

## Requirements
- [Docker](https://docs.docker.com/install)
- [Docker Compose](https://docs.docker.com/compose/install)

## Setup
1. Clone the repository.
1. Copy .env.example file in the root folder to .env and save it
1. Run `composer install` in the root folder
1. Create sqlite file by running `touch database/database.sqlite` in the root folder
1. Run `php artisan migrate` in the root folder to run migrations
1. Run `./vendor/bin/sail up -d` in the root folder
1. App should now be reachable at localhost

## Testing
Run `php artisan test` in the root folder to run tests