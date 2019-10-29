<?php

namespace Resgen\Common\Proc;

use Exception;
use Illuminate\Support\ServiceProvider;
use Resgen\Common\Proc\Driver as Driver;

class ProcessControlProvider extends ServiceProvider
{

    private static $drivers = [
        'ttl'       => Driver\TtlProcessDriver::class,
        'runonce'   => Driver\RunOnceProcessDriver::class,
        'keepalive' => Driver\KeepAliveProcessDriver::class,
    ];

    public function register()
    {
        $selectedDriver   = env('LUMEN_PROC_DRIVER', false);
        $availableDrivers = self::$drivers;

        $this->app->singleton(ProcessControl::class, function () use ($selectedDriver, $availableDrivers) {
            if (!$selectedDriver) {
                throw new Exception(sprintf(
                    'env.LUMEN_PROC_DRIVER must be set to use proc control. Available drivers: %s',
                    implode(',', $availableDrivers)
                ));
            }

            $driver = $availableDrivers[$selectedDriver] ?? false;

            if (!$driver) {
                throw new Exception(sprintf(
                    'Invalid proc ctrl driver: %s. Available drivers: %s',
                    $selectedDriver,
                    implode(',', $availableDrivers),
                ));
            }

            return app($driver);
        });
    }

}