<?php
declare(strict_types=1);

namespace StreamInterop\Interface;

/**
 * This marker interface indicates the implementation attempts to enforce these
 * constraints on the encapsulated resource:
 *
 * - The implementation MUST open the encapsulated resource inside the
 *   _ReadonlyStream_.
 *
 * - The implementation MUST open the encapsulated resource as `php://input` or
 *   `php://memory`.
 *
 * - The implementation MAY open the encapsulated resource in a mode that allows
 *   writing (`rb+`, `w+`, etc.) to allow initialization.
 *
 * - The implementation MAY initialize the encapsulated resource after opening
 *   (e.g., by copying a constructor argument to the encapsulated resource).
 *
 * - The implementation MUST NOT modify, or allow modification of, the
 *   encapsulated resource content after initialization, whether by
 *   implementing _WritableStream_ or by any other means.
 *
 * - The implementation MUST NOT expose the encapsulated resource, whether by
 *   implementing _ResourceStream_ or by any other means.
 */
interface ReadonlyStream extends Stream
{
}
