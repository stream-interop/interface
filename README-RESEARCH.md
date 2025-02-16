# Research

Stream-Interop is based on research including the following projects:

- amphp/byte-stream: https://github.com/amphp/byte-stream (amphp)
- fzaninotto/streamer: https://github.com/fzaninotto/Streamer (fzan)
- hoa/stream: https://github.com/hoaproject/Stream (hoa)
- kraken-php/stream: https://github.com/kraken-php/stream (kraken)
- psr/http-message: https://github.com/php-fig/http-message/blob/master/src/StreamInterface.php (psr)
- react/stream: https://github.com/reactphp/stream (react)
- zenstruck/stream: https://github.com/zenstruck/stream (zenstr)

## Interface Separations

The projects separate their interfaces and implmentations as follows:

- amphp:
    - _ClosableStream_
    - _ReadableStream_
    - _ResourceStream_
    - _WritableStream_
- fzan:
    - _Stream_
- hoa:
    - _In_ ("readable")
    - _Out_ ("writable")
    - _Pointable_ ("seekable")
    - _Stream_
- kraken:
    - _StreamBaseInterface_
    - _StreamReaderInterface_
    - _StreamSeekerInterface_
    - _StreamWriterInterface_
- psr:
    - _StreamInterface_
- react:
    - _DuplexStreamInterface_
    - _ReadableStreamInterface_
    - _WritableStreamInterface_
- zenstr:
    - _Stream_

## PHP Function Equivalents

These PHP functions have equivalent methods in the respective projects.

function                                     | amphp | fzan | hoa | kraken | psr | react | zenstr |
-------------------------------------------- | ----- | ---- | --- | ------ | --- | ----- | ------ |
`fclose()`                                   | X     | X    | X   | X      | X   | X     | X      |
`feof()`                                     |       |      | X   |        | X   |       |        |
`fread(int $length)`                         |       | X1   | X   | X1     | X   |       | X      |
`fwrite(string $data, int $length)`          | X2    | X    | X   | X2     | X2  | X2    | X      |
`rewind()`                                   |       | X    | X   | X      | X   |       | X      |
`fseek(int $offset, int $whence = SEEK_SET)` |       | X    | X   | X      | X   |       | X      |
`ftell()`                                    |       |      | X   | X      | X   |       |        |
`stream_get_meta_data()`                     |       | X    | X   | X      | X   |       | X      |
`stream_get_contents()`                      |       | X1   | X3  | X1     | X   |       | X3     |

1. fzan and kraken use `fread(?int $length)`, where null indicates an equivalent `stream_get_contents()`

2. amphp, kraken, psr, and react omit the `$length` parameter

3. hoa as `readAll()`, zenstruck as `contents()`

Notably, the projects almost never use `false` as a return value to indicate failure; they almost always throw an exception of some sort instead.

## Other Affordances

These are some of the other affordances or their equivalents commonly defined in their respective projects.

method                                    | amphp | fzan | hoa | kraken | psr | react | zenstr |
----------------------------------------- | ----- | ---- | --- | ------ | --- | ----- | ------ |
`__toString()` (convert stream to string) |       |      |     |        | X   |       | X      |
`getResource()` (get the resource itself) | X     | X    | X1  | X      | X1  |       | X1     |
`getSize()`                               |       |      | X2  |        | X   |       |        |
`isClosed()`                              | X     |      |     |        |     |       | X      |
`isOpen()`                                |       | X    | X   | X      |     |       | X      |
`isReadable()`                            | X     | X    |     | X      | X   | X     |        |
`isSeekable()`                            |       | X    |     | X      | X   |       | X      |
`isWritable()`                            | X     | X    | X   | X      | X   | X     |        |


1. hoa as `getStream()`, psr as `detach()`, zenstr as `get()`
2. hoa has `getSize()` only in its _StatableInterface_

## Exceptions

On these error conditions or their equivalents, these exceptions are thrown by these projects:

condition                                 | amphp | fzan | hoa | kraken | psr | react | zenstr |
----------------------------------------- | ----- | ---- | --- | ------ | --- | ----- | ------ |
Resource is invalid                       | E     | I    | C   | C      |     | I     | I      |
Resource is not readable (1)              | E     | L    |     | C      |     | I     |        |
Resource is not seekable (2)              | E     |      |     |        |     |       | R      |
Resource is not writable (3)              | E     | L    |     | C      |     | I     |        |
Resource is already closed                |       | L    |     |        |     |       | R      |
Read fails                                | ?     | R    |     |        | R   |       | R      |
Write fails                               | ?     | R    |     | C      | R   |       | R      |
Tell fails                                |       |      |     | C      | R   |       |        |
Seek fails                                |       |      |     | C      | R   |       | R      |
Rewind fails                              |       |      |     | C      | R   |       | R      |
Get contents fails                        |       |      |     | C      | R   |       | R      |

1. E.g., not opened with `'r'`, or has become unreadable
2. E.g., metadata `'seekable'` is `false`
3. E.g., not opened with `'w'`, or has become unwritable

- `C`: Custom package-specific exception
- `I`: PHP _InvalidArgumentException_
- `L`: PHP _LogicException_
- `R`: PHP _RuntimeException_
- `E`: PHP _Error_
- `?`: Depends on the implementation; sometimes custom, sometimes PHP

* * *
