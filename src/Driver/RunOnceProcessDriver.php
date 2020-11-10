<?php

namespace Resgen\Common\Proc\Driver;

use Resgen\Common\Proc\ProcessControl;
use Resgen\Common\Proc\EscapeProcessException;

class RunOnceProcessDriver implements ProcessControl
{

    public function check(): void
    {
        throw new EscapeProcessException('Run once proc control driver configured.');
    }

}