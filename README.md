Currency converter

## Installation

For a full environment the following dependencies should be installed:

* [PHP](https://www.php.net) 8.2
* [Symfony](https://symfony.com/download) 5.4
* [Composer](https://getcomposer.org) 2.6
* [Xdebug](https://xdebug.org) 3.3

## Setup

Setup environment settings file
```shell
# create local .env file
touch .env.local

# add your database credentials to the .env.local file:
# DATABASE_URL="mysql://username:password@127.0.0.1:3306/databasename?charset=utf
```

## How to run

Before running the code, the PHP dependencies must be installed by running:

```shell
# Install vendors
composer install

# Install node modules
npm install

# Compile assets

npm run dev

# Setup 
symfony console doctrine:migrations:migrate
```

Next add some startup database values:
```shell
# The site is protected with an IP whitelist
# To add your ip to the whitelist:
symfony console whitelist:add 127.0.0.1

# Import some exchangerates
symfony console exchangerates:update EUR
symfony console exchangerates:update USD
```

## Database

Setup the database by running:

```shell
symfony console doctrine:migrations:migrate
```

## Frontend

Handling CSS & JavaScript is done by Webpack Encore

```shell
# compile assets and automatically re-compile when files change
npm run watch

# compile assets once
npm run dev

# on deploy, create a production build
npm run build
```

## Whitelist

The website can only be viewed if the IP address of the visitor is whitelisted.
Manage the whitelist IP address using the following commands:

```shell
# Show a list op IP addresses in the whitelist
symfony console whitelist:list

# Add IP address to the whitelist
symfony console whitelist:add 127.0.0.1

# Delete IP address from whitelist by record id
symfony console whitelist:delete 127.0.0.1
```

## Import

To import/update the exchange rates use the following commands:

```shell
# Import for a single currency
symfony console exchangerates:update EUR

# Import for all currecies.
symfony console exchangerates:update --all
```

## Tests

Test-driven-development using unit tests.
Tests can be added in `tests/Unit/*` and run using [PHPUnit](http://phpunit.de).
The flag `--testdox` can help make the output more legible:

```shell
vendor/bin/phpunit tests/Unit --testdox
```

## Linting & Static Analysis

Following code standards can help make the code more legible and running static analysis tools can spot issues in the code. 
This project comes with PHP [CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) and [Psalm](https://psalm.dev):

```shell
vendor/bin/phpcs -p --standard=PSR12 src/ tests/
vendor/bin/psalm --show-info=true
```

These can be run quickly from the Makefile:

```shell
make lint
```