<?php

namespace seregazhuk\React\PromiseTesting\tests;

use Exception;
use React\Promise\Deferred;
use function React\Promise\Timer\resolve;
use seregazhuk\React\PromiseTesting\TestCase;

class PromiseFulfillsTest extends TestCase
{
    /** @test */
    public function promise_fulfills()
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
    public function it_fails_when_promise_doesnt_fulfill_in_a_specified_timeout()
    {
        try {
            $deferred = new Deferred();

            $deferred->reject();
            $promise = resolve(3, $this->loop);

            $promise->then(function() use ($deferred){
                $deferred->resolve();
            });

            $this->assertPromiseFulfills($promise, 1);
        } catch (Exception $exception) {
            $this->assertRegExp(
                '/Failed asserting that promise fulfills. Promise was rejected/',
                $exception->getMessage()
            );

            $this->assertRegExp(
                '/Promise was rejected by timeout/',
                $exception->getMessage()
            );
        }
    }
}
