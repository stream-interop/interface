<?php
declare(strict_types=1);

namespace StreamInterop\Interface;

use Throwable;

/**
 * [_StreamThrowable_][] is a marker interface that extends [_Throwable_][] to
 * indicate an [_Exception_][] is stream-related.
 *
 * It adds no class members.
 */
interface StreamThrowable extends Throwable
{
}
