<?php
declare(strict_types=1);

namespace StreamInterop\Interface;

/**
 * [_ReadonlyStream_][] marks the stream as enforcing readonly constraints on
 * the encapsulated resource.
 *
 * - Directives:
 *
 *     - Implementations MUST open the encapsulated resource inside the
 *       [_ReadonlyStream_][].
 *
 *     - Implementations MUST open the encapsulated resource as `php://input`
 *       or `php://memory`.
 *
 *     - Implementations MAY open the encapsulated resource in a mode that
 *       allows writing (`rb+`, `w+`, etc.) to allow initialization.
 *
 *     - Implementations MAY initialize the encapsulated resource after
 *       opening (e.g., by copying a constructor argument to the encapsulated
 *       resource).
 *
 *     - Implementations MUST NOT modify, or allow modification of, the
 *       encapsulated resource content after initialization, whether by
 *       implementing [_WritableStream_][] or by any other means.
 *
 *     - Implementations MUST NOT expose the encapsulated resource, whether
 *       by implementing [_ResourceStream_][] or by any other means.
 *
 * - Notes:
 *
 *     - This marker interface indicates the implementation attempts to enforce
 *       the above constraints on the encapsulated resource.
 */
interface ReadonlyStream extends Stream
{
}
