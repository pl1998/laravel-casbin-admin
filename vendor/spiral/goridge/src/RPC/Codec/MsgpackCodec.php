<?php

/**
 * Dead simple, high performance, drop-in bridge to Golang RPC with zero dependencies
 *
 * @author Wolfy-J
 */

declare(strict_types=1);

namespace Spiral\Goridge\RPC\Codec;

use MessagePack\MessagePack;
use Spiral\Goridge\Frame;
use Spiral\Goridge\RPC\CodecInterface;

final class MsgpackCodec implements CodecInterface
{
    private \Closure $pack;
    private \Closure $unpack;

    /**
     * Constructs extension using native or fallback implementation.
     */
    public function __construct()
    {
        $this->initPacker();
    }

    /**
     * Coded index, uniquely identified by remote server.
     *
     * @return int
     */
    public function getIndex(): int
    {
        return Frame::CODEC_MSGPACK;
    }

    /**
     * @param mixed $payload
     * @return string
     */
    public function encode($payload): string
    {
        return ($this->pack)($payload);
    }

    /**
     * @param string $payload
     * @return mixed
     */
    public function decode(string $payload)
    {
        return ($this->unpack)($payload);
    }

    /**
     * Init pack and unpack functions.
     */
    private function initPacker(): void
    {
        if (function_exists('msgpack_pack') && function_exists('msgpack_unpack')) {
            $this->pack = static function ($payload) {
                return msgpack_pack($payload);
            };

            $this->unpack = static function ($payload) {
                return msgpack_unpack($payload);
            };
        } else {
            $this->pack = static function ($payload) {
                return MessagePack::pack($payload);
            };

            $this->unpack = static function ($payload) {
                return MessagePack::unpack($payload);
            };
        }
    }
}
