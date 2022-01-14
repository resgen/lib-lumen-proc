<?php

namespace Resgen\Common\Proc;

use JetBrains\PhpStorm\Pure;
use Throwable;

class SignalException extends \Exception {
	public int      $signo;

	public mixed    $info;

	#[Pure]
	public function __construct(int $signo, mixed $info, ?Throwable $previous = NULL) {
		$this->signo    = $signo;
		$this->info     = $info;

		parent::__construct("Posix Signal Caught ($signo)", $signo, $previous);
	}

}
