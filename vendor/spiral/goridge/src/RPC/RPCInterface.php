<?php

/**
 * Dead simple, high performance, drop-in bridge to Golang RPC with zero dependencies
 *
 * @author Wolfy-J
 */

declare(strict_types=1);

namespace Spiral\Goridge\RPC;

use Spiral\Goridge\Exception\GoridgeException;
use Spiral\Goridge\RPC\Exception\RPCException;

interface RPCInterface
{
    /**
     * Create RPC instance with service specific prefix.
     *
     * @param string $service
     * @return RPCInterface
     */
    public function withServicePrefix(string $service): RPCInterface;

    /**
     * Create RPC instance with service specific codec.
     *
     * @param CodecInterface $codec
     * @return RPCInterface
     */
    public function withCodec(CodecInterface $codec): RPCInterface;

    /**
     * Invoke remove RoadRunner service method using given payload (free form).
     *
     * @param string $method
     * @param mixed  $payload
     * @return mixed
     * @throws GoridgeException
     * @throws RPCException
     */
    public function call(string $method, $payload);
}
