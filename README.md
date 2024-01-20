Currency converter

## Installation

For a full environment the following dependencies should be installed:

* [PHP](https://www.php.net) 8.2
* [Composer](https://getcomposer.org) 2.6
* [Xdebug](https://xdebug.org) 3.3

## How to run

Before running the code, the PHP dependencies must be installed by running:

```shell
composer install

```shell
npm install
```

## Tests

Test-driven-development using unit tests.
Tests can be added in `tests/Unit/*` and run using [PHPUnit](http://phpunit.de).
The flags `--testdox` and `--colors` can help make the output more legible:

```shell
vendor/bin/phpunit tests/Unit/Day01Test.php --testdox --colors
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