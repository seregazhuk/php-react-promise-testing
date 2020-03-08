<?php

namespace seregazhuk\React\PromiseTesting\tests;

use Exception;
use React\Promise\Deferred;
use seregazhuk\React\PromiseTesting\TestCase;

final class WaitForPromiseToFulfillTest extends TestCase
{
    /** @test */
    public function promise_resolves(): void
    {
        try {
            $deferred = new Deferred();

            $deferred->reject(new Exception());
            $value = $this->waitForPromiseToFulfill($deferred->promise());
        } catch (Exception $exception) {
            $this->assertRegExp(
                '/Failed to fulfill a promise. It was rejected with Exception/',
                $exception->getMessage()
            );
        }
    }
}
