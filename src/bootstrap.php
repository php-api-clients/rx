<?php declare(strict_types=1);

use Rx\Scheduler;

Scheduler::setDefaultFactory(function () {
    return new Scheduler\ImmediateScheduler();
});
