# Affiliates app

- [Affiliates app](#affiliates-app)
  - [Preface](#preface)
  - [Installation](#installation)
    - [Default way](#default-way)
    - [Linux with Docker](#linux-with-docker)
  - [Testing the application](#testing-the-application)
    - [Using browser](#using-browser)
    - [Using `CLI` command](#using-cli-command)
    - [Running `Unit Tests`](#running-unit-tests)
  - [Documentation](#documentation)
  - [Available `make` commands list](#available-make-commands-list)
  - [Touched files](#touched-files)
    
## Preface

The main purposes of the application are:

- Run a CLI command to identify affiliates within 100 Km from Dublin
- UI to upload parse and filter affiliates from file -- one affiliate per line, JSON-encoded;
- Calculate the spatial distance of each affiliate;
- Filter affiliates by range (default 100 km);

The application is making use the following `Laravel` features

- [Artisan command](https://laravel.com/docs/8.x/artisan#writing-commands) to import parse and filter affiliates from CLI
- [Model factories](https://laravel.com/docs/8.x/database-testing#defining-model-factories) to seed database during unit tests;
- [Unit tests](https://laravel.com/docs/8.x/testing) to test core functionality and test http requests;
- [Jetstream](https://jetstream.laravel.com/) a starter kit for SPA.

## Installation

First clone the repository to your local environment:

`git clone https://github.com/tonyputi/affiliates.git`

### Default way

Requirements: (PHP 7.4, Composer)

Within your cloned project folder execute the following commands:

1. Type `cd src`
2. Type `cp .env.example .env`
3. TYpe `touch database/database.sqlite`
4. Type `composer install`
5. Type `php artisan key:generate`
6. Type `php artisan migrate --seed`
7. Type `php artisan serve`

Remember to refer to your project as http://localhost:8000

### Linux with Docker

Requirements: (Docker, Docker Compose, Make)

1. Type `make build` to build the docker image
2. Type `make start` to start the docker containers and wait few seconds to let containers proper start
3. Type `make init` to initialize the application container (only the first time is required)

The `make init` command will lunch `composer install` and `php artisan migrate refresh --seed` for you.

## Testing the application

There a 3 ways to test the application or part of it: by using your browser, by CLI or by Unit Tests

### Using browser

Access the address [http://localhost](http://localhost) or [http://localhost:8000](http://localhost:8000) from your browser in accordin to your installation setup. Than click on the `login` link on the top right of your screen.

- Username: `user@example.com`
- Password: `password`

Navigate to `Affiliates` section in order to upload and process `affiliates.txt` file.

### Using `CLI` command

From the CLI command `affiliate:filter` to filter affiliates within range of 100 Km from Dublin.
  
- `php artisan affiliate:filter public/affiliates.txt`
- `php artisan affiliate:filter --help`

### Running `Unit Tests`

If you are running the application with docker setup type:

- `make test` if you are running to run the unit tests;
- `make testall` to run all the unit tests (laravel default ones included)

If you are running the application without docker setup type:

- `php artisan test --group affiliates` to run only affiliates related tests
- `php artisan test` to run all the unit tests (laravel default ones included)

## Documentation

You can access the code documentation [here](http://localhost/docs/index.html)

## Available `make` commands list

- `make build` build new docker image and clean everything after;
- `make up` start the containers;
- `make down` stop the containers;
- `make restart` restart the containers;
- `make init` initialize the container installing dependencies and running migrations;
- `make shell` start new shell within the app container;  
- `make docs` build code documentation;
- `make test` run unit tests;
- `make testall` run unit test and automatic browser tests;
- `make clean` remove unused docker layers.

## Touched files

```
.
├── docker
│   ├── nginx
│   │   └── default.conf
│   └── php
│       ├── Dockerfile
│       └── supervisor.ini
├── docker-compose.yml
├── Makefile
├── README.md
└── src
    ├── app
    │   ├── Console
    │   │   └── Commands
    │   │       ├── AffiliatesFilter.php
    │   │       └── AffiliatesImport.php
    │   ├── Http
    │   │   └── Controllers
    │   │       └── AffiliateController.php
    │   ├── Models
    │   │   ├── Affiliate.php
    │   │   └── User.php
    │   └── Providers
    │       └── AppServiceProvider.php
    ├── bootstrap
    │   └── helpers.php
    ├── composer.json
    ├── config
    ├── database
    │   ├── database.sqlite
    │   ├── factories
    │   │   └── AffiliateFactory.php
    │   ├── migrations
    │   │   └── 2021_09_14_080633_create_affiliates_table.php
    │   └── seeders
    │       └── DatabaseSeeder.php
    ├── doctum.config.php
    ├── public
    │   └── affiliates.txt
    ├── resources
    │   └── js
    │       ├── Jetstream
    │       │   └── Table.vue
    │       ├── Layouts
    │       │   └── AppLayout.vue
    │       └── Pages
    │           └── Affiliates
    ├── routes
    │   └── web.php
    └── tests
        ├── Feature
        │   └── AffiliatesTest.php
        └── Unit
            └── HelpersTest.php
```