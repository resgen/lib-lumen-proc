Lumen Process Control Drivers
=============================

Process control for running artisan commands as a deamon. Respects all sigterm commands sent. Includes TTL driver and RunOnce drivers. This should work for Laravel too, but its only been tested with
lumen.

## Why?

When running artisan commands as a background deamon process, the ProcessControl will ensure that SIGTERMs are respected. This is especially important when running artisan commands as the docker
entrypoint. It will respect docker's kill signal and give you a chance to finish work before exiting.

## Install

With composer:

```bash
composer require resgen/lumen-proc:1.0.*
```

Ensure pcntl_async_signals is enabled for your php install. Example docker alpine RUN:

```bash
RUN docker-php-ext-install pcntl 
```

## Configuration

Add the laravel service provider `Resgen\Common\Proc\ProcessControlProvider` to your app.

Then add the following ENV var with the selected driver:

```bash
LUMEN_PROC_DRIVER=runonce
```

By default, the TTL drivers will log an INFO heart beat every ~5 seconds. You can also disable the heart beat log by doing:

```bash
LUMEN_PROC_HEARTBEAT=disabled
```

You may enable `SignalException` to be thrown asynchronously when a signal is processed, this will happen from wherever your normal code is being executed and gives you a chance to clean things up
gracefully while still responding immediately to signals (or ignore them if appropriate.)

```bash
LUMEN_PROC_THROW_SIGNAL_EXCEPTION=enabled
```

## Available Drivers

#### TtlProcessDriver

ENV name: `ttl`.

Runs for N number of seconds. Defaults to 300s runtime. Logs a heart beat message every ~6 seconds. You can adjust the runtime by setting the `LUMEN_PROC_TTL=1000s`. Will exit if a SIGTERM is sent.
Intended to be used with a process supervisor like runit, supervisord or inside a docker orchestration env like kubernetes.

#### TtlInstantKillProcessDriver

ENV name: `ttl_hardexit`.

Same as TtlProcessDriver, but throws exception as soon as signal is recieved.

#### KeepAliveProcessDriver

ENV name: `keepalive`.

Runs until a SIGTERM signal is sent. Not recommended for production usage. Processes should cycle.

#### RunOnceProcessDriver

ENV name: `runonce`.

Will escape immediately when `check()` is called.

## Example Usage

```php

class ExampleCommand extends LoopingCommand
{
    /** {@inheritDoc} */
    protected $name = 'resgen';

    /** {@inheritDoc} */
    protected $signature = 'resgen:example';

    /** {@inheritDoc} */
    protected function init()
    {
        // Called prior to the first loop
        // returning `false` will cause the command to stop & exit
    }

    /** {@inheritDoc} */
    protected function loop()
    {
        // Called repeatedly until an exit signal is received or thrown

        return 1;   // Return the number of seconds to sleep/idle until loop() should be called again
    }

    /** {@inheritDoc} */
    protected function exiting()
    {
        // Called just prior to command handler returns for cleanup
    }
}
```

## Cron Calls

`LoopingCommand` automatically enables internal cron calls of functions to occur at regular intervals. This check happens each time `loop()` returns control or while the command is sleeping.

### Example Usage

```php

class ExampleCommand extends LoopingCommand
{
    /* ...snip... */
    protected function init()
    {
        $this->cron()
            ->dirCheck('/tmp')
            ->every(5);
            
        $this->cron()
            ->report()
            ->every(60);
    }
    
    /** Check that $dir exists, if not, create it. */
    protected function dirCheck($dir) 
    {
        if(!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
    }
    
    /** Report on the current state every 60 seconds */
    protected function report()
    {
        Log::info('Sample report every 60 seconds.');
    }
    
}
```
