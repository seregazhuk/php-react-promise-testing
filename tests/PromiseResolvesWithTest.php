<?php

namespace seregazhuk\React\PromiseTesting\tests;

use React\Promise\Deferred;
use React\Promise\Promise;
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
}
