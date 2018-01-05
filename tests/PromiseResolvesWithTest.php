<?php

namespace seregazhuk\React\PromiseTesting\tests;

use React\Promise\Deferred;
use function React\Promise\Timer\resolve;
use seregazhuk\React\PromiseTesting\TestCase;

class PromiseResolvesWithTest extends TestCase
{
    /** @test */
    public function promise_resolves()
    {
        try {
            $deferred = new Deferred();

            $deferred->resolve(1234);
            $this->assertPromiseResolvesWith($deferred->promise(), 1);
        } catch (\PHPUnit_Framework_Exception $exception) {
            $this->assertRegExp(
                '/Failed asserting that promise resolves with a specified value/',
                $exception->getMessage()
            );

            $this->assertRegExp(
                '/Failed asserting that .+ matches expected .+/',
                $exception->getMessage()
            );
        }
    }

    /** @test */
    public function it_fails_when_promise_rejects()
    {
        try {
            $deferred = new Deferred();

            $deferred->reject();
            $this->assertPromiseResolvesWith($deferred->promise(), 1);
        } catch (\PHPUnit_Framework_Exception $exception) {
            $this->assertRegExp(
                '/Failed asserting that promise resolves with a specified value/',
                $exception->getMessage()
            );

            $this->assertRegExp(
                '/Promise was rejected/',
                $exception->getMessage()
            );
        }
    }

    /** @test */
    public function it_fails_when_promise_doesnt_resolve_in_a_specified_timeout()
    {
        try {
            $deferred = new Deferred();

            $deferred->reject();
            $promise = resolve(3, $this->loop);

            $promise->then(function() use ($deferred){
                $deferred->resolve();
            });

            $this->assertPromiseResolvesWith($promise, 1);
        } catch (\PHPUnit_Framework_Exception $exception) {
            $this->assertRegExp(
                '/Failed asserting that promise resolves with a specified value/',
                $exception->getMessage()
            );

            $this->assertRegExp(
                '/Promise was rejected by timeout/',
                $exception->getMessage()
            );
        }
    }
}
