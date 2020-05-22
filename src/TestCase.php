<?php

namespace seregazhuk\React\PromiseTesting;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;

abstract class TestCase extends PHPUnitTestCase
{
    use AssertsPromise;

    protected function setUp(): void
    {
        parent::setUp();

        $this->eventLoop();
    }
}
