<?php

namespace Resgen\Common\Proc;

use Illuminate\Console\Command;
use Resgen\Common\Proc\Traits\CronCalls;

abstract class LoopingCommand extends Command
{
    use CronCalls;

    /**
     * Called by the framework will run until ProcessControl escapes
     *
     * @noinspection PhpRedundantCatchClauseInspection
     */
    public function handle(ProcessControl $proc)
    {
        try {
            if ($this->init() === false) {
                return;
            }

            while (true) {
                // Poll the cron scheduler for any due tasks
                $this->cron()->poll();

                // Do some slice of work
                $sleep = $this->loop();

                // Check on signals
                $proc->check();

                // Sleep if requested
                if ($sleep) {
                    sleep($sleep);
                }
            }
        } catch (SignalException|EscapeProcessException) {
        }

        // Cleanup before exit
        $this->exiting();
    }

    /**
     * Called at the beginning prior to the loop
     *
     * @return bool Return false to exit the command
     */
    protected function init() { return true; }

    /**
     * Called successfully until the process is terminated
     *
     * @return int    Seconds to sleep() before next loop() is called, returning 0 stays awake
     */
    protected function loop() { return 1; }

    /** Called just prior to normal process termination */
    protected function exiting() { }
}
