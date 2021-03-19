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

final class JsonCodec implements CodecInterface
{
    /**
     * Coded index, uniquely identified by remote server.
     *
     * @return int
     */
    public function getIndex(): int
    {
        return Frame::CODEC_JSON;
    }

    /**
     * @param mixed $payload
     * @return string
     */
    public function encode($payload): string
    {
        $result = json_encode($payload);
        if ($result === false) {
            $lastError = json_last_error_msg();
            if ($lastError !== null) {
                throw new CodecException(sprintf('json encode: %s', $lastError));
            }
        }

        return $result;
    }

    /**
     * @param string $payload
     * @return mixed
     */
    public function decode(string $payload)
    {
        return json_decode($payload, true);
    }
}
