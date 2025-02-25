<?php
declare(strict_types=1);

namespace StreamInterop\Interface;

/**
 * This marker interface indicates the implementation attempts to enforce these
 * constraints on the encapsulated resource:
 *
 * - The implementation MUST adhere to all _ReadonlyStream_ restrictions.
 *
 * - The implementation MUST NOT allow partial reading of the encapsulated
 *   resource, whether by implementing  _ReadableStream_ or by some other
 *   means.
 *
 * - The implementation MUST NOT expose the state of the encapsulated resource
 *   pointer, whether by implementing _SeekableStream_ or by some other means.
 */
interface ImmutableStream extends ReadonlyStream, StringableStream
{
}
