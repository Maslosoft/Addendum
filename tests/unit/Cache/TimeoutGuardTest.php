<?php

namespace Cache;

use Codeception\Test\Unit;
use Maslosoft\Addendum\Cache\TimeoutGuard;
use Maslosoft\Addendum\Exceptions\TimeoutException;

class TimeoutGuardTest extends Unit
{
    public function testAllowsRebuildWhenTimeRemains(): void
    {
        $previousLimit = ini_get('max_execution_time');
        $previousStart = $_SERVER['REQUEST_TIME_FLOAT'] ?? null;

        try
        {
            ini_set('max_execution_time', '30');
            $_SERVER['REQUEST_TIME_FLOAT'] = microtime(true);
            TimeoutGuard::check();
            $this->assertTrue(true);
        }
        finally
        {
            ini_set('max_execution_time', $previousLimit);
            if ($previousStart === null)
            {
                unset($_SERVER['REQUEST_TIME_FLOAT']);
            }
            else
            {
                $_SERVER['REQUEST_TIME_FLOAT'] = $previousStart;
            }
        }
    }

    public function testThrowsBeforeExecutionLimit(): void
    {
        $previousLimit = ini_get('max_execution_time');
        $previousStart = $_SERVER['REQUEST_TIME_FLOAT'] ?? null;

        try
        {
            ini_set('max_execution_time', '30');
            $_SERVER['REQUEST_TIME_FLOAT'] = microtime(true) - 28;
            $this->expectException(TimeoutException::class);
            $this->expectExceptionMessage('Please retry');
            TimeoutGuard::check();
        }
        finally
        {
            ini_set('max_execution_time', $previousLimit);
            if ($previousStart === null)
            {
                unset($_SERVER['REQUEST_TIME_FLOAT']);
            }
            else
            {
                $_SERVER['REQUEST_TIME_FLOAT'] = $previousStart;
            }
        }
    }

    public function testUnlimitedExecutionTimeIsNotBlocked(): void
    {
        $previousLimit = ini_get('max_execution_time');
        $previousStart = $_SERVER['REQUEST_TIME_FLOAT'] ?? null;

        try
        {
            ini_set('max_execution_time', '0');
            $_SERVER['REQUEST_TIME_FLOAT'] = microtime(true) - 3600;
            TimeoutGuard::check();
            $this->assertTrue(true);
        }
        finally
        {
            ini_set('max_execution_time', $previousLimit);
            if ($previousStart === null)
            {
                unset($_SERVER['REQUEST_TIME_FLOAT']);
            }
            else
            {
                $_SERVER['REQUEST_TIME_FLOAT'] = $previousStart;
            }
        }
    }
}
