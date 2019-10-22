# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog][keepachangelog] and this project adheres to [Semantic Versioning][semver].

## v2.1.0

### Changed

- Maximal `illuminate/*` packages version now is `6.*`

### Added

- GitHub actions for a tests running

## v2.0.0

### Added

- Docker-based environment for development
- Project `Makefile`
- Dependency `avto-dev/stacked-dumper-laravel`

### Changed

- All "custom" asserts now non-static ([reason](https://phpunit.de/manual/6.5/en/appendixes.assertions.html#appendixes.assertions.static-vs-non-static-usage-of-assertion-methods))
- Assert methods now returns `void`
- Some asserts now accepts "message" parameter
- Minimal PHP version now is `7.1.3`
- Minimal PHPUnit version now is `^7.5`
- Minimal Laravel version now is `5.6.x`
- Maximal Laravel version now is `5.8.x`
- Composer scripts

### Removed

- `AvtoDev\DevTools\Laravel\VarDumper\*` (replaced with `avto-dev/stacked-dumper-laravel`)
- `assertIsNumeric`
- `assertIsInteger`
- `assertIsArray`
- `assertIsString`

### Deprecated

- `InstancesAccessorsTrait::callMethod`
- `InstancesAccessorsTrait::getProperty`

## v1.11.0

### Added

- `assertJsonStructure` method in `AdditionalAssertionsTrait`

## v1.10.0

### Added

- Trait `WithGuzzleMocking`

## v1.9.2

### Fixed

- `LaravelLogFilesAssertsTrait::clearLaravelLogs()` now **not** delete hidden files (like `.gitignore` and others)

## v1.9.1

### Fixed

- RoadRunner detection method in function `\dev\ran_using_cli()`

## v1.9.0

### Added

- Service `Laravel\VarDumper` (with middleware and stack instance)
- Global helpers file (namespace `\dev\...`)
- Function `\dev\dump(...$arguments)`
- Function `\dev\dd(...$arguments)`

## v1.8.0

### Added

- Added `LaravelRoutesAssertsTrait` that include `assertAllRoutesHasActions` method [#23]

[#23]:ttps://github.com/avto-dev/dev-tools/issues/23

## v1.7.2

### Changed

- Dependency `phpunit` moved to regular dependencies (not `dev`)

## v1.7.1

### Changed

- Maximal `phpunit` version now is `7.4.x`. Reason - since `7.5.0` frameworks contains assertions like `assertIsNumeric`, `assertIsArray` and others, already declared in current package

## v1.7.0

### Changed

- Maximal PHP version now is undefined
- CI changed to [Travis CI][travis]
- [CodeCov][codecov] integrated

[travis]:https://travis-ci.org/
[codecov]:https://codecov.io/

### Fixed

- `LaravelLogFilesAssertsTrait` for a working with Laravel `5.7.*` (if used `stack` logging driver with single channel `daily` - `laravel.log` file name automatically replaces with `laravel-%Y-m-d%.log`) - method `getDefaultLogsDirectoryPath` patched

## v1.6.1

### Fixed

- Method `getVersionRegexp` in trait `AppVersionAssertionsTrait`

## v1.6.0

### Added

- Trait `AppVersionAssertionsTrait`

## v1.5.0

### Added

- `assertArrayStructure` method in `AdditionasAssertionsTrait` [#7]
- Trait `WithMemoryClean`

## v1.4.0

### Added

- **Laravel**: Trait `WithDatabaseDisconnects`
- Method `getClosureHash()` in trait `InstancesAccessorsTrait` (requires `jeremeamia/superclosure` package installed; also it requires package `nikic/php-parser` version `2.0` and above)

### Changed

- Class `AbstractLaravelTestCase` now supports `WithDatabaseDisconnects` trait in test classes

## v1.3.0

### Added

- Trait `CarbonAssertionsTrait`

### Changed

- Classes `AbstractLaravelTestCase` and `AbstractTestCase` uses `CarbonAssertionsTrait` trait by default
- Unimportant PHPDoc blocks removed

## v1.2.0

### Added

- **Laravel**: Event listener for a logging database queries
- **Laravel**: Service provider that register listener for a logging database queries
- **Laravel**: Trait `WithDatabaseQueriesLogging` (use this trait in your tests classes for enabling all database queries logging)

### Changed

- Class `AbstractLaravelTestCase` now supports `WithDatabaseQueriesLogging` trait in test classes

## v1.1.7

### Added

- Trait `LaravelCommandsAssertionsTrait`

### Changed

- Trait `LaravelCommandsAssertionsTrait` enabled by default in `AbstractLaravelTestCase`

## v1.1.6

### Fixed

- Trait mixins

## v1.1.5

### Fixed

- Some PHPDoc annotations and asserts messages fixed

## v1.1.4

### Added

- Additional method `getApplicationBootstrapFiles()` into `CreatesApplicationTrait`
- Trait `LaravelLogFilesAssertsTrait`
- Minimal PHPunit version now is `6.5` (for internal testing)

## v1.1.3

### Fixed

- Fixed asserts `assertEmptyString`, `assertNotEmptyString`, `assertClassExists` - now works correctly with arrays of input values

## v1.1.2

### Fixed

- Fixed application bootstrap file path for using in packages in `CreatesApplicationTrait`

## v1.1.1

### Fixed

- Fixed exceptions handler in `AbstractTestsBootstrapper`

## v1.1.0

### Added

- Assert method `assertIsInteger`

## v1.0.0

- First release

[#7]:https://github.com/avto-dev/dev-tools/issues/7
[keepachangelog]:https://keepachangelog.com/en/1.0.0/
[semver]:https://semver.org/spec/v2.0.0.html
