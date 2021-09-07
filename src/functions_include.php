<?php

declare(strict_types=1);

namespace ApiClients\Tools\Rx;

use function function_exists;

// @codeCoverageIgnoreStart
if (! function_exists('ApiClients\Tools\Rx\unwrapObservableFromPromise')) {
    require __DIR__ . '/functions.php';
}

// @codeCoverageIgnoreEnd
