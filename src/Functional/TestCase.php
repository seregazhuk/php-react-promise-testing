<?php

namespace seregazhuk\React\PromiseTesting\Functional;

use Clue\React\Block;
use Exception;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use React\EventLoop\LoopInterface;
use React\EventLoop\Factory as LoopFactory;
use React\Promise\PromiseInterface;

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
    public function assertPromiseResolvesWith(PromiseInterface $promise, $value)
    {
        $this->assertEquals($value, $this->waitForPromiseResolves($promise));
    }

    /**
     * @param PromiseInterface $promise
     * @param string $reasonExceptionClass
     */
    public function assertPromiseRejectsWith(PromiseInterface $promise, $reasonExceptionClass)
    {
        $reason = $this->waitForPromiseRejects($promise);

        $this->assertInstanceOf(
            $reasonExceptionClass,
            $reason,
            "Promise should reject with instance of $reasonExceptionClass, got " . get_class($reason)
        );
    }
}
