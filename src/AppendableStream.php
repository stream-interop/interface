<?php
declare(strict_types=1);

namespace StreamInterop\Interface;

use RuntimeException;
use Stringable;

/**
 * If the encapsulated resource is not seekable at the time it becomes
 * available to the AppendableStream, implementations MUST throw LogicException
 * (or an extension thereof).
 *
 * If the encapsulated resource is not writable at the time it becomes
 * available to the AppendableStream, implementations MUST throw LogicException
 * (or an extension thereof).
 */
interface AppendableStream extends Stream
{
    /**
     * Moves the pointer to the end of the stream, as if by fseek(); then,
     * writes $data, returning the number of bytes written, as if by fwrite().
     *
     * @throws RuntimeException on failure.
     */
    public function append(string|Stringable $data) : int;
}
