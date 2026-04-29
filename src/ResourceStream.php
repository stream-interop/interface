<?php
declare(strict_types=1);

namespace StreamInterop\Interface;

/**
 * [_ResourceStream_][] affords direct access to the encapsulated resource.
 */
interface ResourceStream extends Stream
{
    /**
     * Represents the resource as if opened by [`fopen()`][], [`fsockopen()`][],
     * [`popen()`][], etc.
     *
     * - Directives:
     *
     *     - The resource MUST be of type (stream); for example, as
     *       determined by [`get_resource_type()`][].
     *
     *     - The resource SHOULD NOT be publicly settable, either as a property
     *       or via property hook or method.
     *
     * @var resource
     */
    public mixed $resource { get; }
}
