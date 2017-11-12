<?php

namespace seregazhuk\React\PromiseTesting\tests;

use React\Promise\Deferred;
use React\Promise\Promise;
use seregazhuk\React\PromiseTesting\TestCase;

class WaitForPromiseToResolveTest extends TestCase
{
    /** @test */
    public function promise_resolves()
    {
        try {
            $deferred = new Deferred();

            $deferred->reject(new \Exception());
            $value = $this->waitForPromiseToResolve($deferred->promise());
        } catch (\PHPUnit_Framework_Exception $exception) {
            $this->assertRegExp(
                '/Failed to resolve a promise. It was rejected with Exception/',
                $exception->getMessage()
            );
        }
    }
}
