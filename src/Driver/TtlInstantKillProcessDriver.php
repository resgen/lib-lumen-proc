<?php

namespace Resgen\Common\Proc\Driver;

use Log;
use Resgen\Common\Proc\ProcessControl;
use Resgen\Common\Proc\EscapeProcessException;

class TtlInstantKillProcessDriver extends TtlProcessDriver implements ProcessControl
{

    public function sigHandle()
    {
        $this->log->info('Interrupt signal recieved. Hard exiting.');

        $this->interrupt = true;

        throw new EscapeProcessException('Signal recieved, exiting.');
    }

}