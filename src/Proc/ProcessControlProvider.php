<?php

namespace Resgen\Common\Proc;

use Exception;
use Illuminate\Support\ServiceProvider;
use Resgen\Common\Proc\Driver as Driver;

class ProcessControlProvider extends ServiceProvider
{

    private $defaultDrivers = [
        'ttl'          => Driver\TtlProcessDriver::class,
        'ttl_hardexit' => Driver\TtlInstantKillProcessDriver::class,
        'runonce'      => Driver\RunOnceProcessDriver::class,
        'keepalive'    => Driver\KeepAliveProcessDriver::class,
    ];

    protected $customDrivers = [];

    public function register()
    {
        $drivers = array_merge($this->customDrivers, $this->defaultDrivers);

        $this->app->singleton(ProcessControl::class, function() use ($drivers) {
            $selectedDriver = env('LUMEN_PROC_DRIVER', false);
            $availableDriverKeys = implode(',', array_keys($drivers));

            if (!$selectedDriver) {
                throw new Exception(sprintf(
                    'env.LUMEN_PROC_DRIVER must be set to use proc control. Available drivers: %s',
                    $availableDriverKeys
                ));
            }

            $driver = $drivers[$selectedDriver] ?? false;

            if (!$driver) {
                throw new Exception(sprintf(
                    'Invalid proc ctrl driver: %s. Available drivers: %s',
                    $selectedDriver,
                    $availableDriverKeys
                ));
            }

            return app($driver);
        });
    }

}