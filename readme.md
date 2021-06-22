Lumen Process Control Drivers
=============================

Process control for running artisan commands as a deamon. Respects all sigterm commands sent. Includes TTL driver and RunOnce drivers. This should work for Laravel too, but its only been tested with lumen.

## Why?

When running artisan commands as a background deamon process, the ProcessControl will ensure that SIGTERMs are respected. This is especially important when running artisan commands as the docker entrypoint. It will respect docker's kill signal and give you a chance to finish work before exiting. 


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


## Available Drivers

#### TtlProcessDriver
ENV name: `ttl`. 

Runs for N number of seconds. Defaults to 300s runtime. Logs a heart beat message every ~6 seconds. You can adjust the runtime by setting the `LUMEN_PROC_TTL=1000s`. Will exit if a SIGTERM is sent. Intended to be used with a process supervisor like runit, supervisord or inside a docker orchestration env like kubernetes.

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

use Illuminate\Console\Command;
use Resgen\Common\Proc\ProcessControl;
use Resgen\Common\Proc\EscapeProcessException;

class ExampleCommand extends Command
{
    protected $name = 'example';
    protected $signature = 'example:run';

    // will run until ProcessControl escapes
    public function handle(ProcessControl $proc)
    {
        try {
            do {
                // do some work; consume a queue, check a FS, ect...

                sleep(1);

                $proc->check();

            } while(true);

        } catch (EscapeProcessException $e) {}

        // finish work then exit
    }

}
```

