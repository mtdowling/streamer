<?php
namespace GuzzleHttp\Streamer;

class Utils
{
    /** @var array Hash of readable and writable stream types */
    private static $readWriteHash = [
        'read' => [
            'r' => true, 'w+' => true, 'r+' => true, 'x+' => true, 'c+' => true,
            'rb' => true, 'w+b' => true, 'r+b' => true, 'x+b' => true,
            'c+b' => true, 'rt' => true, 'w+t' => true, 'r+t' => true,
            'x+t' => true, 'c+t' => true, 'a+' => true
        ],
        'write' => [
            'w' => true, 'w+' => true, 'rw' => true, 'r+' => true, 'x+' => true,
            'c+' => true, 'wb' => true, 'w+b' => true, 'r+b' => true,
            'x+b' => true, 'c+b' => true, 'w+t' => true, 'r+t' => true,
            'x+t' => true, 'c+t' => true, 'a' => true, 'a+' => true
        ]
    ];

    public static function registerProtocol($protocol, $class)
    {
        if (!in_array($protocol, stream_get_wrappers())) {
            stream_wrapper_register($protocol, $class);
        }
    }

    public static function create($string, $mode = 'r+')
    {
        $stream = fopen('php://temp', $mode);

        if ($str !== '') {
            fwrite($stream, $str);
        }

        fseek($stream, 0);

        return $stream;
    }

    public static function isReadable($stream)
    {
        return isset(self::$readWriteHash['read'][stream_get_meta_data($stream)]);
    }

    public static function isWritable($stream)
    {
        return isset(self::$readWriteHash['write'][stream_get_meta_data($stream)]);
    }

    public static function isSeekable($stream)
    {
        return stream_get_meta_data($stream)['seekable'];
    }
}
