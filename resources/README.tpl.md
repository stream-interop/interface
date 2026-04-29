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

{{= list }}

{{= docs }}

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
[`stream_get_contents()`]: https://php.net/stream_get_contents
[`stream_get_meta_data()`]: https://php.net/stream_get_meta_data
[BCP 14]: https://www.rfc-editor.org/info/bcp14
[README-RESEARCH.md]: ./README-RESEARCH.md
[RFC 2119]: https://datatracker.ietf.org/doc/html/rfc2119
[RFC 8174]: https://datatracker.ietf.org/doc/html/rfc8174
