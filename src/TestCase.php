<?php

namespace seregazhuk\React\PromiseTesting;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;

abstract class TestCase extends PHPUnitTestCase
{
    use AssertPromise;

    protected function setUp(): void
    {
        parent::setUp();

        $this->eventLoop();
    }
}
