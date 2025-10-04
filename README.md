# Playground: Directory Resource

[![Playground CI Workflow](https://github.com/gammamatrix/playground-directory-resource/actions/workflows/ci.yml/badge.svg?branch=develop)](https://raw.githubusercontent.com/gammamatrix/playground-directory-resource/testing/develop/testdox.txt)
[![Test Coverage](https://raw.githubusercontent.com/gammamatrix/playground-directory-resource/testing/develop/coverage.svg)](tests)
[![PHPStan Level 10](https://img.shields.io/badge/PHPStan-level%2010-brightgreen)](.github/workflows/ci.yml#L128)

Playground: Directory Resource

This package provides an API and a Blade UI for interacting with the [Playground: Directory](https://github.com/gammamatrix/playground-directory), a model package for Laravel.

If you need a JSON API without a UI, then have a look at [Playground: Directory API.](https://github.com/gammamatrix/playground-directory-api)

## Documentation

Read more on using [Playground: Directory Resource at Read the Docs: Playground Documentation](https://gammamatrix-playground.readthedocs.io/en/develop/built-components/directory.html)

### Postman

A postman collection is provided in the repository: [postman-playground-directory-resource.json.](postman-playground-directory-resource.json)
- This same collection is viewable on the [.]()

### OpenAPI

This application provides OpenAPI documentation: [openapi.yaml](openapi.yaml).
- The endpoint models support locks, trash with force delete, restoring, revisions and more.
- Index endpoints support advanced query filtering.

OpenAPI API Documentation is built with npm using Redocly.
- npm is only needed to generate documentation and is not needed to operate the Playground: Directory Resource API.

See [package.json](package.json) requirements.

Install npm.

```sh
npm install
```

Build the documentation to generate the [openapi.yaml](openapi.yaml) configuration.

```sh
npm run docs
```

Documentation
- Preview [openapi.yaml on the Redocly Editor UI.](https://redocly.github.io/redoc/?url=https://raw.githubusercontent.com/gammamatrix/playground-directory-resource/develop/openapi.yaml)

## Installation

You can install the package via composer:

```bash
composer require gammamatrix/playground-directory-resource
```

## `artisan about`

Playground provides information in the `artisan about` command.

<!-- <img src="resources/docs/artisan-about-playground-directory-resource.png" alt="screenshot of artisan about command with Playground: Directory Resource."> -->

## Configuration

You can publish the config file with:

```bash
php artisan vendor:publish --provider="Playground\Directory\Resource\ServiceProvider" --tag="playground-config"
```

All routes are enabled by default. They may be disabled via environment variable or the configuration.

See the contents of the published config file: [config/playground-directory-resource.php](config/playground-directory-resource.php)

You can publish the routes file with:
```bash
php artisan vendor:publish --provider="Playground\Directory\Resource\ServiceProvider" --tag="playground-routes"
```
- The routes while be published in a folder at `routes/playground-directory-resource`

### Environment Variables

If you are unable or do not want to publish [configuration files for this package](config/playground-directory-resource.php),
you may override the options via system environment variables.

Information on [environment variables is available on the wiki for this package](https://github.com/gammamatrix/playground-directory-resource/wiki/Environment-Variables)

## Migrations

This package requires the migrations in [playground-directory](https://github.com/gammamatrix/playground-directory) a Laravel package.

## Cloc

```sh
composer cloc
```

```
➜  playground-directory-resource git:(develop) ✗ composer cloc
     239 text files.
     230 unique files.
      94 files ignored.

github.com/AlDanial/cloc v 2.06  T=0.08 s (3008.8 files/s, 414287.9 lines/s)
-------------------------------------------------------------------------------
Language                     files          blank        comment           code
-------------------------------------------------------------------------------
JSON                            83              0              0          15175
YAML                            30              5              0           6422
PHP                             83            999           1360           4089
Blade                           18             91              0           2446
XML                             12              0              7            863
Markdown                         3             58              1            138
INI                              1              3              0             12
-------------------------------------------------------------------------------
SUM:                           230           1156           1368          29145
-------------------------------------------------------------------------------
```

## PHPStan

Tests at level 10 on:
- `config/`
- `lang/`
- `resources/views/`
- `routes/`
- `src/`
- `tests/Feature/`
- `tests/Unit/`

```sh
composer analyse
```

## Coding Standards

Format source code:
```sh
composer format
```

Format blades in resources/views:

```sh
composer format-blade
```
- **NOTE:** requires installing dev packages from package.json.

```sh
npm install
```

## Testing

Run unit tests:
```sh
composer test
```

Run unit and feature tests:
```sh
composer test-dev
```

Run unit and feature tests in parallel:
```sh
composer test-parallel
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
