<?php

declare(strict_types=1);

namespace ApiClients\Tests\Tools\Rx;

use ApiClients\Tools\TestUtilities\TestCase;

use function ApiClients\Tools\Rx\observableFromArray;
use function range;

/**
 * @internal
 */
final class FunctionsObservableFromArrayTest extends TestCase
{
    public function testObservableFromArray(): void
    {
        $range = range(0, 1337);

        $completed = false;
        $currentI  = null;

        observableFromArray($range)->subscribe(
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
}
