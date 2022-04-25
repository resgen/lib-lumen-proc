<?php

namespace Resgen\Common\Proc\Traits;

trait CallRecorder
{

    /** @var object|NULL    The last context set via setRecorderContext() */
    private $lastRecordedContext = null;

    /** @var Closure|NULL    The last recorded function call via __call() */
    private $lastRecordedFn = null;

    /**
     * @param object $context The $this context to set as current context
     *
     * @return $this
     */
    public function setRecorderContext(object $context)
    {
        $this->lastRecordedContext = $context;
        $this->lastRecordedFn      = null;

        return $this;
    }

    /**
     * This captures the call that is made and stores a closure bound to the lastRecorderContext that
     * was set, typically setRecorderContext is set by the CronCalls trait
     *
     * @param string $name The name of the function call
     * @param array  $args The arguments passed to the function call
     *
     * @return $this
     */
    public function __call(string $name, array $args) : self
    {
        $fn = function () use ($name, $args) {
            return $this->$name(...$args);
        };

        $this->lastRecordedFn = $fn->bindTo($this->lastRecordedContext, $this->lastRecordedContext);

        return $this;
    }
}
