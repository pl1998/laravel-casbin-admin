<?php

/**
 * Dead simple, high performance, drop-in bridge to Golang RPC with zero dependencies
 *
 * @author Wolfy-J
 */

declare(strict_types=1);

namespace Spiral\Goridge\RPC;

use Spiral\Goridge\Exception\GoridgeException;
use Spiral\Goridge\Frame;
use Spiral\Goridge\RelayInterface as Relay;
use Spiral\Goridge\RPC\Codec\JsonCodec;
use Spiral\Goridge\RPC\Exception\RPCException;
use Spiral\Goridge\StringableRelayInterface;

class RPC implements RPCInterface
{
    private Relay $relay;
    private CodecInterface $codec;
    private ?string $service = null;

    /** @var int */
    private static int $seq = 1;

    /**
     * @param Relay               $relay
     * @param CodecInterface|null $codec
     */
    public function __construct(Relay $relay, CodecInterface $codec = null)
    {
        $this->relay = $relay;
        $this->codec = $codec ?? new JsonCodec();
    }

    /**
     * Create RPC instance with service specific prefix.
     *
     * @param string $service
     * @return RPCInterface
     */
    public function withServicePrefix(string $service): RPCInterface
    {
        $rpc = clone $this;
        $rpc->service = $service;

        return $rpc;
    }

    /**
     * Create RPC instance with service specific codec.
     *
     * @param CodecInterface $codec
     * @return RPCInterface
     */
    public function withCodec(CodecInterface $codec): RPCInterface
    {
        $rpc = clone $this;
        $rpc->codec = $codec;

        return $rpc;
    }

    /**
     * Invoke remove RoadRunner service method using given payload (depends on codec).
     *
     * @param string $method
     * @param mixed  $payload
     * @return mixed
     * @throws GoridgeException
     * @throws RPCException
     */
    public function call(string $method, $payload)
    {
        $this->relay->send($this->packFrame($method, $payload));

        // wait for the frame confirmation
        $frame = $this->relay->waitFrame();

        if (count($frame->options) !== 2) {
            throw new Exception\RPCException('invalid RPC frame, options missing');
        }

        if ($frame->options[0] !== self::$seq) {
            throw new Exception\RPCException('invalid RPC frame, sequence mismatch');
        }

        self::$seq++;

        return $this->decodeResponse($frame);
    }

    /**
     * @param string              $connection
     * @param CodecInterface|null $codec
     * @return RPCInterface
     */
    public static function create(string $connection, CodecInterface $codec = null): RPCInterface
    {
        $relay = \Spiral\Goridge\Relay::create($connection);

        return new self($relay, $codec);
    }

    /**
     * @param Frame $frame
     * @return mixed
     *
     * @throws Exception\ServiceException
     */
    private function decodeResponse(Frame $frame)
    {
        // exclude method name
        $body = substr((string)$frame->payload, $frame->options[1]);

        if ($frame->hasFlag(Frame::ERROR)) {
            throw new Exception\ServiceException(
                sprintf(
                    "error '%s' on %s",
                    $body,
                    $this->relay instanceof StringableRelayInterface ? (string) $this->relay : get_class($this->relay)
                )
            );
        }

        return $this->codec->decode($body);
    }

    /**
     * @param string $method
     * @param mixed  $payload
     * @return Frame
     */
    private function packFrame(string $method, $payload): Frame
    {
        if ($this->service !== null) {
            $method = $this->service . '.' . ucfirst($method);
        }

        return new Frame(
            $method . $this->codec->encode($payload),
            [self::$seq, strlen($method)],
            $this->codec->getIndex()
        );
    }
}
