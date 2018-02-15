# TestApiSugarCRM
Construint un Objecte per fer les crides API a sinergiaCRM, necessaries per treballar.

Creant un entorn de testing.

## Requeriments

* composer: https://getcomposer.org/


## Install

```php
composer install
```

## Configure

Copiar el fitxer de config-example.php a config.php i posar les dades necessaries.

```php
cp src/config-example.php src/config.php
vi src/config.php
```

## Execute test

```php
./vendor/phpunit/phpunit/phpunit test
```
