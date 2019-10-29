<?php

namespace Resgen\Common\Proc;

use Mockery as m;
use Resgen\Common\Proc\Driver\SignalHandler;
use Resgen\Common\Proc\Driver\TtlProcessDriver;

class ProcessControlUnitTest extends \Resgen\TestCase
{

    public function tearDown(): void {
        parent::tearDown();
        putenv('LUMEN_PROC_DRIVER');
        putenv('LUMEN_PROC_TTL');
    }

    /**
     * @expectedException \Resgen\Common\Proc\EscapeProcessException
     */
    public function test_TtlDriver_Should_Respect_InturuptSignals()
    {
        // given
        putenv('LUMEN_PROC_DRIVER=ttl');
        putenv('LUMEN_PROC_TTL=100');

        app()->register(ProcessControlProvider::class);
        $sut = app(ProcessControl::class);

        // when
        $sut->sigHandle();
        $sut->check();
    }

    /**
     * @expectedException \Resgen\Common\Proc\EscapeProcessException
     */
    public function test_RunOnceDriver_Should_Exit_Immediately()
    {
        // given
        putenv('LUMEN_PROC_DRIVER=runonce');
        app()->register(ProcessControlProvider::class);

        // when
        $procCtl = app(ProcessControl::class);

        // then
        $procCtl->check();
    }

    /**
     * @expectedException \Resgen\Common\Proc\EscapeProcessException
     */
    public function test_TtlDriver_Should_Exit_After_Sleep2_WhenTTL_Is1()
    {
        // given
        putenv('LUMEN_PROC_DRIVER=ttl');
        putenv('LUMEN_PROC_TTL=1');
        app()->register(ProcessControlProvider::class);

        // when
        $procCtl = app(ProcessControl::class);

        $procCtl->check();

        sleep(2);

        // then
        $procCtl->check();
    }

    /**
     * @expectedException \Exception
     */
    public function test_ProcProvider_Should_ThrowException_WhenDriver_Not_Found()
    {
        // given
        putenv('LUMEN_PROC_DRIVER=asdf');

        app()->register(ProcessControlProvider::class);

        // when
        app(ProcessControl::class);
    }

    /**
     * @expectedException \Exception
     */
    public function test_ProcProvider_Should_ThrowException_WhenProvided_ButNot_Configured() {
        // given
        putenv('LUMEN_PROC_DRIVER');

        app()->register(ProcessControlProvider::class);

        // when
        app(ProcessControl::class);
    }

}
