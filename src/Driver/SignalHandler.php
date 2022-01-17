<?php

namespace Resgen\Common\Proc\Driver;

class SignalHandler {
	use Logs;

	public function bind($scope, $callback) {
		if (function_exists('pcntl_async_signals')) {
			pcntl_async_signals(true);

			pcntl_signal(SIGTERM, [$scope, $callback]);
			pcntl_signal(SIGHUP, [$scope, $callback]);
			pcntl_signal(SIGTSTP, [$scope, $callback]);
			pcntl_signal(SIGUSR1, [$scope, $callback]);
			pcntl_signal(SIGILL, [$scope, $callback]);

			// Ctrl-\
			pcntl_signal(SIGQUIT, [$scope, $callback]);

			// Ctrl-\
			pcntl_signal(SIGABRT, [$scope, $callback]);

			// Ctrl-C
			pcntl_signal(SIGINT, [$scope, $callback]);

			// for apache on debian
			// see: https://github.com/docker-library/php/blob/master/7.4/buster/apache/Dockerfile#L275
			pcntl_signal(SIGWINCH, [$scope, $callback]);

		} else {
			$this->log()->warning('pcntl methods not found. sig handler will not work.');
		}
	}

}
