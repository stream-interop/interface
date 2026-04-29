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
     *     - Implementations MUST ensure `$resource` is a `resource of type (stream)`;
     *       for example, as determined by [`get_resource_type()`][].
     *
     *     - Implementations SHOULD NOT allow `$resource` to be publicly settable,
     *       either as a property or via property hook or method.
     *
     * @var resource
     */
    public mixed $resource { get; }
}
