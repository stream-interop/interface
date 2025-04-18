<?php
declare(strict_types=1);

namespace StreamInterop\Interface;

interface ClosableStream extends Stream
{
    /**
     * Closes the encapsulated resource as if by fclose(), pclose(), etc.
     *
     * @throws StreamThrowable on failure.
     */
    public function close() : void;
}
