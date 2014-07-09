Example:

.. code-block:: php

    require 'vendor/autoload.php';

    use GuzzleHttp\Streamer\Utils;
    use GuzzleHttp\Streamer\BaseWrapper;
    use GuzzleHttp\Streamer\NoSeek;

    $base = Utils::fromString('foobar');
    $f = NoSeek::wrap($base);
    
    echo fread($f, 10);
    fseek($f, 0);
    echo fread($f, 10);
    fseek($base, 0);
    echo fread($base, 10);
