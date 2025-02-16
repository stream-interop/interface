<?php
declare(strict_types=1);

namespace StreamInterop\Interface;

/**
 * @phpstan-type stream_metadata_array array{
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
 * @see https://github.com/phpstan/phpstan-src/blob/2.1.x/resources/functionMap.php#L12013
 */
interface StreamTypeAliases
{
}
