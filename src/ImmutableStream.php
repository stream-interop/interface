<?php
declare(strict_types=1);

namespace StreamInterop\Interface;

/**
 * This marker interface indicates the implementation attempts to enforce these
 * constraints on the encapsulated resource:
 *
 * - The implementation MUST adhere to all ReadonlyStream restrictions.
 *
 * - The implementation MUST NOT allow non-idempotent reading of the
 *   encapsulated resource, whether by implementing  ReadableStream or by some
 *   other means.
 *
 * - The implementation MUST NOT expose the state of the encapsulated resource
 *   pointer, whether by implementing SeekableStream or by some other means.
 *
 * - The implementation MUST NOT allow mutation of the `$metadata` property.
 */
interface ImmutableStream extends ReadonlyStream, StringableStream
{
}
