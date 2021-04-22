<?php

namespace seregazhuk\React\PromiseTesting\tests;

use Exception;
use React\Promise\Deferred;
use seregazhuk\React\PromiseTesting\TestCase;

final class AboutPromiseTest extends TestCase
{
    /** @test */
    public function predicate_function_reveals_what_is_true_about_promise(): void
    {
        try {
            $fst = new Deferred;
            $fst->resolve(23);

            $snd = new Deferred;
            $snd->reject(new Exception('An error'));

            $thd = new Deferred;
            $thd->reject('Another error');

            $this->assertTrueAboutPromise($fst->promise(), function ($val): bool {
                return is_int($val) && $val > 20;
            });
            $this->assertTrueAboutPromise($fst->promise(), 'is_string');

            $this->assertTrueAboutPromise($snd->promise(), 'is_string');

            $this->assertTrueAboutPromise($thd->promise(), 'is_string');
        } catch (Exception $exception) {
            $this->assertMatchesRegularExpression(
                '/Failed asserting that .+/',
                $exception->getMessage()
            );
        }
    }

    /** @test */
    public function predicate_function_reveals_what_is_false_about_promise(): void
    {
        try {
            $fst = new Deferred;
            $fst->resolve(23);

            $snd = new Deferred;
            $snd->reject(new Exception('An error'));

            $thd = new Deferred;
            $thd->reject('Another error');

            $this->assertFalseAboutPromise($fst->promise(), function ($val): bool {
                return is_int($val) && $val > 20;
            });
            $this->assertFalseAboutPromise($fst->promise(), 'is_string');

            $this->assertFalseAboutPromise($snd->promise(), 'is_string');

            $this->assertFalseAboutPromise($thd->promise(), 'is_int');
        } catch (Exception $exception) {
            $this->assertMatchesRegularExpression(
                '/Failed asserting that .+/',
                $exception->getMessage()
            );
        }
    }
}
