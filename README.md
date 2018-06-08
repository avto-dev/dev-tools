<p align="center">
  <img alt="PHP logo" src="https://hsto.org/webt/0v/qb/0p/0vqb0pp6ntyyd8mbdkkj0wsllwo.png" width="70" height="70" />
</p>

# Инструменты для PHP-разработчиков

[![Version][badge_version]][link_packagist]
[![Build Status][badge_build_status]][link_build_status]
[![StyleCI][badge_styleci]][link_styleci]
[![Coverage][badge_coverage]][link_coverage]
[![Code Quality][badge_quality]][link_coverage]
[![Issues][badge_issues]][link_issues]
[![License][badge_license]][link_license]
[![Downloads count][badge_downloads_count]][link_packagist]

## Установка

Для установки данного пакета выполните в терминале следующую команду:

```shell
$ composer require --dev avto-dev/dev-tools "^1.0"
```

> Для этого необходим установленный `composer`. Для его установки перейдите по [данной ссылке][getcomposer].

> Обратите внимание на то, что необходимо фиксировать мажорную версию устанавливаемого пакета.

## Использование

Данный пакет позволяет легко интегрировать в ваше приложение вспомогательные инструменты, позволяющие более эффективно вести разработку. Разделить их можно на следующие группы:

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
class MyBootstrap extends AvtoDev\DevTools\Tests\Bootstrap\AbstractLaravelTestsBootstrapper
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

-----

### Тестирование

Для тестирования данного пакета используется фреймворк `phpunit`. Для запуска тестов выполните в терминале:

```shell
$ git clone git@github.com:avto-dev/dev-tools.git ./dev-tools && cd $_
$ composer install
$ composer test
```

## Поддержка и развитие

Если у вас возникли какие-либо проблемы по работе с данным пакетом, пожалуйста, создайте соответствующий `issue` в данном репозитории.

Если вы способны самостоятельно реализовать тот функционал, что вам необходим - создайте PR с соответствующими изменениями. Крайне желательно сопровождать PR соответствующими тестами, фиксирующими работу ваших изменений. После проверки и принятия изменений будет опубликована новая минорная версия.

## Лицензирование

Код данного пакета распространяется под лицензией [MIT][link_license].

[badge_version]:https://img.shields.io/packagist/v/avto-dev/dev-tools.svg?style=flat&maxAge=30
[badge_downloads_count]:https://img.shields.io/packagist/dt/avto-dev/dev-tools.svg?style=flat&maxAge=30
[badge_license]:https://img.shields.io/packagist/l/avto-dev/dev-tools.svg?style=flat&maxAge=30
[badge_build_status]:https://scrutinizer-ci.com/g/avto-dev/dev-tools/badges/build.png?b=master
[badge_styleci]:https://styleci.io/repos/133380335/shield
[badge_coverage]:https://scrutinizer-ci.com/g/avto-dev/dev-tools/badges/coverage.png?b=master
[badge_quality]:https://scrutinizer-ci.com/g/avto-dev/dev-tools/badges/quality-score.png?b=master
[badge_issues]:https://img.shields.io/github/issues/avto-dev/dev-tools.svg?style=flat&maxAge=30
[link_packagist]:https://packagist.org/packages/avto-dev/dev-tools
[link_styleci]:https://styleci.io/repos/133380335/
[link_license]:https://github.com/avto-dev/dev-tools/blob/master/LICENSE
[link_build_status]:https://scrutinizer-ci.com/g/avto-dev/dev-tools/build-status/master
[link_coverage]:https://scrutinizer-ci.com/g/avto-dev/dev-tools/?branch=master
[link_issues]:https://github.com/avto-dev/dev-tools/issues
[getcomposer]:https://getcomposer.org/download/
