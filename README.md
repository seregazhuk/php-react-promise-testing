# ReactPHP Promises Testing
A library that provides a set of convenient assertions for testing ReactPHP promises.
Under the hood uses [clue/php-block-react](https://github.com/clue/php-block-react) to block promises.

[![Build Status](https://travis-ci.org/seregazhuk/php-react-promise-testing.svg?branch=master)](https://travis-ci.org/seregazhuk/php-react-promise-testing)
[![Maintainability](https://api.codeclimate.com/v1/badges/689230cdae09d2e32600/maintainability)](https://codeclimate.com/github/seregazhuk/php-react-promise-testing/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/689230cdae09d2e32600/test_coverage)](https://codeclimate.com/github/seregazhuk/php-react-promise-testing/test_coverage)
[![Total Downloads](https://poser.pugx.org/seregazhuk/react-promise-testing/downloads)](//packagist.org/packages/seregazhuk/react-promise-testing)

When testing asynchronous code and promises things can be a bit tricky. This library provides a set of convenient 
assertions for testing ReactPHP promises. 

**Table of Contents**
- [Installation](#installation)
- [Quick Start](#quick-start)
- [Assertions](#assertions)
    - [assertPromiseFulfills()](#assertpromisefulfills)
    - [assertPromiseFulfillsWith()](#assertpromisefulfillswith)
    - [assertPromiseFulfillsWithInstanceOf()](#assertpromisefulfillswithinstanceof)
    - [assertPromiseRejects()](#assertpromiserejects())
    - [assertPromiseRejectsWith()](#assertpromiserejectswith)
    - [assertTrueAboutPromise()](#asserttrueaboutpromise)
    - [assertFalseAboutPromise()](#assertfalseaboutpromise)
    
- [Helpers](#helpers)
    - [waitForPromiseToFulfill()](#waitforpromisetofulfill)
    - [waitForPromise()](#waitforpromise)
    
## Installation

### Dependencies
Library requires PHP 8.0 or above.

The recommended way to install this library is via [Composer](https://getcomposer.org). 
[New to Composer?](https://getcomposer.org/doc/00-intro.md)

See also the [CHANGELOG](CHANGELOG.md) for details about version upgrades.

```
composer require seregazhuk/react-promise-testing --dev
```

## Quick Start
Use the trait `seregazhuk\React\PromiseTesting\AssertsPromise` or extend your
test classes from `seregazhuk\React\PromiseTesting\TestCase` class,
which itself extends PHPUnit `TestCase`.

```php
final class MyTest extends TestCase
{
    /** @test */
    public function promise_fulfills_with_a_response_object()
    {
        $browser = new Clue\React\Buzz\Browser($this->eventLoop());
        $promise = $browser->get('http://www.google.com/');
        $this->assertPromiseFulfillsWithInstanceOf($promise, ResponseInterface::class);
    }
}
```

Using the trait:

```php

use PHPUnit\Framework\TestCase;
use seregazhuk\React\PromiseTesting\AssertsPromise;

final class MyTest extends TestCase
{
    use AssertsPromise;

    /** @test */
    public function promise_fulfills_with_a_response_object()
    {
        $browser = new Clue\React\Buzz\Browser($this->eventLoop());
        $promise = $browser->get('http://www.google.com/');
        $this->assertPromiseFulfillsWithInstanceOf($promise, ResponseInterface::class);
    }
}
```

Test above checks that a specified promise fulfills with an instance of `ResponseInterface`. 

## Event loop

To make promise assertions we need to run the loop. Before each test a new instance of the event loop
is being created (inside `setUp()` method). If you need the loop to build your dependencies you **should**
use `eventLoop()` method to retrieve it.



## Assertions

### assertPromiseFulfills()

`public function assertPromiseFulfills(PromiseInterface $promise, int $timeout = null): void`

The test fails if the `$promise` rejects. 

You can specify `$timeout` in seconds to wait for promise to be resolved.
If the promise was not fulfilled in specified timeout the test fails. When not specified, timeout is set to 2 seconds.

```php
final class PromiseFulfillsTest extends TestCase
{
    /** @test */
    public function promise_fulfills(): void
    {
        $deferred = new Deferred();
        $deferred->reject();
        $this->assertPromiseFulfills($deferred->promise(), 1);
    }
}
```

```bash
PHPUnit 8.5.2 by Sebastian Bergmann and contributors.

F                                                                   1 / 1 (100%)

Time: 189 ms, Memory: 4.00MB

There was 1 failure:

1) seregazhuk\React\PromiseTesting\tests\PromiseFulfillTest::promise_fulfills
Failed asserting that promise fulfills. Promise was rejected.
```

### assertPromiseFulfillsWith()

`assertPromiseFulfillsWith(PromiseInterface $promise, $value, int $timeout = null): void`

The test fails if the `$promise` doesn't fulfills with a specified `$value`.

You can specify `$timeout` in seconds to wait for promise to be fulfilled.
If the promise was not fulfilled in specified timeout the test fails. 
When not specified, timeout is set to 2 seconds.

```php
final class PromiseFulfillsWithTest extends TestCase
{
    /** @test */
    public function promise_fulfills_with_a_specified_value(): void
    {
        $deferred = new Deferred();
        $deferred->resolve(1234);
        $this->assertPromiseFulfillsWith($deferred->promise(), 1);
    }
}
```

```bash
PHPUnit 8.5.2 by Sebastian Bergmann and contributors.

F                                                                   1 / 1 (100%)

Time: 180 ms, Memory: 4.00MB

There was 1 failure:

1) seregazhuk\React\PromiseTesting\tests\PromiseFulfillsWithTest::promise_fulfills_with_a_specified_value
Failed asserting that promise fulfills with a specified value. 
Failed asserting that 1234 matches expected 1.
```

### assertPromiseFulfillsWithInstanceOf()

`assertPromiseFulfillsWithInstanceOf(PromiseInterface $promise, string $class, int $timeout = null): void`

The test fails if the `$promise` doesn't fulfills with an instance of specified `$class`.

You can specify `$timeout` in seconds to wait for promise to be fulfilled.
If the promise was not fulfilled in specified timeout the test fails. 
When not specified, timeout is set to 2 seconds.

```php
final class PromiseFulfillsWithInstanceOfTest extends TestCase
{
    /** @test */
    public function promise_fulfills_with_an_instance_of_class(): void
    {
        $deferred = new Deferred();
        $deferred->resolve(new MyClass);
        $this->assertPromiseFulfillsWithInstanceOf($deferred->promise(), MyClass::class);
    }
}
```

```bash
PHPUnit 8.5.2 by Sebastian Bergmann and contributors.

F                                                                   1 / 1 (100%)

Time: 180 ms, Memory: 4.00MB

There was 1 failure:

1) seregazhuk\React\PromiseTesting\tests\PromiseFulfillsWithWithInstanceOfTest::promise_fulfills_with_an_instance_of_class
Failed asserting that promise fulfills with a value of class MyClass. 
```

### assertPromiseRejects()
`assertPromiseRejects(PromiseInterface $promise, int $timeout = null): void`

The test fails if the `$promise` fulfills.

You can specify `$timeout` in seconds to wait for promise to be resolved.
If the promise was not fulfilled in specified timeout, it rejects with `React\Promise\Timer\TimeoutException`. 
When not specified, timeout is set to 2 seconds.

```php
final class PromiseRejectsTest extends TestCase
{
    /** @test */
    public function promise_rejects(): void
    {
        $deferred = new Deferred();
        $deferred->resolve();
        $this->assertPromiseRejects($deferred->promise());
    }
}
```

```bash
PHPUnit 8.5.2 by Sebastian Bergmann and contributors.

F                                                                   1 / 1 (100%)

Time: 175 ms, Memory: 4.00MB

There was 1 failure:

1) seregazhuk\React\PromiseTesting\tests\PromiseRejectsTest::promise_rejects
Failed asserting that promise rejects. Promise was fulfilled.
```

### assertPromiseRejectsWith()
`assertPromiseRejectsWith(PromiseInterface $promise, string $reasonExceptionClass, int $timeout = null): void`

The test fails if the `$promise` doesn't reject with a specified exception class.

You can specify `$timeout` in seconds to wait for promise to be resolved.
If the promise was not fulfilled in specified timeout, it rejects with `React\Promise\Timer\TimeoutException`. 
When not specified, timeout is set to 2 seconds.

```php
final class PromiseRejectsWithTest extends TestCase
{
    /** @test */
    public function promise_rejects_with_a_specified_reason(): void
    {
        $deferred = new Deferred();
        $deferred->reject(new \LogicException());
        $this->assertPromiseRejectsWith($deferred->promise(), \InvalidArgumentException::class);
    }
}
```

```bash
PHPUnit 8.5.2 by Sebastian Bergmann and contributors.

F                                                                   1 / 1 (100%)

Time: 136 ms, Memory: 4.00MB

There was 1 failure:

1) seregazhuk\React\PromiseTesting\tests\PromiseRejectsWithTest::promise_rejects_with_a_specified_reason
Failed asserting that promise rejects with a specified reason.
Failed asserting that LogicException Object (...) is an instance of class "InvalidArgumentException".
```

### assertTrueAboutPromise()
`assertTrueAboutPromise(PromiseInterface $promise, callable $predicate, int $timeout = null): void`

The test fails if the value encapsulated in the Promise does not conform to an arbitrary predicate.

You can specify `$timeout` in seconds to wait for promise to be resolved.
If the promise was not fulfilled in specified timeout, it rejects with `React\Promise\Timer\TimeoutException`. 
When not specified, timeout is set to 2 seconds.

```php
final class AssertTrueAboutPromiseTest extends TestCase
{
    /** @test */
    public function promise_encapsulates_integer(): void
    {
        $deferred = new Deferred();
        $deferred->resolve(23);

        $this->assertTrueAboutPromise($deferred->promise(), function ($val) {
            return is_object($val);
        });
    }
}
```

```bash
PHPUnit 8.5.2 by Sebastian Bergmann and contributors.

F                                                                   1 / 1 (100%)

Time: 136 ms, Memory: 4.00MB

There was 1 failure:

1) seregazhuk\React\PromiseTesting\tests\AssertTrueAboutPromiseTest::promise_encapsulates_integer
Failed asserting that false is true.
```

### assertFalseAboutPromise()
`assertFalseAboutPromise(PromiseInterface $promise, callable $predicate, int $timeout = null): void`

The test fails if the value encapsulated in the Promise conforms to an arbitrary predicate.

You can specify `$timeout` in seconds to wait for promise to be resolved.
If the promise was not fulfilled in specified timeout, it rejects with `React\Promise\Timer\TimeoutException`. 
When not specified, timeout is set to 2 seconds.

```php
final class AssertFalseAboutPromiseTest extends TestCase
{
    /** @test */
    public function promise_encapsulates_object(): void
    {
        $deferred = new Deferred();
        $deferred->resolve(23);

        $this->assertFalseAboutPromise($deferred->promise(), function ($val) {
            return is_int($val);
        });
    }
}
```

```bash
PHPUnit 8.5.2 by Sebastian Bergmann and contributors.

F                                                                   1 / 1 (100%)

Time: 136 ms, Memory: 4.00MB

There was 1 failure:

1) seregazhuk\React\PromiseTesting\tests\AssertFalseAboutPromiseTest::promise_encapsulates_object
Failed asserting that true is false.
```

## Helpers

### waitForPromiseToFulfill()
`function waitForPromiseToFulfill(PromiseInterface $promise, int $timeout = null)`.

This helper can be used when you want to resolve a promise and get the resolution value.

Tries to resolve a `$promise` in a specified `$timeout` seconds and returns resolved value. If `$timeout` is not 
set uses 2 seconds by default. The test fails if the `$promise` doesn't fulfill.

```php
final class WaitForPromiseToFulfillTest extends TestCase
{
    /** @test */
    public function promise_fulfills(): void
    {
        $deferred = new Deferred();

        $deferred->reject(new \Exception());
        $value = $this->waitForPromiseToFulfill($deferred->promise());
    }
}
```

```bash
PHPUnit 8.5.2 by Sebastian Bergmann and contributors.

F                                                                   1 / 1 (100%)

Time: 223 ms, Memory: 6.00MB

There was 1 failure:

1) seregazhuk\React\PromiseTesting\tests\WaitForPromiseToFulfillTest::promise_fulfills
Failed to fulfill a promise. It was rejected with Exception.
```

### waitForPromise()
`function waitForPromise(PromiseInterface $promise, int $timeout = null)`.

Tries to resolve a specified `$promise` in a specified `$timeout` seconds. If `$timeout` is not set uses 2 
seconds by default. If the promise fulfills returns a resolution value, otherwise throws an exception. If the 
promise rejects throws the rejection reason, if the promise doesn't fulfill in a specified `$timeout` throws 
`React\Promise\Timer\TimeoutException`.

This helper can be useful when you need to get the value from the fulfilled promise in a synchronous way:

```php
$value = $this->waitForPromise($cache->get('key'));
```
