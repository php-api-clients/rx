<?php declare(strict_types=1);

namespace ApiClients\Tests\Tools\Rx;

use function ApiClients\Tools\Rx\observableFromArray;
use ApiClients\Tools\TestUtilities\TestCase;

/**
 * @internal
 */
final class FunctionsObservavleFromArrayTest extends TestCase
{
    public function testObservableFromArray(): void
    {
        $range = \range(0, 1337);

        $completed = false;
        $currentI = null;

        observableFromArray($range)->subscribe(
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
}
