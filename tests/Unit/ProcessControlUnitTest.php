<?php

namespace Resgen\Common\Proc;

use Resgen\TestCase;

class ProcessControlUnitTest extends TestCase
{

    public function tearDown() : void
    {
        parent::tearDown();
        putenv('LUMEN_PROC_DRIVER');
        putenv('LUMEN_PROC_TTL');
    }

    public function test_TtlDriver_Should_Respect_InturuptSignals()
    {
        $this->expectException(EscapeProcessException::class);
        // given
        putenv('LUMEN_PROC_DRIVER=ttl');
        putenv('LUMEN_PROC_TTL=100');

        app()->register(ProcessControlProvider::class);
        $sut = app(ProcessControl::class);

        // when
        $sut->sigHandle(2, 0);
        $sut->check();
    }

    public function test_Ttl_HardExitDriver_Should_Exit_Immediately_OnInturuptSignals()
    {
        $this->expectException(EscapeProcessException::class);
        // given
        putenv('LUMEN_PROC_DRIVER=ttl_hardexit');
        putenv('LUMEN_PROC_TTL=1000');

        app()->register(ProcessControlProvider::class);
        $sut = app(ProcessControl::class);

        // when
        $sut->sigHandle(2, 0);
    }

    public function test_RunOnceDriver_Should_Exit_Immediately()
    {
        $this->expectException(EscapeProcessException::class);
        // given
        putenv('LUMEN_PROC_DRIVER=runonce');
        app()->register(ProcessControlProvider::class);

        // when
        $procCtl = app(ProcessControl::class);

        // then
        $procCtl->check();
    }

    public function test_TtlDriver_Should_Exit_After_Sleep2_WhenTTL_Is1()
    {
        $this->expectException(EscapeProcessException::class);
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

    public function test_ProcProvider_Should_ThrowException_WhenDriver_Not_Found()
    {
        $this->expectException(\Exception::class);
        // given
        putenv('LUMEN_PROC_DRIVER=asdf');

        app()->register(ProcessControlProvider::class);

        // when
        app(ProcessControl::class);
    }

    public function test_ProcProvider_Should_ThrowException_WhenProvided_ButNot_Configured()
    {
        $this->expectException(\Exception::class);
        // given
        putenv('LUMEN_PROC_DRIVER');

        app()->register(ProcessControlProvider::class);

        // when
        app(ProcessControl::class);
    }

}
