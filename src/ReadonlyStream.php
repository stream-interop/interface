<?php
declare(strict_types=1);

namespace StreamInterop\Interface;

/**
 * [_ReadonlyStream_][] is a marker interface that extends [_Stream_][] to
 * indicate the implementation attempts to enforce readonly constraints on
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
 *       implementing [_WritableStream_][] or by some other means.
 *
 *     - Implementations MUST NOT expose the encapsulated resource, whether
 *       by implementing [_ResourceStream_][] or by some other means.
 *
 *     - Implementations MAY allow closing of the encapsulated resource,
 *       whether by implementing [_ClosableStream_][] or by some other means.
 *
 * - Notes:
 *
 *     - **The readonly constraints are necessarily strict.** Whereas readonly
 *       on scalar and array properties can be implemented relatively easily,
 *       readonly on a resource property is more difficult. The encapsulated
 *       resource, including both its content and its pointer, must be
 *       inaccessible from outside the [_ReadonlyStream_][] to ensure it cannot
 *       be modified from outside the [_ReadonlyStream_][].
 *
 *     - **[_ReadonlyStream_][] implementations may be memory-intensive.** This
 *       is because they usually have to be initialized with a copy of the
 *       original resource, typically a file resource, thereby reading all of
 *       it into a `php://memory` resource.
 *
 *     - **`php://input` is natively readonly.** It does not need to be copied
 *       to a `php://memory` resource, and does not need to be initialized.
 */
interface ReadonlyStream extends Stream
{
}
