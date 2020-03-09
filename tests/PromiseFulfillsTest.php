<?php

namespace seregazhuk\React\PromiseTesting\tests;

use Exception;
use React\Promise\Deferred;
use function React\Promise\Timer\resolve;
use seregazhuk\React\PromiseTesting\TestCase;

final class PromiseFulfillsTest extends TestCase
{
    /** @test */
    public function promise_fulfills(): void
    {
        try {
            $deferred = new Deferred();
            $deferred->reject();
            $this->assertPromiseFulfills($deferred->promise(), 1);
        } catch (Exception $exception) {
            $this->assertRegExp(
                '/Failed asserting that promise fulfills. Promise was rejected/',
                $exception->getMessage()
            );
        } 
     }

    /** @test */
    public function it_fails_when_promise_doesnt_fulfill_in_a_specified_timeout(): void
    {
        try {
            $deferred = new Deferred();

            $deferred->reject();
            $promise = resolve($timeToResolve = 3, $this->eventLoop());

            $promise->then(static function() use ($deferred) {
                $deferred->resolve();
            });

            $this->assertPromiseFulfills($promise, 1);
        } catch (Exception $exception) {
            $this->assertRegExp(
                '/Promise was cancelled due to timeout./',
                $exception->getMessage()
            );

            $this->assertRegExp(
                '/Promise was cancelled due to timeout/',
                $exception->getMessage()
            );
        }
    }
}
