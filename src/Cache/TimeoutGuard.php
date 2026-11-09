<?php

namespace Maslosoft\Addendum\Cache;

use Maslosoft\Addendum\Exceptions\TimeoutException;

/**
 * Prevent starting another cache rebuild/write close to PHP's execution timeout.
 */
final class TimeoutGuard
{
    public static function check(): void
    {
        $limit = (int) ini_get('max_execution_time');
        if ($limit <= 0)
        {
            // 0 means unlimited, as is common for CLI.
            return;
        }

        $startedAt = $_SERVER['REQUEST_TIME_FLOAT'] ?? $_SERVER['REQUEST_TIME'] ?? null;
        if (!is_numeric($startedAt))
        {
            return;
        }

        $remaining = $limit - (microtime(true) - (float) $startedAt);
        $reserve = min(5.0, $limit * 0.2);

        if ($remaining <= $reserve)
        {
            throw new TimeoutException(sprintf(
                'Addendum cache rebuild stopped near PHP execution timeout (limit: %d seconds, estimated %.1f seconds remaining). Please retry.',
                $limit,
                max(0.0, $remaining)
            ));
        }
    }
}
