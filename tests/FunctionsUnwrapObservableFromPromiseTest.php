<?php declare(strict_types=1);

namespace ApiClients\Tests\Tools\Rx;

use function ApiClients\Tools\Rx\unwrapObservableFromPromise;
use ApiClients\Tools\TestUtilities\TestCase;
use Exception;
use function React\Promise\reject;
use function React\Promise\resolve;
use Rx\Observable;
use Rx\React\Promise;

/**
 * @internal
 */
final class FunctionsUnwrapObservableFromPromiseTest extends TestCase
{
    public function testUnwrapObservableFromPromise(): void
    {
        $completed = false;
        $currentI = null;

        unwrapObservableFromPromise(
            resolve(
                Observable::fromArray(\range(0, 1337))
            )
        )->subscribe(
            function ($i) use (&$currentI): void {
                $currentI = $i;
            },
            null,
            function () use (&$completed): void {
                $completed = true;
            }
        );

        self::assertTrue($completed);
        self::assertSame(1337, $currentI);
    }

    public function testUnwrapObservableFromPromiseOnError(): void
    {
        $completed = false;
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
            function ($exception) use (&$currentException): void {
                $currentException = $exception;
            },
            function () use (&$completed): void {
                $completed = true;
            }
        );

        self::assertFalse($completed);
        self::assertSame($exception, $currentException);
    }

    /**
     * @expectedException Exception
     */
    public function testUnwrapObservableFromPromiseDoesNotSwallowException(): void
    {
        unwrapObservableFromPromise(
            resolve(
                Observable::just(1)
            )
        )->subscribe(
            function (): void {
                throw new Exception('boom');
            }
        );
    }
}
