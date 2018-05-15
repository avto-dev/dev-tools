<p align="center">
  <img alt="PHP logo" src="https://hsto.org/webt/0v/qb/0p/0vqb0pp6ntyyd8mbdkkj0wsllwo.png" width="70" height="70" />
</p>

# Вспомогательные инструменты для разработчиков

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
