<?php

namespace Resgen\Common\Proc\Driver;

use Resgen\Common\Proc\ProcessControl;
use Resgen\Common\Proc\EscapeProcessException;

class KeepAliveProcessDriver implements ProcessControl
{
    use Logs;

    /** @var int */
    protected $lastMsgTime;

    /** @var int */
    protected $startTime;

    /** @var int */
    protected $timeSpentProcessing = 0;

    /** @var bool */
    protected $interrupt = false;

    public function __construct(SignalHandler $signalHandler)
    {
        $this->startTime  = microtime(true);
        $signalHandler->bind($this, 'sigHandle');
    }

    public function check(): void
    {
        // processing time in seconds
        $this->timeSpentProcessing = (microtime(true) - $this->startTime);

        if ($this->interrupt) {
            throw new EscapeProcessException('Interrupt signal processed.');
        }

        // Heart beat log every ~5secs
        if (microtime(true) - $this->lastMsgTime > 5) {
            $this->heartBeat()->info(round($this->timeSpentProcessing).' seconds spent processing');
            $this->lastMsgTime = microtime(true);
        }
    }

    public function sigHandle()
    {
        $this->log()->info('Interrupt signal recieved. Starting graceful shutdown.');
        $this->interrupt = true;
    }

}