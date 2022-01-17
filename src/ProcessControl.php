<?php

namespace Resgen\Common\Proc;

interface ProcessControl
{

    /** Call to check for signal being caught */
    public function check() : void;
}
