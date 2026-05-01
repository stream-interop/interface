<?php
declare(strict_types=1);

namespace StreamInterop\Interface;

/**
 * [_ImmutableStream_][] is a marker interface that extends [_ReadonlyStream_][]
 * to indicate the implementation attempts to enforce immutability constraints
 * on the encapsulated resource.
 *
 * - Directives:
 *
 *     - Implementations MUST adhere to all [_ReadonlyStream_][] constraints.
 *
 *     - Implementations MUST NOT allow non-idempotent reading of the
 *       encapsulated resource, whether by implementing [_ReadableStream_][] or
 *       by some other means.
 *
 *     - Implementations MUST NOT expose the state of the encapsulated
 *       resource pointer, whether by implementing [_SeekableStream_][] or by
 *       some other means.
 *
 *     - Implementations MUST NOT allow closing of the encapsulated resource
 *       before the [_ImmutableStream_][] is destructed, whether by
 *       implementing [_ClosableStream_][] or by some other means.
 *
 *     - Implementations MUST NOT allow mutation of the `$metadata` property.
 *
 * - Notes:
 *
 *     - **The immutability constraints are necessarily strict.** Immutability
 *       of a resource is incompatible with non-idempotent reading; doing so
 *       modifies its pointer position, thereby changing its state. Likewise,
 *       closing the resource changes its state. These constraints leave only
 *       [_StringableStream_][] and [_SizableStream_][] as compatible
 *       interfaces.
 */
interface ImmutableStream extends ReadonlyStream, StringableStream
{
}
