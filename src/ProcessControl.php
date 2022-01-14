<?php

namespace Resgen\Common\Proc;

interface ProcessControl {

	/** Call to check for signal being caught */
	public function check() : void;

	/** Calling this causes signals to throw a SignalException when the signal arrives */
	public function throwSignalExceptions(bool $throw = true) : void;

}
