<?php

namespace seregazhuk\React\PromiseTesting;

use Clue\React\Block;
use Exception;
use PHPUnit\Framework\AssertionFailedError;
use React\EventLoop\Factory as LoopFactory;
use React\EventLoop\LoopInterface;
use React\Promise\PromiseInterface;
use React\Promise\Timer\TimeoutException;

trait AssertsPromise
{
    protected int $defaultWaitTimeout = 2;

    private $loop;

    /**
     * @param PromiseInterface $promise
     * @param int|null $timeout seconds to wait for resolving
     * @throws AssertionFailedError
     */
    public function assertPromiseFulfills(PromiseInterface $promise, int $timeout = null): void
    {
        $failMessage = 'Failed asserting that promise fulfills. ';
        $this->addToAssertionCount(1);

        try {
            $this->waitForPromise($promise, $timeout);
        } catch (TimeoutException $exception) {
            $this->fail($failMessage . 'Promise was cancelled due to timeout.');
        } catch (Exception $exception) {
            $this->fail($failMessage . 'Promise was rejected.');
        }
    }

    /**
     * @param PromiseInterface $promise
     * @param mixed $value
     * @param int|null $timeout
     * @throws AssertionFailedError
     */
    public function assertPromiseFulfillsWith(PromiseInterface $promise, $value, int $timeout = null): void
    {
        $failMessage = 'Failed asserting that promise fulfills with a specified value. ';
        $result = null;
        $this->addToAssertionCount(1);

        try {
            $result = $this->waitForPromise($promise, $timeout);
        } catch (TimeoutException $exception) {
            $this->fail($failMessage . 'Promise was cancelled due to timeout.');
        } catch (Exception $exception) {
            $this->fail($failMessage . 'Promise was rejected.');
        }

        $this->assertEquals($value, $result, $failMessage);
    }

    /**
     * @throws AssertionFailedError
     */
    public function assertPromiseFulfillsWithInstanceOf(PromiseInterface $promise, string $class, int $timeout = null): void
    {
        $failMessage = "Failed asserting that promise fulfills with a value of class $class. ";
        $result = null;
        $this->addToAssertionCount(1);

        try {
            $result = $this->waitForPromise($promise, $timeout);
        } catch (TimeoutException $exception) {
            $this->fail($failMessage . 'Promise was cancelled due to timeout.');
        } catch (Exception $exception) {
            $this->fail($failMessage . 'Promise was rejected.');
        }

        $this->assertInstanceOf($class, $result, $failMessage);
    }

    /**
     * @param PromiseInterface $promise
     * @param int|null $timeout
     * @throws AssertionFailedError
     */
    public function assertPromiseRejects(PromiseInterface $promise, int $timeout = null): void
    {
        $this->addToAssertionCount(1);

        try {
            $this->waitForPromise($promise, $timeout);
        } catch (Exception $exception) {
            return;
        }

        $this->fail('Failed asserting that promise rejects. Promise was fulfilled.');
    }

    /**
     * @throws AssertionFailedError
     */
    public function assertPromiseRejectsWith(PromiseInterface $promise, string $reasonExceptionClass, int $timeout = null): void
    {
        try {
            $this->waitForPromise($promise, $timeout);
        } catch (Exception $reason) {
            $this->assertInstanceOf(
                $reasonExceptionClass,
                $reason,
                'Failed asserting that promise rejects with a specified reason.'
            );

            return;
        }

        $this->fail('Failed asserting that promise rejects. Promise was fulfilled.');
    }

    /**
     * @throws Exception
     * @return mixed
     */
    public function waitForPromiseToFulfill(PromiseInterface $promise, int $timeout = null)
    {
        try {
            return $this->waitForPromise($promise, $timeout);
        } catch (Exception $exception) {
            $reason = get_class($exception);
            $this->fail("Failed to fulfill a promise. It was rejected with {$reason}.");
        }
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function waitForPromise(PromiseInterface $promise, int $timeout = null)
    {
        return Block\await($promise, $this->eventLoop(), $timeout ?: $this->defaultWaitTimeout);
    }

    public function eventLoop(): LoopInterface
    {
        if (! $this->loop) {
            $this->loop = LoopFactory::create();
        }

        return $this->loop;
    }

    /**
     * @param PromiseInterface $promise
     * @param callable $predicate
     * @param int|null $timeout
     * @throws AssertionFailedError
     */
    public function assertTrueAboutPromise(
        PromiseInterface $promise,
        callable $predicate,
        int $timeout = null
    ): void {
        $this->assertAboutPromise($promise, $predicate, $timeout);
    }

    /**
     * @param PromiseInterface $promise
     * @param callable $predicate
     * @param int|null $timeout
     * @throws AssertionFailedError
     */
    public function assertFalseAboutPromise(
        PromiseInterface $promise,
        callable $predicate,
        int $timeout = null
    ): void {
        $this->assertAboutPromise($promise, $predicate, $timeout, false);
    }

    /**
     * @param PromiseInterface $promise
     * @param callable $predicate
     * @param int|null $timeout
     * @param bool $assertTrue
     * @throws AssertionFailedError
     */
    private function assertAboutPromise(
        PromiseInterface $promise,
        callable $predicate,
        int $timeout = null,
        bool $assertTrue = true
    ): void {
        $result = $assertTrue ? false : true;
        $this->addToAssertionCount(1);

        try {
            $result = $predicate($this->waitForPromise($promise, $timeout));
        } catch (TimeoutException $exception) {
            $this->fail('Promise was cancelled due to timeout');
        } catch (Exception $exception) {
            $this->fail('Failed asserting that promise was fulfilled. Promise was rejected');
        }

        $assertTrue ? $this->assertTrue($result) : $this->assertFalse($result);
    }
}
