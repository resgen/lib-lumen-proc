<?php

namespace Resgen\Common\Proc;

class ExampleCommand extends LoopingCommand
{
    /** {@inheritDoc} */
    protected $name = 'resgen';

    /** {@inheritDoc} */
    protected $signature = 'resgen:example';

    /** {@inheritDoc} */
    protected function init()
    {
        // Called prior to the first loop

        $this->cron()
             ->exampleCron('Sample Argument')
             ->every(60);    // Seconds


        // returning `false` will cause the command to stop & exit
    }

    /** {@inheritDoc} */
    protected function loop()
    {
        // Called repeatedly until an exit signal is received or thrown

        return 1;   // Return the number of seconds to sleep/idle until loop() should be called again
    }

    protected function exampleCron(string $arg)
    {
        // Example cron function that would be called as configured
    }

    /** {@inheritDoc} */
    protected function exiting()
    {
        // Called just prior to command handler returns for cleanup
    }
}
