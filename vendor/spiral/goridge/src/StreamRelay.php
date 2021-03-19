<?php

/**
 * Dead simple, high performance, drop-in bridge to Golang RPC with zero dependencies
 *
 * @author Wolfy-J
 */

declare(strict_types=1);

namespace Spiral\Goridge;

use Spiral\Goridge\Exception\RelayException;

/**
 * Communicates with remote server/client over streams using byte payload:
 *
 * [ prefix       ][ payload                               ]
 * [ 1+8+8 bytes  ][ message length|LE ][message length|BE ]
 *
 * prefix:
 * [ flag       ][ message length, unsigned int 64bits, LittleEndian ]
 */
class StreamRelay extends Relay
{
    /** @var resource */
    private $in;

    /** @var resource */
    private $out;

    /**
     * Example:
     * $relay = new StreamRelay(STDIN, STDOUT);
     *
     * @param resource $in  Must be readable.
     * @param resource $out Must be writable.
     *
     * @throws Exception\InvalidArgumentException
     */
    public function __construct($in, $out)
    {
        if (!is_resource($in) || get_resource_type($in) !== 'stream') {
            throw new Exception\InvalidArgumentException('expected a valid `in` stream resource');
        }

        if (!$this->assertReadable($in)) {
            throw new Exception\InvalidArgumentException('resource `in` must be readable');
        }

        if (!is_resource($out) || get_resource_type($out) !== 'stream') {
            throw new Exception\InvalidArgumentException('expected a valid `out` stream resource');
        }

        if (!$this->assertWritable($out)) {
            throw new Exception\InvalidArgumentException('resource `out` must be writable');
        }

        $this->in = $in;
        $this->out = $out;
    }

    /**
     * @return Frame
     * @throws RelayException
     */
    public function waitFrame(): Frame
    {
        $header = fread($this->in, 12);
        if ($header === false || strlen($header) !== 12) {
            throw new Exception\HeaderException('unable to read frame header');
        }

        $parts = Frame::readHeader($header);

        // total payload length
        $payload = '';
        $length = $parts[1] * 4 + $parts[2];

        while ($length > 0) {
            $buffer = fread($this->in, (int) $length);
            if ($buffer === false) {
                throw new Exception\TransportException('error reading payload from the stream');
            }

            $payload .= $buffer;
            $length -= strlen($buffer);
        }

        return Frame::initFrame($parts, $payload);
    }

    /**
     * @param Frame $frame
     */
    public function send(Frame $frame): void
    {
        $body = Frame::packFrame($frame);

        if (fwrite($this->out, $body, strlen($body)) === false) {
            throw new Exception\TransportException('unable to write payload to the stream');
        }
    }

    /**
     * Checks if stream is readable.
     *
     * @param resource $stream
     *
     * @return bool
     */
    private function assertReadable($stream): bool
    {
        $meta = stream_get_meta_data($stream);

        return in_array(
            $meta['mode'],
            ['r', 'rb', 'r+', 'rb+', 'w+', 'wb+', 'w+b', 'a+', 'ab+', 'x+', 'c+', 'cb+'],
            true
        );
    }

    /**
     * Checks if stream is writable.
     *
     * @param resource $stream
     *
     * @return bool
     */
    private function assertWritable($stream): bool
    {
        $meta = stream_get_meta_data($stream);

        return !in_array($meta['mode'], ['r', 'rb'], true);
    }
}
