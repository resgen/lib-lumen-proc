<?php

namespace Resgen\Common\Proc;

use Mockery\Exception\InvalidOrderException;
use Resgen\Common\Proc\Traits\CallRecorder;

class CronScheduler
{
    use CallRecorder;

    /** @var array[] */
    private $schedule = [];

    /** poll() should be called regularly during idle period loops */
    public function poll()
    {
        foreach ($this->schedule as &$item) {
            if (time() - $item['last'] < $item['every']) {
                continue;
            }

            $item['last'] = time();
            $item['fn']();
        }
    }

    /**
     * @param int $seconds Called to schedule the last recorded call every {$seconds} seconds
     *
     * @return $this
     */
    public function every(int $seconds)
    {
        if ($this->lastRecordedFn === null) {
            throw new InvalidOrderException('No previous function call recorded while trying to schedule call.');
        }

        $this->schedule[] = [
            'fn'    => $this->lastRecordedFn,
            'every' => $seconds,
            'last'  => time(),
        ];

        $this->lastRecordedFn = null;

        return $this;
    }
}
