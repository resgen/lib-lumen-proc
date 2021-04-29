<?php

namespace Resgen\Common\Proc\Driver;

use Log;
use Resgen\Common\Proc\ProcessControl;
use Resgen\Common\Proc\EscapeProcessException;

class TtlProcessDriver extends KeepAliveProcessDriver implements ProcessControl
{

    /** @var int */
    protected $ttlSeconds;

    public function __construct(SignalHandler $signalHandler)
    {
        parent::__construct($signalHandler);

        $this->log = Log::withName('TtlProcessDriver');

        $this->ttlSeconds = env('LUMEN_PROC_TTL', 300);
    }

    public function check(): void
    {
        parent::check();

        if ($this->timeSpentProcessing > $this->ttlSeconds) {
            throw new EscapeProcessException('TTL Exceeded');
        }
    }

}