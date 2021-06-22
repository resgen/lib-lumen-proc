<?php

namespace Resgen\Common\Proc\Driver;

use Resgen\Common\Proc\ProcessControl;
use Resgen\Common\Proc\EscapeProcessException;

class TtlInstantKillProcessDriver extends TtlProcessDriver implements ProcessControl
{
    use Logs;

    public function sigHandle()
    {
        $this->log()->info('Interrupt signal recieved. Hard exiting.');

        $this->interrupt = true;

        throw new EscapeProcessException('Signal recieved, exiting.');
    }

}