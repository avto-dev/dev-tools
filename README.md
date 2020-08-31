<p align="center">
  <img alt="PHP logo" src="https://hsto.org/webt/0v/qb/0p/0vqb0pp6ntyyd8mbdkkj0wsllwo.png" width="70" height="70" />
</p>

# PHP developers tools

[![Version][badge_packagist_version]][link_packagist]
[![PHP Version][badge_php_version]][link_packagist]
[![Build Status][badge_build_status]][link_build_status]
[![Coverage][badge_coverage]][link_coverage]
[![Downloads count][badge_downloads_count]][link_packagist]
[![License][badge_license]][link_license]

## Install

Require this package with composer using the following command:

```bash
$ composer require --dev avto-dev/dev-tools "^2.0"
```

> Installed `composer` is required ([how to install composer][getcomposer]).

> You need to fix the major version of package.

## Использование

Данный пакет позволяет легко интегрировать в ваше приложение вспомогательные инструменты, позволяющие более эффективно вести разработку. Разделить их можно на следующие группы:

## Вспомогательные сервисы для Laravel

Для Laravel-приложений вы можете подключать следующие сервис-провайдеры:

Сервис-провайдер | Его назначение
---------------- | --------------
[DatabaseQueriesLogger](./src/Laravel/DatabaseQueriesLogger/ServiceProvider.php) | Производит запись всех обращений к базе данных в лог-файл приложения

## Unit-тестирование приложения

> В контексте фреймворка `PHPUnit`

### Bootstrap

Bootstrap - это файл, который выполняется **перед запуском всех тестов, единожды**. Основная его задача - произвести подготовку среды - создать директории для временных файлов (удалив предыдущие), подготовить схему БД и так далее.

Вы можете указать путь до своего собственного bootstrap-файла в конфигурации `PHPUnit` (`./phpunit.xml`):

```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit bootstrap="./tests/bootstrap.php">
    <!-- configuration -->
</phpunit>
```

#### Для Laravel-приложений

Написание кода по рекурсивному созданию директорий, соединению с БД может показаться вам довольно утомительным. Для того, чтобы упростить данную задачу вы можете создать свой класс `bootsrapper`-а, который умеет **поочередное** выполнение всех методов внутри себя, начинающихся с префикса `boot*` при создании собственного экземпляра. Более того - `$this->app` уже хранит инстанс вашего приложения (достаточно подключить нужный трейт). Взгляните на пример:

```php
<?php

class MyBootstrap extends \AvtoDev\DevTools\Tests\Bootstrap\AbstractLaravelTestsBootstrapper
{
    use AvtoDev\DevTools\Tests\PHPUnit\Traits\CreatesApplicationTrait;

    public function bootPrepareDatabase()
    {
        $this->app->make(Illuminate\Contracts\Console\Kernel::class)->call('migrate:refresh');
    }
}
```

И вы всегда имеете "свежую" схему БД перед запуском тестов.

#### Прочие приложения

Более простая реализация:

```php
<?php

class MyBootstrap extends \AvtoDev\DevTools\Tests\Bootstrap\AbstractTestsBootstrapper
{
    public function bootMakeSome()
    {
        // Put your code here
    }

    public function bootMakeSomethingElse()
    {
        // Put your code here
    }
}
```

> В обоих случаях не забудьте создать файл `./tests/bootstrap.php`, написав в нём `<?php new MyBootstrap();`, указав `<phpunit bootstrap="./tests/bootstrap.php">` в конфигурации `PHPUnit`.

### Абстрактные классы Unit-тестов

Для более удобного тестирования вашего приложения вы можете использовать абстрактные классы Unit-тестов, поставляемых с данным пакетом (имеются версии как для Laravel-приложений, так и без данной зависимости).

Их отличительная особенность заключается в том, что они содержат дополнительные `assert`-методы, которые позволят вам писать писать более лаконичные и выразительные тесты. Данный функционал подключается с помощью трейтов, так что вы сможете при необходимости составить свой собственный абстрактный класс (не наследованный от поставляемого) с необходимым набором "фишек".

Рекомендация использовать абстрактные классы обусловлена тем, что их функционал с течением времени будет расширяться, и вы сможете получать их новые возможности при помощи одной лишь команды `composer update avto-dev/dev-tools`.

#### Для Laravel-приложений

Наследуйте классы своих тестов от `AvtoDev\DevTools\Tests\PHPUnit\AbstractLaravelTestCase`.

#### Прочие приложения

Наследуйте классы своих тестов от `AvtoDev\DevTools\Tests\PHPUnit\AbstractTestCase`.

### PHPUnit-трейты

Ниже в виде сводной таблицы представлены имена трейтов и поставляемый с ними функционал. Все трейты расположены в `AvtoDev\DevTools\Tests\PHPUnit\Traits`:

Имя трейта | Функционал
---------- | ----------
`AdditionalAssertionsTrait` | Дополнительные assert-методы
`CreatesApplicationTrait` | Метод, создающий инстанс Laravel-приложения. При его использовании появляется возможность использовать методы `beforeApplicationBootstrapped` и `afterApplicationBootstrapped`
`InstancesAccessorsTrait` | Методы доступа к protected\private методам\свойствам у классов (с помощью рефлексии)
`LaravelEventsAssertionsTrait` | Методы тестирования событий (events) и их слушателей (listeners)
`LaravelLogFilesAssertsTrait` | Методы тестирования лог-файлов Laravel приложения
`LaravelCommandsAssertionsTrait` | Методы тестирования Laravel artisan комманд
`WithDatabaseQueriesLogging` | Подключая данный трейт в класс теста - все запросы к БД будут записываться в log-файл (класс теста должен наследоваться при этом от `AbstractLaravelTestCase`)
`CarbonAssertionsTrait` | Методы для тестирования `Carbon`-объектов
`WithDatabaseDisconnects` | Подключая данный трейт в класс теста - на `tearDown` происходит отключение от всех БД ([причина](https://www.neontsunami.com/posts/too-many-connections-using-phpunit-for-testing-laravel-51))
`WithMemoryClean` | Подключая данный трейт в класс теста - на `tearDown` происходит очистка свойств класса. Для использования этого трейта вне `Laravel-framework` необходимо вызывать метод `clearMemory` на `tearDown` интересующего класса
`WithGuzzleMocking` | Трейт, поставляющий метод создания хэндлера-обработчика запросов HTTP клиента Guzzle 6
`AppVersionAssertionsTrait` | Методы для проверки версии приложения и крайней версии, указанной в файле `CHANGELOG.md`
`LaravelRoutesAssertsTrait` | Методы для проверки правильной настройки роутов Laravel

-----

### Testing

For package testing we use `phpunit` framework and `docker-ce` + `docker-compose` as develop environment. So, just write into your terminal after repository cloning:

```bash
$ make build
$ make latest # or 'make lowest'
$ make test
```

## Changes log

[![Release date][badge_release_date]][link_releases]
[![Commits since latest release][badge_commits_since_release]][link_commits]

Changes log can be [found here][link_changes_log].

## Support

[![Issues][badge_issues]][link_issues]
[![Issues][badge_pulls]][link_pulls]

If you will find any package errors, please, [make an issue][link_create_issue] in current repository.

## License

This is open-sourced software licensed under the [MIT License][link_license].

[badge_packagist_version]:https://img.shields.io/packagist/v/avto-dev/dev-tools.svg?maxAge=180
[badge_php_version]:https://img.shields.io/packagist/php-v/avto-dev/dev-tools.svg?longCache=true
[badge_build_status]:https://img.shields.io/github/workflow/status/avto-dev/dev-tools/tests/master
[badge_coverage]:https://img.shields.io/codecov/c/github/avto-dev/dev-tools/master.svg?maxAge=60
[badge_downloads_count]:https://img.shields.io/packagist/dt/avto-dev/dev-tools.svg?maxAge=180
[badge_license]:https://img.shields.io/packagist/l/avto-dev/dev-tools.svg?longCache=true
[badge_release_date]:https://img.shields.io/github/release-date/avto-dev/dev-tools.svg?style=flat-square&maxAge=180
[badge_commits_since_release]:https://img.shields.io/github/commits-since/avto-dev/dev-tools/latest.svg?style=flat-square&maxAge=180
[badge_issues]:https://img.shields.io/github/issues/avto-dev/dev-tools.svg?style=flat-square&maxAge=180
[badge_pulls]:https://img.shields.io/github/issues-pr/avto-dev/dev-tools.svg?style=flat-square&maxAge=180
[link_releases]:https://github.com/avto-dev/dev-tools/releases
[link_packagist]:https://packagist.org/packages/avto-dev/dev-tools
[link_build_status]:https://github.com/avto-dev/dev-tools/actions
[link_coverage]:https://codecov.io/gh/avto-dev/dev-tools/
[link_changes_log]:https://github.com/avto-dev/dev-tools/blob/master/CHANGELOG.md
[link_issues]:https://github.com/avto-dev/dev-tools/issues
[link_create_issue]:https://github.com/avto-dev/dev-tools/issues/new/choose
[link_commits]:https://github.com/avto-dev/dev-tools/commits
[link_pulls]:https://github.com/avto-dev/dev-tools/pulls
[link_license]:https://github.com/avto-dev/dev-tools/blob/master/LICENSE
[getcomposer]:https://getcomposer.org/download/
[roadrunner]:https://github.com/spiral/roadrunner
