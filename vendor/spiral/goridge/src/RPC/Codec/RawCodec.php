<?php

/**
 * Dead simple, high performance, drop-in bridge to Golang RPC with zero dependencies
 *
 * @author Wolfy-J
 */

declare(strict_types=1);

namespace Spiral\Goridge\RPC\Codec;

use Spiral\Goridge\Frame;
use Spiral\Goridge\RPC\CodecInterface;
use Spiral\Goridge\RPC\Exception\CodecException;

final class RawCodec implements CodecInterface
{
    /**
     * Coded index, uniquely identified by remote server.
     *
     * @return int
     */
    public function getIndex(): int
    {
        return Frame::CODEC_RAW;
    }

    /**
     * @param mixed $payload
     * @return string
     */
    public function encode($payload): string
    {
        if (!is_string($payload)) {
            throw new CodecException(
                sprintf('Only string payloads can be send using RawCodec, %s given', gettype($payload))
            );
        }

        return $payload;
    }

    /**
     * @param string $payload
     * @return mixed
     */
    public function decode(string $payload)
    {
        return $payload;
    }
}
