# Stream-Interop Standard Interface Package

[![PDS Skeleton](https://img.shields.io/badge/pds-skeleton-blue.svg?style=flat-square)](https://github.com/php-pds/skeleton)
[![PDS Composer Script Names](https://img.shields.io/badge/pds-composer--script--names-blue?style=flat-square)](https://github.com/php-pds/composer-script-names)

Stream-Interop provides an interoperable package of standard interfaces for
working with stream resources in PHP 8.4 or later. It reflects, refines, and
reconciles the common practices identified within
[several pre-existing projects][README-RESEARCH.md].

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED",  "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [BCP 14][] ([RFC 2119][], [RFC 8174][]).

This package attempts to adhere to the [Package Development Standards](https://php-pds.com/) approach to [naming and versioning](https://php-pds.com/#naming-and-versioning).

## Interfaces

Stream-Interop defines separate interfaces for various affordances around stream resources so that (1) implementations can advertise well-tailored affordances, and (2) consumers can typehint to the specific affordances they require for specific situations.

- [_Stream_][] is a common baseline for streams.

- [_ResourceStream_][] extends [_Stream_][] to afford direct access to the encapsulated resource.

- [_ClosableStream_][] extends [_Stream_][] to afford closing the stream.

- [_SizableStream_][] extends [_Stream_][] to afford getting the full length of the stream in bytes.

- [_ReadableStream_][] extends [_Stream_][] to afford non-idempotent reading from the stream.

- [_SeekableStream_][] extends [_Stream_][] to afford moving the stream pointer position back and forth.

- [_StringableStream_][] extends [_Stream_][] to afford idempotent reading from the encapsulated resource.

- [_WritableStream_][] extends [_Stream_][] to afford writing to the stream at the current pointer position.

- [_ReadonlyStream_][] is a marker interface that extends [_Stream_][] to indicate the implementation attempts to enforce readonly constraints on the encapsulated resource.

- [_ImmutableStream_][] is a marker interface that extends [_ReadonlyStream_][] to indicate the implementation attempts to enforce immutability constraints on the encapsulated resource.

- [_StreamThrowable_][] is a marker interface that extends [_Throwable_][] to indicate an [_Exception_][] is stream-related.

- [_StreamTypeAliases_][] defines custom PHPStan types to aid static analysis.

### _Stream_

[_Stream_][] is a common baseline for streams.

- Notes:

    - **The `$metadata` property is expected to change dynamically.** That
      is, as the encapsulated resource gets read from and written to,
      the metadata for that resource is likely to change. Thus, the
      `$metadata` property value is expected to change along with it.
      In practical terms, this likely means a [`stream_get_meta_data()`][]
      call on each access of `$metadata`.

    - **There are no `isReadable()`, etc. methods.** If necessary, such
      functionality can be determined by typehinting against the interface,
      or by checking `instanceof`, etc.

    - **The encapsulated resource is not exposed publicly here.** The
      encapsulated resource may remain private or protected. See the
      [_ResourceStream_][] interface for details on making the encapsulated
      resource publicly accessible.

#### _Stream_ Properties

- ```php
  public metadata_array $metadata { get; }
  ```
    - Represents the metadata for the encapsulated resource as if by
    [`stream_get_meta_data()`][].

    - Directives:

        - Implementations MUST provide the most-recent metadata for the
          encapsulated resource at the moment of property access; if the
          encapsulated resource is closed, implementations MUST return an
          empty array.

        - Implementations MUST NOT allow `$metadata` to be publicly settable,
          either as a property or via property hook or method.

#### _Stream_ Methods

- ```php
  public function isClosed() : bool;
  ```
    - Returns `true` if the encapsulated resource has been closed, or `false` if not.

- ```php
  public function isOpen() : bool;
  ```
    - Returns `true` if the encapsulated resource is still open, or `false` if not.

### _ResourceStream_

[_ResourceStream_][] extends [_Stream_][] to afford direct access to the
encapsulated resource.

- Notes:

    - **Not all [_Stream_][] implementations need to expose the encapsulated
      resource.** Exposing the resource gives full control over it to
      consumers, who can then manipulate it however they like (e.g. close
      it, move the pointer, and so on). However, having access to the
      resource may be necessary for some consumers.

    - **Some [_Stream_][] implementations might not encapsulate a resource.**
      Although a resource is the most common data source for a stream, other
      data sources may be used, in which cases [_ResourceStream_][]
      implementation is neither appropriate nor necessary.

#### _ResourceStream_ Properties

- ```php
  public resource $resource { get; }
  ```
    - Represents the resource as if opened by [`fopen()`][], [`fsockopen()`][],
    [`popen()`][], etc.

    - Directives:

        - Implementations MUST ensure `$resource` is a `resource of type (stream)`;
          for example, as determined by [`get_resource_type()`][].

        - Implementations SHOULD NOT allow `$resource` to be publicly settable,
          either as a property or via property hook or method.

### _ClosableStream_

[_ClosableStream_][] extends [_Stream_][] to afford closing the stream.

- Directives:

    - Implementations MAY close the encapsulated resource internally
      without affording [_ClosableStream_][].

- Notes:

    - **Not all [_Stream_][] implementations need to be closable.** It may
      be important for resource closing to be handled by a separate service
      or authority, and not be closable by [_Stream_][] consumers.

#### _ClosableStream_ Methods

- ```php
  public function close() : void;
  ```
    - Closes the encapsulated resource as if by [`fclose()`][],
    [`pclose()`][], etc.

    - Directives:

        - Implementations MUST throw a [_StreamThrowable_][] on failure.

### _SizableStream_

[_SizableStream_][] extends [_Stream_][] to afford getting the full length of
the stream in bytes.

- Directives:

    - Implementations MAY get the size of the encapsulated resource
      internally without affording [_SizableStream_][].

- Notes:

    - **Not all [_Stream_][] implementations need to be sizable.** Some
      encapsulated resources may be unable to report a size; for example,
      remote or write-only resources.

#### _SizableStream_ Methods

- ```php
  public function getSize() : ?int<0,max>;
  ```
    - Returns the length of the encapsulated resource in bytes as if by the
    [`fstat()`][] value for `size`, or `null` if indeterminate or on error.

### _ReadableStream_

[_ReadableStream_][] extends [_Stream_][] to afford non-idempotent reading
from the stream.

- Directives:

    - If the encapsulated resource is not readable at the time it becomes
      available to the [_ReadableStream_][], implementations MUST throw a
      [_StreamThrowable_][].

    - Implementations MAY read from the encapsulated resource internally
      without affording [_ReadableStream_][].

- Notes:

    - **The `eof()` method is on [_ReadableStream_][], not [_Stream_][] or
      [_SeekableStream_][].** End-of-file is determined as a function of
      reading past the end of the file, not as of seeking to the end of
      the file. Cf. <https://www.php.net/manual/en/function.feof.php#122925>.

    - **These methods are non-idempotent.** They may return different
      results on repeated sequential calls, and may have side effects (e.g.,
      changing the position of the pointer.)

#### _ReadableStream_ Methods

- ```php
  public function eof() : bool;
  ```
    - Tests for end-of-file on the encapsulated resource as if by [`feof()`][].

    - Directives:

        - Implementations MUST throw a [_StreamThrowable_][] on failure.

- ```php
  public function read(int<1,max> $length) : string;
  ```
    - Returns up to `$length` bytes from the encapsulated resource as if by
    [`fread()`][].

    - Directives:

        - Implementations MUST throw a [_StreamThrowable_][] on failure.

- ```php
  public function getContents() : string;
  ```
    - Returns the remaining contents of the resource from the current pointer
    position as if by [`stream_get_contents()`][].

    - Directives:

        - Implementations MUST throw a [_StreamThrowable_][] on failure.

### _SeekableStream_

[_SeekableStream_][] extends [_Stream_][] to afford moving the stream pointer
position back and forth.

- Directives:

    - If the encapsulated resource is not seekable at the time it becomes
      available to the [_SeekableStream_][], implementations MUST throw a
      [_StreamThrowable_][].

#### _SeekableStream_ Methods

- ```php
  public function rewind() : void;
  ```
    - Moves the stream pointer position to the beginning of the stream as if
    by [`rewind()`][].

    - Directives:

        - Implementations MUST throw a [_StreamThrowable_][] on failure.

- ```php
  public function seek(
      int $offset,
      SEEK_CUR|SEEK_SET|SEEK_END $whence = 0,
  ) : void;
  ```
    - Moves the stream pointer position to the `$offset` as if by [`fseek()`][].

    - Directives:

        - Implementations MUST throw a [_StreamThrowable_][] on failure.

- ```php
  public function tell() : int;
  ```
    - Returns the current stream pointer position as if by [`ftell()`][].

    - Directives:

        - Implementations MUST throw a [_StreamThrowable_][] on failure.

### _StringableStream_

[_StringableStream_][] extends [_Stream_][] to afford idempotent reading from
the encapsulated resource.

- Directives:

    - If the encapsulated resource is not readable at the time it becomes
      available to the [_StringableStream_][], implementations MUST throw a
      [_StreamThrowable_][].

    - If the encapsulated resource is not seekable at the time it becomes
      available to the [_StringableStream_][], implementations MUST throw a
      [_StreamThrowable_][].

    - Implementations MAY convert all or part of the encapsulated resource
      to a string internally without affording [_StringableStream_][].

- Notes:

    - **These methods are idempotent.** Repeated sequential calls to an
      unchanged resource will return the exact same result, without exposing
      side effects (such as the pointer position being changed).

#### _StringableStream_ Methods

- ```php
  public function __toString() : string;
  ```
    - Returns the entire contents of the encapsulated resource as if by
    [`rewind()`][]ing before returning [`stream_get_contents()`][].

    - Directives:

        - After reading, implementations MUST reposition the encapsulated
          resource pointer to its initial location.

        - Implementations MUST throw a [_StreamThrowable_][] on failure.

- ```php
  public function subString(int $offset, ?int<0,max> $length = null) : string;
  ```
    - Returns a string from the encapsulated resource as if by [`fseek()`][]ing
    before reading.

    - Directives:

        - If the `$offset` is negative, implementations MUST begin reading at
          that many bytes from the end of the stream; otherwise,
          implementations MUST begin reading at that many bytes from the
          start of the stream.

        - If the `$length` is null, implementations MUST return all remaining
          bytes from the stream; otherwise, implementations MUST return up to
          that many bytes from the stream.

        - After reading, implementations MUST reposition the encapsulated
          resource pointer to its initial location.

        - Implementations MUST throw a [_StreamThrowable_][] on failure.

### _WritableStream_

[_WritableStream_][] extends [_Stream_][] to afford writing to the stream at
the current pointer position.

- Directives:

    - If the encapsulated resource is not writable at the time it becomes
      available to the [_WritableStream_][], implementations MUST throw a
      [_StreamThrowable_][].

    - Implementations MAY write to the encapsulated resource internally
      without affording [_WritableStream_][].

#### _WritableStream_ Methods

- ```php
  public function write(string|Stringable $data) : int;
  ```
    - Writes `$data` starting at the current stream pointer position, returning
    the number of bytes written, as if by [`fwrite()`][].

    - Directives:

        - Implementations MUST throw a [_StreamThrowable_][] on failure.

### _ReadonlyStream_

[_ReadonlyStream_][] is a marker interface that extends [_Stream_][] to
indicate the implementation attempts to enforce readonly constraints on
the encapsulated resource.

- Directives:

    - Implementations MUST open the encapsulated resource inside the
      [_ReadonlyStream_][].

    - Implementations MUST open the encapsulated resource as `php://input`
      or `php://memory`.

    - Implementations MAY open the encapsulated resource in a mode that
      allows writing (`rb+`, `w+`, etc.) to allow initialization.

    - Implementations MAY initialize the encapsulated resource after
      opening (e.g., by copying a constructor argument to the encapsulated
      resource).

    - Implementations MUST NOT modify, or allow modification of, the
      encapsulated resource content after initialization, whether by
      implementing [_WritableStream_][] or by some other means.

    - Implementations MUST NOT expose the encapsulated resource, whether
      by implementing [_ResourceStream_][] or by some other means.

    - Implementations MAY allow closing of the encapsulated resource,
      whether by implementing [_ClosableStream_][] or by some other means.

- Notes:

    - **The readonly constraints are necessarily strict.** Whereas readonly
      on scalar and array properties can be implemented relatively easily,
      readonly on a resource property is more difficult. The encapsulated
      resource, including both its content and its pointer, must be
      inaccessible from outside the [_ReadonlyStream_][] to ensure it cannot
      be modified from outside the [_ReadonlyStream_][].

    - **[_ReadonlyStream_][] implementations may be memory-intensive.** This
      is because they usually have to be initialized with a copy of the
      original resource, typically a file resource, thereby reading all of
      it into a `php://memory` resource.

    - **`php://input` is natively readonly.** It does not need to be copied
      to a `php://memory` resource, and does not need to be initialized.

### _ImmutableStream_

[_ImmutableStream_][] is a marker interface that extends [_ReadonlyStream_][]
to indicate the implementation attempts to enforce immutability constraints
on the encapsulated resource.

- Directives:

    - Implementations MUST adhere to all [_ReadonlyStream_][] constraints.

    - Implementations MUST NOT allow non-idempotent reading of the
      encapsulated resource, whether by implementing [_ReadableStream_][] or
      by some other means.

    - Implementations MUST NOT expose the state of the encapsulated
      resource pointer, whether by implementing [_SeekableStream_][] or by
      some other means.

    - Implementations MUST NOT allow closing of the encapsulated resource
      before the [_ImmutableStream_][] is destructed, whether by
      implementing [_ClosableStream_][] or by some other means.

    - Implementations MUST NOT allow mutation of the `$metadata` property.

- Notes:

    - **The immutability constraints are necessarily strict.** Immutability
      of a resource is incompatible with non-idempotent reading; doing so
      modifies its pointer position, thereby changing its state. Likewise,
      closing the resource changes its state. These constraints leave only
      [_StringableStream_][] and [_SizableStream_][] as compatible
      interfaces.

### _StreamThrowable_

[_StreamThrowable_][] is a marker interface that extends [_Throwable_][] to
indicate an [_Exception_][] is stream-related.

It adds no class members.

### _StreamTypeAliases_

[_StreamTypeAliases_][] defines custom PHPStan types to aid static analysis.

- ```
  metadata_array: array{
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
    - An `array` of stream metadata, as if by [`stream_get_meta_data()`][].

- ```
  stat_array: array{
      dev: int<0,max>,
      ino: int<0,max>,
      mode: int<0,max>,
      nlink: int<0,max>,
      uid: int<0,max>,
      gid: int<0,max>,
      rdev: int<0,max>,
      size: int<0,max>,
      atime: int<0,max>,
      mtime: int<0,max>,
      ctime: int<0,max>,
      blksize: int<0,max>,
      blocks: int<0,max>
  }
  ```
    - An `array` of resource stats, as if by [`fstat()`][] or [`stat()`][].

## Implementations

Implementations MAY encapsulate a string, or some other kind of data source, instead of a `resource`.

Implementations encapsulating something besides a `resource` MUST behave *as if* they encapsulate a resource.

Implementations advertised as readonly or immutable MUST be deeply readonly or immutable. With the exception of implementations meeting the specified [_ReadonlyStream_][] or [_ImmutableStream_][] conditions, they MUST NOT encapsulate any references, resources, mutable objects, objects or arrays encapsulating references or resources or mutable objects, and so on.

Implementations MAY define additional class members not defined in these interfaces; implementations advertised as readonly or immutable MUST make those additional class members deeply readonly or immutable.

Notes:

- **Reflection does not invalidate advertisements of readonly or immutable implementations.** The ability of a consumer to use Reflection to mutate an implementation advertised as readonly or immutable does not constitute a failure to comply with Stream-Interop.

- **Reference implementations** are available at <https://github.com/stream-interop/impl>.

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

Even so, consumers are free to register filters on the resources they inject into a [_Stream_][]. In addition, implementors are free to create filter mechanisms that intercept the input going into a [_WritableStream_][] (e.g. via its `write()` method) or the output coming from a [_ReadableStream_][] (e.g. via its `read()` method).

### Why is there no _Factory_ interface?

The sheer volume of possible combinations of the various interfaces makes it difficult to provide a factory with proper return typehints. Implementors are encouraged to develop their own factories with proper typehinting.

* * *

[_ClosableStream_]: #closablestream
[_Exception_]: https://php.net/Exception
[_ImmutableStream_]: #immutablestream
[_ReadableStream_]: #readablestream
[_ReadonlyStream_]: #readonlystream
[_ResourceStream_]: #resourcestream
[_SeekableStream_]: #seekablestream
[_SizableStream_]: #sizablestream
[_Stream_]: #stream
[_StreamThrowable_]: #streamthrowable
[_StreamTypeAliases_]: #streamtypealiases
[_StringableStream_]: #stringablestream
[_Throwable_]: https://php.net/Throwable
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
[`get_resource_type()`]: https://php.net/get_resource_type
[`pclose()`]: https://php.net/pclose
[`popen()`]: https://php.net/popen
[`rewind()`]: https://php.net/rewind
[`stat()`]: https://php.net/stat
[`stream_get_contents()`]: https://php.net/stream_get_contents
[`stream_get_meta_data()`]: https://php.net/stream_get_meta_data
[BCP 14]: https://datatracker.ietf.org/doc/bcp14/
[README-RESEARCH.md]: ./README-RESEARCH.md
[RFC 2119]: https://datatracker.ietf.org/doc/html/rfc2119
[RFC 8174]: https://datatracker.ietf.org/doc/html/rfc8174
