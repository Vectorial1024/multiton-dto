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

DTOs are great, but when `readonly` DTOs need to be (re)created many times, problems may arise. Consider:

```php
// create instance for usage...
$dto = new ReadOnlyDto(1);

// ...
// somewhere else unrelated
$anotherDto = new ReadOnlyDto(1);

// ...
```

Here, `$dto` and `$anotherDto` are two different object instances; `$dto == $anotherDto` but `$dto !== $anotherDto`. This means:
- Unnecessarily high overall memory usage for such `readonly` DTOs, esp. when needing to duplicate them many times
  - An example could be the result dataset of a database JOIN query
- Impossible to use with e.g. `WeakMap`, which relies on the specific object instances

With this ergonomic library, same `readonly` DTO instances can be conveniently deduplicated, so that e.g. memory usage may be minimized.
This is an example of the multiton pattern where multiple DTO instances are allowed to exist only if their identities are distinct.

Note that this library is flexible: it will only activate when explicitly requested by the user.
In case the DTOs will never duplicate (e.g. RESTful API returning a single instance to the caller),
simply don't invoke this library and this library will get out of the way.

## Installation
via Composer:

```sh
composer require vectorial1024/multiton-dto
```

## Usage
First, make your DTO use the special trait from this library:

```php
use Vectorial1024\MultitonDto\MultitonDto;

public class ReadOnlyDto
{
    // sample DTO class

    use MultitonDtoTrait;

    public function __construct(
        public readonly int $theValue
    ) {
    }

    protected function provideDtoID(): string
    {
        // abstract function from trait; let this library know how to identify your DTO instances
        return (string) $this->theValue;
    }
}
```

Then, you have the convenient option to convert any created instance to the shared multiton DTO instance:

```php
// convert to shared DTO...
$sharedInstance = (new ReadOnlyDto(1))->toMultiton();

// ...or not at all; it's up to you. just double check your use case.
$unsharedInstance = new ReadOnlyDto(3);

// just that, when using this library, a useful behavior arises:
$secondInstance = (new ReadOnlyDto(2))->toMultiton();
assert($secondInstance === $sharedInstance);
// passes
assert($secondInstance !== $unsharedInstance);
// passes
```

These shared instances are remembered via `WeakReference` variables, which unfortunately will still occupy a very small amount of memory even when everything is gone.

For performance reasons, leftover DTO references are not automatically cleaned up. However, you may do this at an appropriate time during your program flow:

```php
// tell this library to remove only the leftover expired DTO records...
ReadOnlyDto::cleanMultitons();

// ...or go nuclear and unlink everything...
ReadOnlyDto::resetMultitons();

// ...or perhaps this cleanup is not needed; it's up to you.
```

## Notes on DTO Inheritance
Due to PHP technical limitations, if the parent class has chosen to use `MultitonDtoTrait`, then:
- child classes cannot opt out of the trait; and
- child classes must share their DTO instances with the parent class, and vice versa

As such, when reading DTO instances inside child classes, sometimes explicit casts are needed.

## Testing
PHPUnit via Composer:

```sh
composer run-script test
```

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
