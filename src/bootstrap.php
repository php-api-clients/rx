<?php

declare(strict_types=1);

use Rx\Scheduler;
use Rx\SchedulerInterface;

Scheduler::setDefaultFactory(static fn (): SchedulerInterface => new Scheduler\ImmediateScheduler());
