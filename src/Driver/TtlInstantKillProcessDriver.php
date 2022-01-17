<?php

namespace Resgen\Common\Proc\Driver;

use Resgen\Common\Proc\EscapeProcessException;

class TtlInstantKillProcessDriver extends TtlProcessDriver {
	use Logs;

	public function sigHandle(int $signo, $info) {
		$this->log()->info('Interrupt signal recieved. Hard exiting.');

		$this->interrupt = true;

		throw new EscapeProcessException('Signal recieved, exiting.');
	}

}
