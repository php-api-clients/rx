<?php

declare(strict_types=1);

namespace ApiClients\Tests\Tools\Rx;

use ApiClients\Tools\TestUtilities\TestCase;
use Exception;
use Rx\Observable;
use Rx\React\Promise;
use Throwable;

use function ApiClients\Tools\Rx\unwrapObservableFromPromise;
use function range;
use function React\Promise\reject;
use function React\Promise\resolve;

/**
 * @internal
 */
final class FunctionsUnwrapObservableFromPromiseTest extends TestCase
{
    public function testUnwrapObservableFromPromise(): void
    {
        $completed = false;
        $currentI  = null;

        unwrapObservableFromPromise(
            resolve(
                Observable::fromArray(range(0, 1337))
            )
        )->subscribe(
            static function ($i) use (&$currentI): void {
                $currentI = $i;
            },
            null,
            static function () use (&$completed): void {
                $completed = true;
            }
        );

        self::assertTrue($completed);
        self::assertSame(1337, $currentI);
    }

    public function testUnwrapObservableFromPromiseOnError(): void
    {
        $completed        = false;
        $currentException = null;

        $exception = new Exception('boom');

        unwrapObservableFromPromise(
            resolve(
                Promise::toObservable(
                    reject(
                        $exception
                    )
                )
            )
        )->subscribe(
            null,
            static function ($exception) use (&$currentException): void {
                $currentException = $exception;
            },
            static function () use (&$completed): void {
                $completed = true;
            }
        );

        self::assertFalse($completed);
        self::assertSame($exception, $currentException);
    }

    public function testUnwrapObservableFromPromiseDoesNotSwallowException(): void
    {
        self::expectException(Throwable::class);
        self::expectExceptionMessage('boom');

        unwrapObservableFromPromise(
            resolve(
                Observable::of(1)
            )
        )->subscribe(
            static function (): void {
                /** @phpstan-ignore-next-line */
                throw new Exception('boom');
            }
        );
    }
}
