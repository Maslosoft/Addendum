<?php

namespace Maslosoft\Addendum\Exceptions;

use RuntimeException;

/**
 * Cache rebuilding was stopped before PHP's execution-time limit.
 */
class TimeoutException extends RuntimeException
{
}
