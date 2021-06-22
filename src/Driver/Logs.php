<?php

namespace Resgen\Common\Proc\Driver;

use Log;
use Psr\Log\LoggerInterface;

trait Logs
{

    private function heartBeat(): HeartBeatAdapter
    {
        return new HeartBeatAdapter(
            $this->log()
        );
    }

    private function log(): LoggerInterface
    {
        $name = collect(explode("\\", static::class))
            ->last();

        return Log::withName($name);
    }

}