<?php

namespace seregazhuk\React\PromiseTesting;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use React\Promise\Promise;
use Clue\React\Block;
use Exception;
use React\EventLoop\LoopInterface;
use React\EventLoop\Factory as LoopFactory;

class TestCase extends PHPUnitTestCase
{
    /**
     * @var LoopInterface
     */
    protected $loop;

    protected function setUp()
    {
        $this->loop = LoopFactory::create();
    }

    /**
     * @param Promise $promise
     * @param mixed $value
     */
    public function assertPromiseResolvesWith(Promise $promise, $value)
    {
        $promise->done(
            function($resolvedValue) use ($value) {
                $this->assertEquals($resolvedValue, $value, 'Failed asserting that promise resolves with a specified value.');
            },
            function() {
                $this->fail('Failed asserting that promise resolves with a specified value. Promise was rejected.');
            });
    }

    /**
     * @param Promise $promise
     */
    public function assertPromiseResolves(Promise $promise)
    {
        $promise->done(null, function(){
            $this->fail('Failed asserting that promise resolves. Promise was rejected.');
        });
    }

    public function assertPromiseRejects(Promise $promise)
    {
        $promise->done(function() {
            $this->fail('Failed asserting that promise rejects. Promise was resolved.');
        });
    }

    /**
     * @param Promise $promise
     * @param string $reasonExceptionClass
     */
    public function assertPromiseRejectsWith(Promise $promise, $reasonExceptionClass)
    {
        $promise->done(function() {
            $this->fail('Failed asserting that promise rejects. Promise was resolved.');
        }, function($reason) use ($reasonExceptionClass) {
            $this->assertInstanceOf(
                $reasonExceptionClass,
                $reason,
                'Failed asserting that promise rejects with a specified reason.');
        });
    }

    /**
     * @param Promise $promise
     * @return mixed
     * @throws Exception
     */
    public function waitForPromiseResolves(Promise $promise)
    {
        return Block\await($promise, $this->loop);
    }

    /**
     * @param Promise $promise
     * @return Exception
     */
    public function waitForPromiseRejects(Promise $promise)
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
     * @param Promise $promise
     * @throws Exception
     */
    public function waitForPromiseResolvesWith(Promise $promise, $value)
    {
        $this->assertEquals($value, $this->waitForPromiseResolves($promise));
    }

    /**
     * @param Promise $promise
     * @param string $reasonExceptionClass
     */
    public function waitPromiseRejectsWith(Promise $promise, $reasonExceptionClass)
    {
        $reason = $this->waitForPromiseRejects($promise);

        $this->assertInstanceOf(
            $reasonExceptionClass,
            $reason,
            'Failed asserting that promise rejects with a specified reason.'
        );
    }
}
