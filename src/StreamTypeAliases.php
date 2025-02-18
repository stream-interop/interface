<?php
declare(strict_types=1);

namespace StreamInterop\Interface;

/**
 * @see https://github.com/phpstan/phpstan-src/blob/2.1.x/resources/functionMap.php
 * @phpstan-type metadata_array array{
 *     timed_out: bool,
 *     blocked: bool,
 *     eof: bool,
 *     unread_bytes: int,
 *     stream_type: string,
 *     wrapper_type: string,
 *     wrapper_data: mixed,
 *     mode: string,
 *     seekable: bool,
 *     uri?: string,
 *     mediatype?: string,
 *     base64?: bool
 * }
 *
* @see https://www.php.net/manual/en/function.stat.php
* @phpstan-type stat_array array{
*     dev:int<0,max>,
*     ino:int<0,max>,
*     mode:int<0,max>,
*     nlink:int<0,max>,
*     uid:int<0,max>,
*     gid:int<0,max>,
*     rdev:int<0,max>,
*     size:int<0,max>,
*     atime:int<0,max>,
*     mtime:int<0,max>,
*     ctime:int<0,max>,
*     blksize:int<0,max>,
*     blocks:int<0,max>
* }
*/
interface StreamTypeAliases
{
}
