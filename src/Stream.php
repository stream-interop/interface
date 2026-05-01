<?php
declare(strict_types=1);

namespace StreamInterop\Interface;

/**
 * [_Stream_][] is a common baseline for streams.
 *
 * - Notes:
 *
 *     - **The `$metadata` property is expected to change dynamically.** That
 *       is, as the encapsulated resource gets read from and written to,
 *       the metadata for that resource is likely to change. Thus, the
 *       `$metadata` property value is expected to change along with it.
 *       In practical terms, this likely means a [`stream_get_meta_data()`][]
 *       call on each access of `$metadata`.
 *
 *     - **There are no `isReadable()`, etc. methods.** If necessary, such
 *       functionality can be determined by typehinting against the interface,
 *       or by checking `instanceof`, etc.
 *
 *     - **The encapsulated resource is not exposed publicly here.** The
 *       encapsulated resource may remain private or protected. See the
 *       [_ResourceStream_][] interface for details on making the encapsulated
 *       resource publicly accessible.
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
     *     - Implementations MUST provide the most-recent metadata for the
     *       encapsulated resource at the moment of property access; if the
     *       encapsulated resource is closed, implementations MUST return an
     *       empty array.
     *
     *     - Implementations MUST NOT allow `$metadata` to be publicly settable,
     *       either as a property or via property hook or method.
     *
     * @var metadata_array
     */
    public array $metadata { get; }

    /**
     * Returns `true` if the encapsulated resource has been closed, or `false` if not.
     */
    public function isClosed() : bool;

    /**
     * Returns `true` if the encapsulated resource is still open, or `false` if not.
     */
    public function isOpen() : bool;
}
