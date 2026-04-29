<?php return [
    'namespace' => 'StreamInterop\\Interface\\',
    'directory' => dirname(__DIR__) . '/src',
    'template' => dirname(__DIR__) . '/resources/README.tpl.md',
    'interfaces' => [
        'Stream',
        'ResourceStream',
        'ClosableStream',
        'SizableStream',
        'ReadableStream',
        'SeekableStream',
        'StringableStream',
        'WritableStream',
        'ReadonlyStream',
        'ImmutableStream',
        'StreamThrowable',
        'StreamTypeAliases',
    ],
];
