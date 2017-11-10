<?php

namespace seregazhuk\React\PromiseTesting\tests;

use React\Promise\Deferred;
use React\Promise\Promise;
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
}
