# Stream Interop Interface Package

[![PDS Skeleton](https://img.shields.io/badge/pds-skeleton-blue.svg?style=flat-square)](https://github.com/php-pds/skeleton)
[![PDS Composer Script Names](https://img.shields.io/badge/pds-composer--script--names-blue?style=flat-square)](https://github.com/php-pds/composer-script-names)

This package provides interoperable interfaces providing a more object-oriented approach to encapsulating and interacting with stream resources in PHP 8.4+. It reflects, refines, and reconciles the common practices identified within several pre-existing projects.

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD", "SHOULD NOT", "RECOMMENDED",  "MAY", and "OPTIONAL" in this document are to be interpreted as described in [BCP 14][] ([RFC 2119][], [RFC 8174][]).

This package attempts to adhere to the [Package Development Standards](https://php-pds.com/) approach to [naming and versioning](https://php-pds.com/#naming-and-versioning).

## Interfaces

StreamInterop defines separate interfaces for various affordances around stream resources so that (1) implementations can advertise well-tailored affordances, and (2) consumers can typehint to the specific affordances they require for specific situations.

- _Stream_ is a common baseline for streams.
- _ResourceStream_ affords direct access to the encapsulated resource.
- _ClosableStream_ affords closing the stream.
- _SizableStream_ affords getting the full length of the stream in bytes.
- _ReadableStream_ affords reading from the stream.
- _SeekableStream_ affords moving the stream pointer.
- _StringableStream_ affords casting the stream to a string.
- _WritableStream_ affords writing to the stream.

Implementations MAY read from a resource internally without affording _ReadableStream_. Likewise, implementations MAY write to a resource internally without affording _WritableStream_, seek on a resource internally without affording _SeekableStream_, and so on.

### _Stream_

The _Stream_ interface defines these properties common to all streams:

- `public MetadataArray $metadata { get; }`
    - Represents the metadata for the encapsulated resource as if by [`stream_get_meta_data()`][].
    - It MUST provide the most-recent metadata for the encapsulated resource at the moment of property access.
    - It MUST NOT be publicly settable, either as a property or via property hook or method.

It also defines these methods common to all streams:

- `public function isClosed() : bool`
    - Returns true if the encapsulated resource has been closed, or false if not.

- `public function isOpen() : bool`
    - Returns true if the encapsulated resource is still open, or false if not.

Finally, it provides this custom PHPStan type to assist static analysis:

- `MetadataArray` as if by [`stream_get_meta_data()`][]:

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

Notes:

- **The `$metadata` property is expected change dynamically.** That is, as the encapsulated resource gets read from and written to, the metadata for that resource is likely to change. Thus, the `$metadata` property value is expected to change along with it. In practical terms, this likely means a `stream_get_meta_data()` call on each access of `$metadata`.

- **There are no `isReadable()`, etc. methods.** If necessary, such functionality can be determined by typehinting against the interface, or by checking `instanceof`, etc.

- **The encapsulated resource is not exposed publicly here.** The encapsulated resource MAY remain private or protected. See the _ResourceStream_ interface below for details on making the encapsulated resource publicly accessible.

### _ResourceStream_

The _ResourceStream_ interface extends _Stream_ to define a property to allow public access to the encapsulated resource:

- `public resource $resource { get; }`
    - Represents the resource as if opened by [`fopen()`][], [`fsockopen()`][], [`popen()`][], etc.
    - It MUST be a `resource of type (stream)`; for example, as determined by `get_resource_type()`.
    - It SHOULD NOT be publicly settable, either as a property or via property hook or method.

Notes:

- **Not all _Stream_ implementations need to expose the encapsulated resource.** Exposing the resource gives full control over it to consumers, who can then manipulate it however they like (e.g. close it, move the pointer, and so on). However, having access to the resource may be necessary for some consumers.

- **Some _Stream_ implementations might not encapsulate a resource.** Although a resource is the most common data source for a stream, other data sources MAY be used, in which cases _ResourceStream_ is neither appropriate nor necessary.

### _ClosableStream_

The _ClosableStream_ interface extends _Stream_ to define this method:

- `public function close() : void`
    - Closes the encapsulated resource as if by [`fclose()`][], [`pclose()`][], etc.
    - Implementations MUST throw [_RuntimeException_][] on failure.

Notes:

- **Not all _Stream_ implementations need to be closable.** It may be important for resource closing to be handled by a separate service or authority, and not be closable by _Stream_ consumers.

### _SizableStream_

The _SizableStream_ interface extends _Stream_ to define this method:

- `public function getSize() : ?int<0,max>`
    - Returns the length of the encapsulated resource in bytes as if by the [`fstat()`][] value for `size`, or null if indeterminate or on error.

Notes:

- **Not all _Stream_ implementations need to be sizable.** Some encapsulated resources may be unable to report a size; for example, remote or write-only resources.

### _ReadableStream_

The _ReadableStream_ interface extends _Stream_ to define these methods for reading from a resource:

- `public function eof() : bool`
    - Tests for end-of-file on the encapsulated resource as if by [`feof()`][].

- `public function getContents() : string`
    - Returns the remaining contents of the resource from the current pointer position as if by [`stream_get_contents()`][].
    - Implementations MUST throw [_RuntimeException_][] on failure.

- `public function read(int<1,max> $length) : string`
    - Returns up to `$length` bytes from the encapsulated resource as if by [`fread()`][].
    - Implementations MUST throw [_RuntimeException_][] on failure.

If the encapsulated resource is not readable at the time it becomes available to the _ReadableStream_, implementations MUST throw [_InvalidArgumentException_][].

Notes:

- **`eof()` is on _ReadableStream_, not _Stream_ or _SeekableStream_.** End-of-file is determined as a function of reading past the end of the file, not as of seeking to the end of the file. Cf. <https://www.php.net/manual/en/function.feof.php#122925>.

### _SeekableStream_

The _SeekableStream_ interface extends _Stream_ to define methods for moving the stream pointer position back and forth:

- `public function rewind() : void`
    - Moves the stream pointer position to the beginning of the stream as if by [`rewind()`][].
    - Implementations MUST throw [_RuntimeException_][] on failure.

- `public function seek(int $offset, int $whence = SEEK_SET) : void`
    - Moves the stream pointer position to the `$offset` as if by [`fseek()`][].
    - Implementations MUST throw [_RuntimeException_][] on failure.

- `public function tell() : int`
    - Returns the current stream pointer position as if by [`ftell()`][].
    - Implementations MUST throw [_RuntimeException_][] on failure.

If the encapsulated resource is not seekable at the time it becomes available to the _SeekableStream_, implementations MUST throw [_InvalidArgumentException_][].

### _StringableStream_

The _StringableStream_ interface extends _Stream_ to define a single method for returning the entire resource as a string:

- `public function __toString() : string`
    - Returns the entire contents of the encapsulated resource as if by [`rewind()`][]ing before it returns [`stream_get_contents()`][].

### _WritableStream_

The _WritableStream_ interface extends _Stream_ to define a single method for writing to a resource:

- `public function write(string|Stringable $data) : int`
    - Writes `$data` starting at the current stream pointer position, returning the number of bytes written, as if by [`fwrite()`][].
    - Implementations MUST throw [_RuntimeException_][] on failure.

If the encapsulated resource is not writable at the time it becomes available to the _WritableStream_, implementations MUST throw [_InvalidArgumentException_][].


## Implementations

Reference implementations are available at <https://github.com/stream-interop/impl>.

Notes:

- **A _Stream_ implementation MAY encapsulate a string or some other kind of data source, instead of a `resource`.** In these cases, it will make no sense to implement _ResourceStream_. Implementations that encapsulate something besides a `resource` MUST behave *as if* they encapsulate a resource.


## Q & A

### What projects were used as reference points for StreamInterop?

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

[Stream filters](https://www.php.net/manual/en/function.stream-filter-register.php) are a powerful aspect of stream resources. However, as they operate on resources directly, creating interfaces for them is out-of-scope for StreamInterop. Further, none of the projects included in the StreamInterop research implemented filters, making it difficult to rationalize adding filter interfaces.

Even so, consumers are free to register filters on the resources they injection into a _Stream_. In addition, implementors are free to create filter mechanisms that intercept the input going into a _WritableStream_ (e.g. via its `write()` method) or the output coming from a _ReadableStream_ (e.g. via its `read()` method).

### Why is there no _Factory_ interface?

The sheer volume of possible combinations of the various interfaces makes it difficult to provide a factory with proper return typehints. Implementors are encouraged to develop their own factories with proper typehinting.

* * *

[_InvalidArgumentException_]: https://php.net/InvalidArgumentException
[_RuntimeException_]: https://php.net/RuntimeException
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
[`stream_get_contents()`]: https://php.net/stream_get_contents
[`stream_get_meta_data()`]: https://php.net/stream_get_meta_data
[BCP 14]: https://www.rfc-editor.org/info/bcp14
[README-RESEARCH.md]: ./README-RESEARCH.md
[RFC 2119]: https://www.rfc-editor.org/rfc/rfc2119.txt
[RFC 8174]: https://www.rfc-editor.org/rfc/rfc8174.txt
