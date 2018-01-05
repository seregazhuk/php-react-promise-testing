<?php

namespace seregazhuk\React\PromiseTesting\tests;

use React\Promise\Deferred;
use function React\Promise\Timer\resolve;
use seregazhuk\React\PromiseTesting\TestCase;

class PromiseResolvesTest extends TestCase
{
    /** @test */
    public function promise_resolves()
    {
        try {
            $deferred = new Deferred();
            $deferred->reject();
            $this->assertPromiseResolves($deferred->promise(), 1);
        } catch (\PHPUnit_Framework_Exception $exception) {
            $this->assertRegExp(
                '/Failed asserting that promise resolves. Promise was rejected/',
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

            $this->assertPromiseResolves($promise, 1);
        } catch (\PHPUnit_Framework_Exception $exception) {
            $this->assertRegExp(
                '/Failed asserting that promise resolves. Promise was rejected/',
                $exception->getMessage()
            );

            $this->assertRegExp(
                '/Promise was rejected by timeout/',
                $exception->getMessage()
            );
        }
    }
}
