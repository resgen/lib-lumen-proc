<?php

namespace Resgen\Common\Proc\Driver;


class SignalHandler
{

    public function bind($scope, $callback)
    {
        pcntl_async_signals(true);
        pcntl_signal(SIGHUP,  [$scope, $callback]);
        pcntl_signal(SIGTERM, [$scope, $callback]);
        pcntl_signal(SIGQUIT, [$scope, $callback]); // Ctrl-\
        pcntl_signal(SIGINT,  [$scope, $callback]); // Ctrl-C
        pcntl_signal(SIGTSTP, [$scope, $callback]);
    }

}