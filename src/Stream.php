<?php
declare(strict_types=1);

namespace StreamInterop\Interface;

/**
 * @phpstan-import-type stream_metadata_array from StreamTypeAliases
 */
interface Stream
{
    /**
     * Represents the metadata for the encapsulated resource as if by
     * stream_get_meta_data().
     *
     * It MUST provide the most-recent metadata for the encapsulated resource
     * at the moment of property access.
     *
     * It MUST NOT be publicly settable, either as a property or via property
     * hook or method.
     *
     * @var stream_metadata_array
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
