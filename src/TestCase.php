<?php

namespace seregazhuk\React\PromiseTesting;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use React\Promise\PromiseInterface;
use Clue\React\Block;
use Exception;
use React\EventLoop\LoopInterface;
use React\EventLoop\Factory as LoopFactory;
use React\Promise\Timer\TimeoutException;

class TestCase extends PHPUnitTestCase
{
    const DEFAULT_WAIT_TIMEOUT = 2;
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
     * @param int|null $timeout seconds to wait for resolving
     * @return mixed
     */
    public function assertPromiseResolves(PromiseInterface $promise, $timeout = null)
    {
        $failMessage = 'Failed asserting that promise resolves. ';

        try {
            return $this->waitForPromise($promise, $timeout);
        } catch (TimeoutException $exception) {
            $this->fail($failMessage . 'Promise was rejected by timeout.');
        } catch (Exception $exception) {
            $this->fail($failMessage . 'Promise was rejected.');
        }
    }

    /**
     * /**
     * @param PromiseInterface $promise
     * @param mixed $value
     * @param int|null $timeout
     */
    public function assertPromiseResolvesWith(PromiseInterface $promise, $value, $timeout = null)
    {
        $failMessage = 'Failed asserting that promise resolves with a specified value. ';

        try {
            $result = $this->waitForPromise($promise, $timeout);
        } catch (TimeoutException $exception) {
            $this->fail($failMessage . 'Promise was rejected by timeout.');
        } catch (Exception $exception) {
            $this->fail($failMessage . 'Promise was rejected.');
        }

        $this->assertEquals($value, $result, $failMessage);
    }

    /**
     * @param PromiseInterface $promise
     * @param int|null $timeout
     * @return Exception
     */
    public function assertPromiseRejects(PromiseInterface $promise, $timeout = null)
    {
        try {
            $this->waitForPromise($promise, $timeout);
        } catch (Exception $exception) {
            return $exception;
        }

        $this->fail('Failed asserting that promise rejects. Promise was resolved.');
    }

    /**
     * @param PromiseInterface $promise
     * @param string $reasonExceptionClass
     * @param int|null $timeout
     */
    public function assertPromiseRejectsWith(PromiseInterface $promise, $reasonExceptionClass, $timeout = null)
    {
        $reason = $this->assertPromiseRejects($promise, $timeout);

        $this->assertInstanceOf(
            $reasonExceptionClass,
            $reason,
            'Failed asserting that promise rejects with a specified reason.'
        );
    }

    /**
     * @param PromiseInterface $promise
     * @param int|null $timeout
     * @return mixed
     * @throws Exception
     */
    public function waitForPromiseToResolve(PromiseInterface $promise, $timeout = null)
    {
        try {
            return $this->waitForPromise($promise, $timeout);
        } catch (Exception $exception) {
            $reason = get_class($exception);
            $this->fail("Failed to resolve a promise. It was rejected with {$reason}.");
        }
    }

    /**
     * @param PromiseInterface $promise
     * @param int $timeout
     * @return mixed
     * @throws Exception
     */
    public function waitForPromise(PromiseInterface $promise, $timeout = null)
    {
        return Block\await($promise, $this->loop, $timeout ?: self::DEFAULT_WAIT_TIMEOUT);
    }
}
