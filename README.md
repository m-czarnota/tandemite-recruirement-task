# Tandemite - recruirement task

## How to start?
1) `docker compose up -d`
2) in php container run below commands:
* `composer dsu`
* `php bin/console lexik:jwt:generate-keypair --overwrite`
* `php bin/console tandemite:user:create_first`
3) open `localhost:80` in browser
4) when you go to list of clients you will have to sign in. credentials are defined in `php/.env`
* login: user@default.com
* password: abc123

## TODO
* add OpenAPI
* add pagination component to list of clients on front-end
* add writing to and reading from cache for list of client on back-end

## About my approach
For the back-end, I decided on an approach similar to the hexagonal architecture. The application is based on the Symfony framework due to the convenience of request handling, dependency injection and ORM delivered as Doctrine. The code is largely based on language-dependent code to minimize the hassle when upgrading Symfony. Each action has been separated in Application folders into separate directories to be able to easily manage each individual action and isolate separate elements typical for each, such as data mappings and request validation. The application has unit and behat tests.
