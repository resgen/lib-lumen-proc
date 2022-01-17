<?php

namespace Resgen\Common\Proc\Driver;

use Resgen\Common\Proc\EscapeProcessException;
use Resgen\Common\Proc\ProcessControl;

class RunOnceProcessDriver implements ProcessControl
{

    public function check() : void
    {
        throw new EscapeProcessException('Run once proc control driver configured.');
    }
}
