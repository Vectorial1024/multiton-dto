# multiton-dto
[![Packagist License][packagist-license-image]][packagist-url]
[![Packagist Version][packagist-version-image]][packagist-url]
[![Packagist Downloads][packagist-downloads-image]][packagist-stats-url]
[![PHP Dependency Version][php-version-image]][packagist-url]
[![GitHub Actions Workflow Status][php-build-status-image]][github-actions-url]
[![GitHub Repo Stars][github-stars-image]][github-repo-url]

Ergonomic PHP library for optimizing data transfer objects (DTOs).

## Situation / Why should I need this?
Data transfer objects (DTOs) in PHP are very minimal classes that has the only objective of representing a piece of data for easy data transfer.
In modern PHP, they can be classes with only `readonly` properties.

DTOs are great, but when DTOs need to be (re)created many times in different places of the workflow, problems may arise. Consider:

```php
// create instance for usage...
$dto = new CustomDto(1);

// ...
// somewhere else unrelated
$anotherDto = new CustomDto(1);

// ...
```

Here, `$dto` and `$anotherDto` are two different object instances; `$dto == $anotherDto` but `$dto !== $anotherDto`. This means:
- Unnecessarily high overall memory usage for such simple DTOs if they are to be duplicated many times
  - An example could be the result dataset of a database JOIN query
- Impossible to use with e.g. `WeakMap`, which relies on the specific object instances

The solution is simple: because DTOs very likely are `readonly` classes anyway, their object instances can be shared.
This minimizes memory usage, along with e.g. enabling easy integration with the `WeakMap` datatype.

This library allows you to ergonomically manage duplicated DTOs by sharing their instances.
You can then analyze your use case and apply this library where applicable.

## Installation
via Composer:

```sh
composer require vectorial1024/multiton-dto
```

## Usage
(WIP)

## Testing
(WIP)

[packagist-url]: https://packagist.org/packages/vectorial1024/multiton-dto
[packagist-stats-url]: https://packagist.org/packages/vectorial1024/multiton-dto/stats
[github-repo-url]: https://github.com/Vectorial1024/multiton-dto
[github-actions-url]: https://github.com/Vectorial1024/multiton-dto/actions/workflows/php.yml

[packagist-license-image]: https://img.shields.io/packagist/l/vectorial1024/multiton-dto?style=plastic
[packagist-version-image]: https://img.shields.io/packagist/v/vectorial1024/multiton-dto?style=plastic
[packagist-downloads-image]: https://img.shields.io/packagist/dm/vectorial1024/multiton-dto?style=plastic
[php-version-image]: https://img.shields.io/packagist/dependency-v/vectorial1024/multiton-dto/php?style=plastic&label=PHP
[php-build-status-image]: https://img.shields.io/github/actions/workflow/status/Vectorial1024/multiton-dto/php.yml?style=plastic
[github-stars-image]: https://img.shields.io/github/stars/vectorial1024/multiton-dto
