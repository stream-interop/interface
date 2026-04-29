<?php
declare(strict_types=1);

namespace StreamInterop\Interface;

/**
 * [_ImmutableStream_][] marks the stream as enforcing immutability constraints
 * on the encapsulated resource.
 *
 * - Directives:
 *
 *     - Implementations MUST adhere to all [_ReadonlyStream_][] restrictions.
 *
 *     - Implementations MUST NOT allow non-idempotent reading of the
 *       encapsulated resource, whether by implementing [_ReadableStream_][] or
 *       by some other means.
 *
 *     - Implementations MUST NOT expose the state of the encapsulated
 *       resource pointer, whether by implementing [_SeekableStream_][] or by
 *       some other means.
 *
 *     - Implementations MUST NOT allow mutation of the `$metadata` property.
 *
 * - Notes:
 *
 *     - This marker interface indicates the implementation attempts to enforce
 *       the above constraints on the encapsulated resource.
 */
interface ImmutableStream extends ReadonlyStream, StringableStream
{
}
