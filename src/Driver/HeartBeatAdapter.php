<?php

namespace Resgen\Common\Proc\Driver;

use Log;
use Psr\Log\LoggerInterface;

class HeartBeatAdapter
{

    private LoggerInterface $log;

    public function __construct(LoggerInterface $log)
    {
        $this->log = $log;
    }

    public function info($msg) : void
    {
        if (env('LUMEN_PROC_HEARTBEAT', 'enabled') == 'disabled') {
            return;
        }

        $this->log->info($msg);
    }

}
