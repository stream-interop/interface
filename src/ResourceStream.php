<?php
declare(strict_types=1);

namespace StreamInterop\Interface;

/**
 * [_ResourceStream_][] extends [_Stream_][] to afford direct access to the
 * encapsulated resource.
 *
 * - Notes:
 *
 *     - **Not all [_Stream_][] implementations need to expose the encapsulated
 *       resource.** Exposing the resource gives full control over it to
 *       consumers, who can then manipulate it however they like (e.g. close
 *       it, move the pointer, and so on). However, having access to the
 *       resource may be necessary for some consumers.
 *
 *     - **Some [_Stream_][] implementations might not encapsulate a resource.**
 *       Although a resource is the most common data source for a stream, other
 *       data sources may be used, in which cases [_ResourceStream_][]
 *       implementation is neither appropriate nor necessary.
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
