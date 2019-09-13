# Find your service challenge

## Local environment setup

Requirements: Docker.

- Execute in the main root of the project `docker-compose up`
- Then in another terminal you need to execute `docker-compose exec php bash` you will be prompetd to a terminal.
- In the php terminal, you need to execute `composer install`.
- Then execute `php artisan migrate`
- Then execute `php artisan db:seed`

Configure Laravel Passport:

 - Execute `php artisan passport:install` and copy the token from the ID 2.
 - Copy the token into your `.env` file.
 
You should have something like this:

> PASSPORT_CLIENT_ID=2
>
> PASSPORT_CLIENT_SECRET=AABBCCDD

## Testing

 - To execute unit testing run `docker-compose exec php ./vendor/bin/phpunit`
 
## Docs

 - Postman collection and environment is located into `docs/api-docs` 
