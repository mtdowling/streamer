<?php
namespace GuzzleHttp\Streamer;

class NoSeek extends BaseWrapper
{
    public function stream_seek($offset, $whence)
    {
        return false;
    }
}
