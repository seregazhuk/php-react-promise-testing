# ReactPHP Promises Testing
A library that provides a set of convenient assertions for testing ReactPHP promises. 

[![Build Status](https://travis-ci.org/seregazhuk/php-react-promise-testing.svg?branch=master)](https://travis-ci.org/seregazhuk/php-react-promise-testing)

When testing asynchronous code and promises things can be a bit tricky. This library provides a set of convenient 
assertions for testing ReactPHP promises. 

**Table of Contents**
- [Installation](#installation)
- [Quick Start](#quick-start)
- [Assertions](#assertions)
    - [assertPromiseResolves()](#assertpromiseresolves)
    - [assertPromiseResolvesWith()](#assertpromiseresolveswith)
    - [assertPromiseRejects()](#assertpromiserejects)
    - [assertPromiseRejectsWith()](#assertpromiserejectswith)
    
## Installation

### Dependencies
Library requires PHP 5.6.0 or above.

The recommended way to install this library is via [Composer](https://getcomposer.org). 
[New to Composer?](https://getcomposer.org/doc/00-intro.md)

See also the [CHANGELOG](CHANGELOG.md) for details about version upgrades.

```
composer require seregazhuk/react-promise-testing
```

## Quick Start
To start using it extend your test classes from `seregazhuk\React\PromiseTesting\TestCase` class, 
which itself extends PHPUnit `TestCase`:
 
```php
class MyTest extends TestCase
{
    /** @test */
    public function promise_resolves()
    {
        $resolve = function(callable $resolve, callable $reject) {
            return $resolve('Promise resolved!');
        };

        $cancel = function(callable $resolve, callable $reject) {
            $reject(new \Exception('Promise cancelled!'));
        };

        $promise = new Promise($resolve, $cancel);
        $this->assertPromiseResolves($promise);
    }
}

```

Test above checks that a specified promise resolves. If the promise was rejected this test fails.

## Assertions

### assertPromiseResolves()

`public function assertPromiseResolves(PromiseInterface $promise, $timeout = null)`

The test fails if the `$promise` rejects. 

You can specify `$timeout` in seconds to wait for promise to be resolved.
If the promise was not resolved in specified timeout the test fails. When not specified, timeout is set to 2 seconds.

```php
class PromiseResolvesTest extends TestCase
{
    /** @test */
    public function promise_resolves()
    {
        $deferred = new Deferred();
        $deferred->reject();
        $this->assertPromiseResolves($deferred->promise(), 1);
    }
}
```

```bash
PHPUnit 5.7.23 by Sebastian Bergmann and contributors.

F                                                                   1 / 1 (100%)

Time: 189 ms, Memory: 4.00MB

There was 1 failure:

1) seregazhuk\React\PromiseTesting\tests\PromiseResolvesTest::promise_resolves
Failed asserting that promise resolves. Promise was rejected.
```

### assertPromiseResolvesWith()
`assertPromiseResolvesWith(PromiseInterface $promise, $value, $timeout = null)`

The test fails if the `$promise` doesn't resolve with a specified `$value`.

You can specify `$timeout` in seconds to wait for promise to be resolved.
If the promise was not resolved in specified timeout the test fails. When not specified, timeout is set to 2 seconds.

```php
class PromiseResolvesWithTest extends TestCase
{
    /** @test */
    public function promise_resolves_with_a_specified_value()
    {
        $deferred = new Deferred();
        $deferred->resolve(1234);
        $this->assertPromiseResolvesWith($deferred->promise(), 1);
    }
}
```

```bash
PHPUnit 5.7.23 by Sebastian Bergmann and contributors.

F                                                                   1 / 1 (100%)

Time: 180 ms, Memory: 4.00MB

There was 1 failure:

1) seregazhuk\React\PromiseTesting\tests\PromiseResolvesWithTest::promise_resolves
Failed asserting that promise resolves with a specified value. 
Failed asserting that 1234 matches expected 1.
```

### assertPromiseRejects()
`assertPromiseRejects(PromiseInterface $promise, $timeout = null)`

The test fails if the `$promise` resolves.

You can specify `$timeout` in seconds to wait for promise to be rejected.
If the promise was not resolved in specified timeout the test fails. When not specified, timeout is set to 2 seconds.

```php
class PromiseRejectsTest extends TestCase
{
    /** @test */
    public function promise_rejects()
    {
        $deferred = new Deferred();
        $deferred->resolve();
        $this->assertPromiseRejects($deferred->promise());
    }
}
```

```bash
PHPUnit 5.7.23 by Sebastian Bergmann and contributors.

F                                                                   1 / 1 (100%)

Time: 175 ms, Memory: 4.00MB

There was 1 failure:

1) seregazhuk\React\PromiseTesting\tests\PromiseRejectsTest::promise_rejects
Failed asserting that promise rejects. Promise was resolved.
```

### assertPromiseRejectsWith()
`assertPromiseRejectsWith(PromiseInterface $promise, $reasonExceptionClass, $timeout = null)`

The test fails if the `$promise` doesn't reject with a specified exception class.

You can specify `$timeout` in seconds to wait for promise to be rejected.
If the promise was not resolved in specified timeout the test fails. When not specified, timeout is set to 2 seconds.

```php
class PromiseRejectsWithTest extends TestCase
{
    /** @test */
    public function promise_rejects_with_a_specified_reason()
    {
        $deferred = new Deferred();
        $deferred->reject(new \LogicException());
        $this->assertPromiseRejectsWith($deferred->promise(), \InvalidArgumentException::class);
    }
}
```

```bash
PHPUnit 5.7.23 by Sebastian Bergmann and contributors.

F                                                                   1 / 1 (100%)

Time: 136 ms, Memory: 4.00MB

There was 1 failure:

1) seregazhuk\React\PromiseTesting\tests\PromiseRejectsWithTest::promise_rejects_with_a_specified_reason
Failed asserting that promise rejects with a specified reason.
Failed asserting that LogicException Object (...) is an instance of class "InvalidArgumentException".
```
