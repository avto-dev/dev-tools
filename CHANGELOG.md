# Changelog

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

### First release


[#7]:https://github.com/avto-dev/dev-tools/issues/7
