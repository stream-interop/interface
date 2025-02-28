# Stream-Interop Interface Package

[![PDS Skeleton](https://img.shields.io/badge/pds-skeleton-blue.svg?style=flat-square)](https://github.com/php-pds/skeleton)
[![PDS Composer Script Names](https://img.shields.io/badge/pds-composer--script--names-blue?style=flat-square)](https://github.com/php-pds/composer-script-names)

Stream-Interop publishes a standard set of interoperable interfaces providing a more object-oriented approach to encapsulating and interacting with stream resources in PHP 8.4+. It reflects, refines, and reconciles the common practices identified within [several pre-existing projects][README-RESEARCH.md].

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD", "SHOULD NOT", "RECOMMENDED",  "MAY", and "OPTIONAL" in this document are to be interpreted as described in [BCP 14][] ([RFC 2119][], [RFC 8174][]).

This package attempts to adhere to the [Package Development Standards](https://php-pds.com/) approach to [naming and versioning](https://php-pds.com/#naming-and-versioning).

## Interfaces

Stream-Interop defines separate interfaces for various affordances around stream resources so that (1) implementations can advertise well-tailored affordances, and (2) consumers can typehint to the specific affordances they require for specific situations:

- [_Stream_][] is a common baseline for streams.
- [_ResourceStream_][] affords direct access to the encapsulated resource.
- [_ClosableStream_][] affords closing the stream.
- [_SizableStream_][] affords getting the full length of the stream in bytes.
- [_ReadableStream_][] affords non-idempotent reading from the stream.
- [_SeekableStream_][] affords moving the stream pointer.
- [_StringableStream_][] affords idempotent reading from the stream.
- [_WritableStream_][] affords writing to the stream.

Stream-Interop also defines these marker interfaces:

- [_ReadonlyStream_][] marks the stream as enforcing readonly constraints on the encapsulated resource.
- [_ImmutableStream_][] marks the stream as enforcing immutability constraints on the encapsulated resource.

Finally, Stream-Interop defines an interface of [_StreamTypeAliases_][] to aid static analysis with PHPStan.

### _Stream_

The [_Stream_][] interface defines these properties common to all streams:

- `public metadata_array $metadata { get; }`
    - Represents the metadata for the encapsulated resource as if by [`stream_get_meta_data()`][].
    - The implementation MUST provide the most-recent metadata for the encapsulated resource at the moment of property access; if the encapsulated resource is closed, the implementation MUST return an empty array.
    - The implementation MUST NOT allow `$metadata` to be publicly settable, either as a property or via property hook or method.

It also defines these methods common to all streams:

- `public function isClosed() : bool`
    - Returns `true` if the encapsulated resource has been closed, or `false` if not.

- `public function isOpen() : bool`
    - Returns `true` if the encapsulated resource is still open, or `false` if not.

Notes:

- **The `$metadata` property is expected change dynamically.** That is, as the encapsulated resource gets read from and written to, the metadata for that resource is likely to change. Thus, the `$metadata` property value is expected to change along with it. In practical terms, this likely means a [`stream_get_meta_data()`][] call on each access of `$metadata`.

- **There are no `isReadable()`, etc. methods.** If necessary, such functionality can be determined by typehinting against the interface, or by checking `instanceof`, etc.

- **The encapsulated resource is not exposed publicly here.** The encapsulated resource MAY remain private or protected. See the _ResourceStream_ interface below for details on making the encapsulated resource publicly accessible.

### _ResourceStream_

The [_ResourceStream_][] interface extends [_Stream_][] to define a property to allow public access to the encapsulated resource:

- `public resource $resource { get; }`
    - Represents the resource as if opened by [`fopen()`][], [`fsockopen()`][], [`popen()`][], etc.
    - The implementation MUST ensure `$resource` is a `resource of type (stream)`; for example, as determined by `get_resource_type()`.
    - The implementation SHOULD NOT allow `$resource` to be publicly settable, either as a property or via property hook or method.

Notes:

- **Not all [_Stream_][] implementations need to expose the encapsulated resource.** Exposing the resource gives full control over it to consumers, who can then manipulate it however they like (e.g. close it, move the pointer, and so on). However, having access to the resource may be necessary for some consumers.

- **Some [_Stream_][] implementations might not encapsulate a resource.** Although a resource is the most common data source for a stream, other data sources MAY be used, in which cases [_ResourceStream_][] implementation is neither appropriate nor necessary.

### _ClosableStream_

The [_ClosableStream_][] interface extends [_Stream_][] to define this method:

- `public function close() : void`
    - Closes the encapsulated resource as if by [`fclose()`][], [`pclose()`][], etc.
    - The implementation MUST throw [_RuntimeException_][] (or an extension thereof) on failure.

The implementation MAY close the encapsulated resource internally without affording [_ClosableStream_][].

Notes:

- **Not all [_Stream_][] implementations need to be closable.** It may be important for resource closing to be handled by a separate service or authority, and not be closable by [_Stream_][] consumers.


### _SizableStream_

The [_SizableStream_][] interface extends [_Stream_][] to define this method:

- `public function getSize() : ?int<0,max>`
    - Returns the length of the encapsulated resource in bytes as if by the [`fstat()`][] value for `size`, or `null` if indeterminate or on error.

The implementation MAY get the size of the encapsulated resource internally without affording [_SizableStream_][].

Notes:

- **Not all [_Stream_][] implementations need to be sizable.** Some encapsulated resources may be unable to report a size; for example, remote or write-only resources.

### _ReadableStream_

The [_ReadableStream_][] interface extends [_Stream_][] to afford these methods for non-idempotent reading from a resource:

- `public function eof() : bool`
    - Tests for end-of-file on the encapsulated resource as if by [`feof()`][].
    - The implementation MUST throw [_RuntimeException_][] (or an extension thereof) on failure.

- `public function getContents() : string`
    - Returns the remaining contents of the resource from the current pointer position as if by [`stream_get_contents()`][].
    - The implementation MUST throw [_RuntimeException_][] (or an extension thereof) on failure.

- `public function read(int<1,max> $length) : string`
    - Returns up to `$length` bytes from the encapsulated resource as if by [`fread()`][].
    - The implementation MUST throw [_RuntimeException_][] (or an extension thereof) on failure.

If the encapsulated resource is not readable at the time it becomes available to the [_ReadableStream_][], the implementation MUST throw [_LogicException_][] (or an extension thereof).

The implementation MAY read from the encapsulated resource internally without affording [_ReadableStream_][].

Notes:

- **The `eof()` method is on [_ReadableStream_][], not [_Stream_][] or [_SeekableStream_][].** End-of-file is determined as a function of reading past the end of the file, not as of seeking to the end of the file. Cf. <https://www.php.net/manual/en/function.feof.php#122925>.

- **These methods are non-idempotent.** They may return different results on repeated sequential calls, and may have side effects (e.g., changing the position of the pointer.)

### _SeekableStream_

The [_SeekableStream_][] interface extends [_Stream_][] to define methods for moving the stream pointer position back and forth:

- `public function rewind() : void`
    - Moves the stream pointer position to the beginning of the stream as if by [`rewind()`][].
    - The implementation MUST throw [_RuntimeException_][] (or an extension thereof) on failure.

- `public function seek(int $offset, int $whence = SEEK_SET) : void`
    - Moves the stream pointer position to the `$offset` as if by [`fseek()`][].
    - The implementation MUST throw [_RuntimeException_][] (or an extension thereof) on failure.

- `public function tell() : int`
    - Returns the current stream pointer position as if by [`ftell()`][].
    - The implementation MUST throw [_RuntimeException_][] (or an extension thereof) on failure.

If the encapsulated resource is not seekable at the time it becomes available to the [_SeekableStream_][], the implementation MUST throw [_LogicException_][] (or an extension thereof).

### _StringableStream_

The [_StringableStream_][] interface extends [_Stream_][] to afford idempotent reading from the encaspulated resource:

- `public function __toString() : string`
    - Returns the entire contents of the encapsulated resource as if by [`rewind()`][]ing before returning [`stream_get_contents()`][].
    - After reading, The implementation MUST reposition the encapsulated resource pointer to its initial location.
    - The implementation MUST throw [_RuntimeException_][] (or an extension thereof) on failure.

- `public function subString(int $offset, ?int $length) : string`
    - Returns a string from the encapsulated resource as if by [`fseek()`][]ing before reading.
    - If the `$offset` is negative, the implementation MUST begin reading at that many bytes from the end of the stream; otherwise, the implementation MUST begin reading at that many bytes from the start of the stream.
    - If the `$length` is null, the implementation MUST return all remaining bytes from the stream; otherwise, the implementation MUST return up to that many bytes from the stream.
    - After reading, the implementation MUST reposition the encapsulated resource pointer to its initial location.
    - The implementation MUST throw [_RuntimeException_][] (or an extension thereof) on failure.

If the encapsulated resource is not readable at the time it becomes available to the [_StringableStream_][], the implementation MUST throw [_LogicException_][] (or an extension thereof).

If the encapsulated resource is not seekable at the time it becomes available to the [_StringableStream_][], the implementation MUST throw [_LogicException_][] (or an extension thereof).

The implementation MAY convert all or part of the encapsulated resource to a string internally without affording [_StringableStream_][].

Notes:

- **These methods are idempotent.** Repeated sequential calls to an unchanged resource will return the exact same result, without exposing side effects (such as the pointer position being changed).

### _WritableStream_

The [_WritableStream_][] interface extends [_Stream_][] to define a single method for writing to a resource:

- `public function write(string|Stringable $data) : int`
    - Writes `$data` starting at the current stream pointer position, returning the number of bytes written, as if by [`fwrite()`][].
    - The implementation MUST throw [_RuntimeException_][] on failure.

If the encapsulated resource is not writable at the time it becomes available to the [_WritableStream_][], the implementation MUST throw [_LogicException_][] (or an extension thereof).

The implementation MAY write to the encapsulated resource internally without affording [_WritableStream_][].

### _ReadonlyStream_

The [_ReadonlyStream_][] marker interface indicates the implementation attempts to enforce these constraints:

- The implementation MUST open the encapsulated resource inside the [_ReadonlyStream_][].

- The implementation MUST open the encapsulated resource as `php://input` or `php://memory`.

- The implementation MAY open the encapsulated resource in a mode that allows writing (`rb+`, `w+`, etc.) to allow initialization.

- The implementation MAY initialize the encapsulated resource after opening (e.g., by copying a constructor argument to the encapsulated resource).

- The implementation MUST NOT modify, or allow modification of, the encapsulated resource content after initialization, whether by implementing [_WritableStream_][] or by some other means.

- The implementation MUST NOT expose the encapsulated resource, whether by implementing [_ResourceStream_][] or by some other means.

- The implementation MAY allow closing of the encapsulated resource, whether by implementing [_ClosableStream_][] or by some other means.

Notes:

- **The readonly constraints are necessarily strict.** Whereas readonly on scalar and array properties can be implemented relatively easily, readonly on a resource property is more difficult. The encapsulated resource, including both its content and its pointer, must be inaccessible from outside the [_ReadonlyStream_][] to ensure it cannot be modified from outside the [_ReadonlyStream_][].

- **_ReadonlyStream_ implementations may be memory-intensive.** This is because they usually have to be initialized with a copy of the original resource, typically a file resource, thereby reading all of it into a `php://memory` resource.

- **`php://input` is natively readonly.** It does not need to be copied to a `php://memory` resource, and does not need to be initialized.

### _ImmutableStream_

The [_ImmutableStream_][] marker interface extends [_ReadonlyStream_][] to indicate the implementation attempts to enforce these constraints:

- The implementation MUST adhere to all [_ReadonlyStream_][] constraints.

- The implementation MUST NOT allow non-idempotent reading of the encapsulated resource, whether by implementing  [_ReadableStream_][] or by some other means.

- The implementation MUST NOT expose the state of the encapsulated resource pointer, whether by implementing [_SeekableStream_][] or by some other means.

- The implementation MUST NOT allow mutation of the `$metadata` property.

Notes:

- **The immutability constraints are necessarily strict.** Immutability of a resource is incompatible with non-idempotent reading; doing so modifies its pointer position, thereby changing its state. This constraint leaves only [_StringableStream_][], [_ClosableStream_][], and [_SizableStream_][] as compatible interfaces.

### _StreamTypeAliases_

The _StreamTypeAliases_ interface defines this custom PHPStan type to assist static analysis:

- `metadata_array` as if by [`stream_get_meta_data()`][]:

    ```
    array{
        timed_out: bool,
        blocked: bool,
        eof: bool,
        unread_bytes: int,
        stream_type: string,
        wrapper_type: string,
        wrapper_data: mixed,
        mode: string,
        seekable: bool,
        uri?: string,
        mediatype?: string,
        base64?: bool
    }
    ```

- `stat_array` as if by [`fstat()`][] or [`stat()`][]:

    ```
    array{
        dev:int<0,max>,
        ino:int<0,max>,
        mode:int<0,max>,
        nlink:int<0,max>,
        uid:int<0,max>,
        gid:int<0,max>,
        rdev:int<0,max>,
        size:int<0,max>,
        atime:int<0,max>,
        mtime:int<0,max>,
        ctime:int<0,max>,
        blksize:int<0,max>,
        blocks:int<0,max>
    }
    ```


## Implementations

Reference implementations are available at <https://github.com/stream-interop/impl>.

Implementations MAY encapsulate a string, or some other kind of data source, instead of a `resource`.

Implementations encapsulating something besides a `resource` MUST behave *as if* they encapsulate a resource.


## Q & A

### What projects were used as reference points for Stream-Interop?

These are the reference projects for developing the above interfaces.

- amphp/byte-stream: https://github.com/amphp/byte-stream
- fzaninotto/streamer: https://github.com/fzaninotto/Streamer
- hoa/stream: https://github.com/hoaproject/Stream
- kraken-php/stream: https://github.com/kraken-php/stream
- psr/http-message: https://github.com/php-fig/http-message/blob/master/src/StreamInterface.php
- react/stream: https://packagist.org/packages/react/stream
- zenstruck/stream: https://github.com/zenstruck/stream

Please see [README-RESEARCH.md][] for more information.

### What about filters?

[Stream filters](https://www.php.net/manual/en/function.stream-filter-register.php) are a powerful aspect of stream resources. However, as they operate on resources directly, creating interfaces for them is out-of-scope for Stream-Interop. Further, none of the projects included in the Stream-Interop research implemented filters, making it difficult to rationalize adding filter interfaces.

Even so, consumers are free to register filters on the resources they injection into a [_Stream_][]. In addition, implementors are free to create filter mechanisms that intercept the input going into a [_WritableStream_][] (e.g. via its `write()` method) or the output coming from a [_ReadableStream_][] (e.g. via its `read()` method).

### Why is there no _Factory_ interface?

The sheer volume of possible combinations of the various interfaces makes it difficult to provide a factory with proper return typehints. Implementors are encouraged to develop their own factories with proper typehinting.

* * *

[_ClosableStream_]: #closablestream
[_ImmutableStream_]: #immutablestream
[_LogicException_]: https://php.net/LogicException
[_ReadableStream_]: #readablestream
[_ReadonlyStream_]: #readonlystream
[_ResourceStream_]: #resourcestream
[_RuntimeException_]: https://php.net/RuntimeException
[_SeekableStream_]: #seekablestream
[_SizableStream_]: #sizablestream
[_Stream_]: #stream
[_StreamTypeAliases_]: #streamtypealiases
[_StringableStream_]: #stringablestream
[_WritableStream_]: #writablestream
[`fclose()`]: https://php.net/fclose
[`feof()`]: https://php.net/feof
[`fopen()`]: https://php.net/fopen
[`fread()`]: https://php.net/fread
[`fseek()`]: https://php.net/fseek
[`fsockopen()`]: https://php.net/fsockopen
[`fstat()`]: https://php.net/fstat
[`ftell()`]: https://php.net/ftell
[`fwrite()`]: https://php.net/fwrite
[`pclose()`]: https://php.net/pclose
[`popen()`]: https://php.net/popen
[`rewind()`]: https://php.net/rewind
[`stat()`]: https://php.net/stat
[`stream_get_contents()`]: https://php.net/stream_get_contents
[`stream_get_meta_data()`]: https://php.net/stream_get_meta_data
[BCP 14]: https://www.rfc-editor.org/info/bcp14
[README-RESEARCH.md]: ./README-RESEARCH.md
[RFC 2119]: https://www.rfc-editor.org/rfc/rfc2119.txt
[RFC 8174]: https://www.rfc-editor.org/rfc/rfc8174.txt
