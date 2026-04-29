<?php
declare(strict_types=1);

namespace StreamInterop\Interface;

/**
 * [_SizableStream_][] affords getting the full length of the stream in bytes.
 */
interface SizableStream extends Stream
{
    /**
     * Returns the length of the encapsulated resource in bytes as if by the
     * [`fstat()`][] value for size, or null if indeterminate or on error.
     *
     * @return ?int<0,max>
     */
    public function getSize() : ?int;
}
