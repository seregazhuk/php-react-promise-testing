<?php

namespace seregazhuk\React\PromiseTesting\tests;

use Exception;
use React\Promise\Deferred;
use function React\Promise\Timer\resolve;
use seregazhuk\React\PromiseTesting\TestCase;

final class PromiseFulfillsWithTest extends TestCase
{
    /** @test */
    public function promise_fulfills_with_a_specified_value(): void
    {
        try {
            $deferred = new Deferred();

            $deferred->resolve(1234);
            $this->assertPromiseFulfillsWith($deferred->promise(), 1);
        } catch (Exception $exception) {
            $this->assertMatchesRegularExpression(
                '/Failed asserting that promise fulfills with a specified value/',
                $exception->getMessage()
            );

            $this->assertMatchesRegularExpression(
                '/Failed asserting that .+ matches expected .+/',
                $exception->getMessage()
            );
        }
    }

    /** @test */
    public function it_fails_when_promise_rejects(): void
    {
        try {
            $deferred = new Deferred();

            $deferred->reject();
            $this->assertPromiseFulfillsWith($deferred->promise(), 1);
        } catch (Exception $exception) {
            $this->assertMatchesRegularExpression(
                '/Failed asserting that promise fulfills with a specified value/',
                $exception->getMessage()
            );

            $this->assertMatchesRegularExpression(
                '/Promise was rejected/',
                $exception->getMessage()
            );
        }
    }

    /** @test */
    public function it_fails_when_promise_doesnt_fulfill_in_a_specified_timeout(): void
    {
        try {
            $deferred = new Deferred();

            $deferred->reject();
            $promise = resolve($timeToResolve = 3, $this->eventLoop());

            $promise->then(
                static function () use ($deferred) {
                    $deferred->resolve();
                }
            );

            $this->assertPromiseFulfillsWith($promise, 1, 1);
        } catch (Exception $exception) {
            $this->assertMatchesRegularExpression(
                '/Failed asserting that promise fulfills with a specified value/',
                $exception->getMessage()
            );

            $this->assertMatchesRegularExpression(
                '/Promise was cancelled due to timeout/',
                $exception->getMessage()
            );
        }
    }
}
