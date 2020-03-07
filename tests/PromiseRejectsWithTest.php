<?php

namespace seregazhuk\React\PromiseTesting\tests;

use Exception;
use React\Promise\Deferred;
use seregazhuk\React\PromiseTesting\TestCase;

final class PromiseRejectsWithTest extends TestCase
{
    /** @test */
    public function promise_rejects_with_a_specified_reason(): void
    {
        try {
            $deferred = new Deferred();
            $deferred->reject(new \LogicException());
            $this->assertPromiseRejectsWith($deferred->promise(), \InvalidArgumentException::class);
        } catch (Exception $exception) {
            $this->assertRegExp('/Failed asserting that promise rejects with a specified reason/', $exception->getMessage());
            $this->assertRegExp(
                '/Failed asserting that LogicException Object .+ is an instance of class "InvalidArgumentException"/',
                $exception->getMessage()
            );
        }
    }
}
