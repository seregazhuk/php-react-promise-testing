<?php

namespace seregazhuk\React\PromiseTesting;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use PHPUnit_Framework_MockObject_MockObject;
use React\Promise\PromiseInterface;
use Clue\React\Block;
use Exception;
use React\EventLoop\LoopInterface;
use React\EventLoop\Factory as LoopFactory;

class TestCase extends PHPUnitTestCase
{
    /**
     * @param PromiseInterface $promise
     * @param mixed $value
     */
    public function assertPromiseResolvesWith(PromiseInterface $promise, $value)
    {
        /** @var PromiseInterface $promise */
        $promise->then(null, function($error) {
            $this->assertNull($error);
            $this->fail('promise rejected');
        });

        $promise->then($this->assertCallableCalledOnceWithArgs([$value]), $this->assertCallableNeverCalled());
    }

    /**
     * @param PromiseInterface $promise
     */
    public function assertPromiseResolves(PromiseInterface $promise)
    {
        /** @var PromiseInterface $promise */
        $promise->then(null, function($error) {
            $this->assertNull($error);
            $this->fail('Promise rejected');
        });

        $promise->then($this->assertCallableCalledOnce(), $this->assertCallableNeverCalled());
    }

    /**
     * @param PromiseInterface $promise
     * @param string $reasonExceptionClass
     */
    public function assertPromiseRejectsWith(PromiseInterface $promise, $reasonExceptionClass)
    {
        /** @var PromiseInterface $promise */
        $promise->then(null, function($error) {
            $this->assertNull($error);
            $this->fail('Promise resolved');
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

    /**
     * @var LoopInterface
     */
    protected $loop;

    protected function setUp()
    {
        $this->loop = LoopFactory::create();
    }

    /**
     * @param PromiseInterface $promise
     * @return mixed
     * @throws Exception
     */
    public function waitForPromiseResolves(PromiseInterface $promise)
    {
        return Block\await($promise, $this->loop);
    }

    /**
     * @param PromiseInterface $promise
     * @return Exception
     */
    public function waitForPromiseRejects(PromiseInterface $promise)
    {
        try {
            Block\await($promise, $this->loop);
        } catch (Exception $exception) {
            return $exception;
        }

        $this->fail('Promise resolved');
    }

    /**
     * @param mixed $value
     * @param PromiseInterface $promise
     * @throws Exception
     */
    public function waitForPromiseResolvesWith(PromiseInterface $promise, $value)
    {
        $this->assertEquals($value, $this->waitForPromiseResolves($promise));
    }

    /**
     * @param PromiseInterface $promise
     * @param string $reasonExceptionClass
     */
    public function waitPromiseRejectsWith(PromiseInterface $promise, $reasonExceptionClass)
    {
        $reason = $this->waitForPromiseRejects($promise);

        $this->assertInstanceOf(
            $reasonExceptionClass,
            $reason,
            "Promise should reject with instance of $reasonExceptionClass, got " . get_class($reason)
        );
    }
}
