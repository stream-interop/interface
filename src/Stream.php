<?php
declare(strict_types=1);

namespace StreamInterop\Interface;

/**
 * [_Stream_][] is a common baseline for streams.
 *
 * @phpstan-import-type metadata_array from StreamTypeAliases
 */
interface Stream
{
    /**
     * Represents the metadata for the encapsulated resource as if by
     * [`stream_get_meta_data()`][].
     *
     * - Directives:
     *
     *     - The property MUST provide the most-recent metadata for the
     *       encapsulated resource at the moment of property access.
     *
     *     - The property MUST NOT be publicly settable, either directly or via
     *       property hook or method.
     *
     * @var metadata_array
     */
    public array $metadata { get; }

    /**
     * Returns true if the encapsulated resource has been closed, or false if not.
     */
    public function isClosed() : bool;

    /**
     * Returns true if the encapsulated resource is still open, or false if not.
     */
    public function isOpen() : bool;
}
