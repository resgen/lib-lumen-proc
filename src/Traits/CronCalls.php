<?php

namespace Resgen\Common\Proc\Traits;

use Resgen\Common\Proc\CronScheduler;

trait CronCalls
{
    /**
     * Creates a CronScheduler object if needed, records the call context with it and
     * returns the CronScheduler object which will capture the next call.  Returns $this
     * so that subsequent invocation appears to be the same as $this for call recording.
     *
     * @noinspection PhpDocSignatureInspection
     *
     * @return $this
     */
    protected function cron() : CronScheduler
    {
        app(CronScheduler::class)->setRecorderContext($this);

        return app(CronScheduler::class);
    }
}
