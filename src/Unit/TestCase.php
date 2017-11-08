<?php

namespace seregazhuk\React\PromiseTesting\Unit;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use React\Promise\PromiseInterface;

class TestCase extends PHPUnitTestCase
{
    public function assertPromiseResolvesWith(PromiseInterface $promise, $value)
    {
        /** @var PromiseInterface $promise */
        $promise->then(null, function($error) {
            $this->assertNull($error);
            $this->fail('promise rejected');
        });

        $promise->then($this->assertCallableCalledOnceWithArgs([$value]), $this->assertCallableNeverCalled());
    }

    public function assertPromiseResolves(PromiseInterface $promise)
    {
        /** @var PromiseInterface $promise */
        $promise->then(null, function($error) {
            $this->assertNull($error);
            $this->fail('promise rejected');
        });

        $promise->then($this->assertCallableCalledOnce(), $this->assertCallableNeverCalled());
    }

    public function assertPromiseRejectsWith(PromiseInterface $promise, $reasonExceptionClass)
    {
        /** @var PromiseInterface $promise */
        $promise->then(null, function($error) {
            $this->assertNull($error);
            $this->fail('promise resolved');
        });

        $promise->then(
            $this->assertCallableNeverCalled(),
            $this->assertCallableCalledOnceWithObjectOf($reasonExceptionClass)
        );
    }

    public function assertCallableCalledOnce()
    {
        $this->getMockBuilder(CallableStub::class)
            ->getMock()
            ->expects($this->once())
            ->method('__invoke');
    }

    /**
     * @param array $arguments
     */
    public function assertCallableCalledOnceWithArgs(array $arguments = [])
    {
        $this->getMockBuilder(CallableStub::class)
            ->getMock()
            ->expects($this->once())
            ->method('__invoke')
            ->with($arguments);
    }

    /**
     * @param string $class
     */
    public function assertCallableCalledOnceWithObjectOf($class)
    {
        $this->getMockBuilder(CallableStub::class)
            ->getMock()
            ->expects($this->once())
            ->method('__invoke')
            ->with($this->callback(function($arg) use ($class) {
                $this->assertInstanceOf($class, $arg);
            }));
    }

    public function assertCallableNeverCalled()
    {
        $this->getMockBuilder(CallableStub::class)
            ->getMock()
            ->expects($this->never())
            ->method('__invoke');
    }
}
