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
            $deferred->reject(new \InvalidArgumentException());
            $this->assertPromiseRejectsWith($deferred->promise(), \LogicException::class);
        } catch (Exception $exception) {
            $this->assertMatchesRegularExpression('/Failed asserting that promise rejects with a specified reason/', $exception->getMessage());
            $this->assertMatchesRegularExpression(
                '/Failed asserting that LogicException Object .+ is an instance of class "InvalidArgumentException"/',
                $exception->getMessage()
            );
        }
    }
}
