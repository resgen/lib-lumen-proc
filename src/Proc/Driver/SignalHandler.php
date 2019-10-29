<?php

namespace Resgen\Common\Proc\Driver;

use Log;

class SignalHandler
{

    public function bind($scope, $callback)
    {
        if (method_exists('pcntl_async_signals')) {
            pcntl_async_signals(true);
            pcntl_signal(SIGHUP,  [$scope, $callback]);
            pcntl_signal(SIGTERM, [$scope, $callback]);
            pcntl_signal(SIGQUIT, [$scope, $callback]); // Ctrl-\
            pcntl_signal(SIGINT,  [$scope, $callback]); // Ctrl-C
            pcntl_signal(SIGTSTP, [$scope, $callback]);
        } else {
            Log::warning('pcntl methods not found');
        }
    }

}