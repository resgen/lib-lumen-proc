<?php

namespace Resgen;

use Laravel\Lumen\Application;
use Laravel\Lumen\Testing\TestCase as LumenTestCase;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

abstract class TestCase extends LumenTestCase {
	use MockeryPHPUnitIntegration;

	public function createApplication() : Application {
		$app = new Application();
		$app->withFacades();

		return $app;
	}

}
