<?php

namespace seregazhuk\React\PromiseTesting\Unit;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use PHPUnit_Framework_MockObject_MockObject;
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

    /**
     * @return PHPUnit_Framework_MockObject_MockObject|callable
     */
    public function assertCallableCalledOnce()
    {
        $mock = $this->getMockBuilder(CallableStub::class)->getMock();
        $mock->expects($this->once())->method('__invoke');

        return $mock;
    }

    /**
     * @param array $arguments
     * @return PHPUnit_Framework_MockObject_MockObject|callable
     */
    public function assertCallableCalledOnceWithArgs(array $arguments = [])
    {
        $mock = $this->getMockBuilder(CallableStub::class)->getMock();
        $mock->expects($this->once())->method('__invoke')->with(...$arguments);

        return $mock;
    }

    /**
     * @param string $class
     * @return PHPUnit_Framework_MockObject_MockObject|callable
     */
    public function assertCallableCalledOnceWithObjectOf($class)
    {
        $mock = $this->getMockBuilder(CallableStub::class)->getMock();

        $mock->expects($this->once())
            ->method('__invoke')
            ->with($this->isInstanceOf($class));

        return $mock;
    }

    /**
     * @return PHPUnit_Framework_MockObject_MockObject|callable
     */
    public function assertCallableNeverCalled()
    {
        $mock = $this->getMockBuilder(CallableStub::class)->getMock();
        $mock->expects($this->never())->method('__invoke');

        return $mock;
    }
}
