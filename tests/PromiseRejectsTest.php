<?php

namespace seregazhuk\React\PromiseTesting\tests;

use Exception;
use React\Promise\Deferred;
use seregazhuk\React\PromiseTesting\TestCase;

final class PromiseRejectsTest extends TestCase
{
    /** @test */
    public function promise_rejects(): void
    {
        try {
            $deferred = new Deferred();
            $deferred->resolve();
            $this->assertPromiseRejects($deferred->promise());
        } catch (Exception $exception) {
            $this->assertRegExp(
                '/Failed asserting that promise rejects. Promise was fulfilled/',
                $exception->getMessage()
            );
        }
    }
}
