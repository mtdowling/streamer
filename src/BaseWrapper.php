<?php
namespace GuzzleHttp\Streamer;

class BaseWrapper
{
    /** @var resource */
    public $context;

    /** @var resource */
    private $stream;

    /** @var string r, r+, or w */
    private $mode;

    public static function wrap($stream)
    {
        static $cache = [];
        static $replace = ['_', '\\'];
        $calledClass = get_called_class();
        $protocol = 'Guzzle-' . str_replace($replace, '-', $calledClass);

        // Register the stream wrapper decorator if needed. This is cached as
        // registering and checking existing wrappers is expensive.
        if (!isset($cache[$protocol])) {
            Utils::registerProtocol($protocol, $calledClass);
            $cache[$protocol] = true;
        }

        $wrapper = fopen(
            $protocol . '://',
            stream_get_meta_data($stream)['mode'],
            null,
            stream_context_create(['guzzle' => ['stream' => $stream]
        ]));

        if (!$wrapper) {
            throw new \RuntimeException('Unable to wrap the stream with '
                . $protocol);
        }

        return $wrapper;
    }

    public function stream_open($path, $mode, $options, &$opened_path)
    {
        $options = stream_context_get_options($this->context);

        if (!isset($options['guzzle']['stream'])) {
            return false;
        }

        $this->mode = $mode;
        $this->stream = $options['guzzle']['stream'];

        return true;
    }

    public function stream_close()
    {
        fclose($this->stream);
    }

    public function stream_read($count)
    {
        return fread($this->stream, $count);
    }

    public function stream_write($data)
    {
        return fwrite($this->stream, $data);
    }

    public function stream_tell()
    {
        return ftell($this->stream);
    }

    public function stream_eof()
    {
        return feof($this->stream);
    }

    public function stream_seek($offset, $whence)
    {
        return fseek($this->stream, $offset, $whence);
    }

    public function stream_stat()
    {
        return fstat($this->stream);
    }

    public function stream_flush()
    {
        return fflush($this->stream);
    }

    public function stream_lock($operation)
    {
        return flock($this->stream, $operation);
    }

    public function stream_truncate($new_size)
    {
        return ftruncate($this->stream, $new_size);
    }
}
