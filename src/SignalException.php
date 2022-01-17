<?php

namespace Resgen\Common\Proc;

use Throwable;

class SignalException extends \Exception {
	public int      $signo;

	public mixed    $info;

	public function __construct(int $signo, mixed $info, ?Throwable $previous = NULL) {
		$this->signo    = $signo;
		$this->info     = $info;

		parent::__construct("Posix Signal Caught ($signo)", $signo, $previous);
	}

}
