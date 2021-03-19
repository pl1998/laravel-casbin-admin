<?php

/**
 * Dead simple, high performance, drop-in bridge to Golang RPC with zero dependencies
 *
 * @author Wolfy-J
 */

declare(strict_types=1);

namespace Spiral\Goridge;

use Error;
use Spiral\Goridge\Exception\RelayException;

/**
 * Communicates with remote server/client over be-directional socket using byte payload:
 *
 * [ prefix       ][ payload                               ]
 * [ 1+8+8 bytes  ][ message length|LE ][message length|BE ]
 *
 * prefix:
 * [ flag       ][ message length, unsigned int 64bits, LittleEndian ]
 */
class SocketRelay extends Relay implements StringableRelayInterface
{
    /** Supported socket types. */
    public const SOCK_TCP  = 0;
    public const SOCK_UNIX = 1;

    private string $address;
    private bool $connected = false;
    private ?int $port;
    private int $type;

    /** @var resource */
    private $socket;


    /**
     * Example:
     * <code>
     *  $relay = new SocketRelay("localhost", 7000);
     *  $relay = new SocketRelay("/tmp/rpc.sock", null, Socket::UNIX_SOCKET);
     * </code>
     *
     * @param string   $address Localhost, ip address or hostname.
     * @param int|null $port    Ignored for UNIX sockets.
     * @param int      $type    Default: TCP_SOCKET
     *
     * @throws Exception\InvalidArgumentException
     */
    public function __construct(string $address, ?int $port = null, int $type = self::SOCK_TCP)
    {
        if (!extension_loaded('sockets')) {
            throw new Exception\InvalidArgumentException("'sockets' extension not loaded");
        }

        switch ($type) {
            case self::SOCK_TCP:
                // TCP address should always be in lowercase
                $address = strtolower($address);

                if ($port === null) {
                    throw new Exception\InvalidArgumentException(sprintf(
                        "no port given for TPC socket on '%s'",
                        $address
                    ));
                }

                if ($port < 0 || $port > 65535) {
                    throw new Exception\InvalidArgumentException(sprintf(
                        "invalid port given for TPC socket on '%s'",
                        $address
                    ));
                }
                break;

            case self::SOCK_UNIX:
                $port = null;
                break;

            default:
                throw new Exception\InvalidArgumentException(sprintf(
                    "undefined connection type %s on '%s'",
                    $type,
                    $address
                ));
        }

        $this->address = $address;
        $this->port = $port;
        $this->type = $type;
    }

    /**
     * Destruct connection and disconnect.
     */
    public function __destruct()
    {
        if ($this->isConnected()) {
            $this->close();
        }
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        if ($this->type === self::SOCK_TCP) {
            return "tcp://{$this->address}:{$this->port}";
        }

        return "unix://{$this->address}";
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @return int|null
     */
    public function getPort(): ?int
    {
        return $this->port;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function isConnected(): bool
    {
        return $this->connected;
    }

    /**
     * @return Frame
     * @throws RelayException
     */
    public function waitFrame(): Frame
    {
        $this->connect();

        $header = '';
        $headerLength = socket_recv($this->socket, $header, 12, MSG_WAITALL);
        if ($header === null || $headerLength !== 12) {
            throw new Exception\HeaderException(sprintf(
                'unable to read frame header: %s',
                socket_strerror(socket_last_error($this->socket))
            ));
        }

        $parts = Frame::readHeader($header);

        // total payload length
        $payload = '';
        $length = $parts[1] * 4 + $parts[2];

        while ($length > 0) {
            $bufferLength = socket_recv($this->socket, $buffer, (int) $length, MSG_WAITALL);

            if ($bufferLength === false || $buffer === null) {
                throw new Exception\HeaderException(sprintf(
                    'unable to read payload from socket: %s',
                    socket_strerror(socket_last_error($this->socket))
                ));
            }

            $payload .= $buffer;
            $length -= $bufferLength;
        }

        return Frame::initFrame($parts, $payload);
    }

    /**
     * @param Frame $frame
     */
    public function send(Frame $frame): void
    {
        $this->connect();

        $body = Frame::packFrame($frame);

        if (socket_send($this->socket, $body, strlen($body), 0) === false) {
            throw new Exception\TransportException('unable to write payload to the stream');
        }
    }

    /**
     * Ensure socket connection. Returns true if socket successfully connected
     * or have already been connected.
     *
     * @return bool
     *
     * @throws Exception\RelayException
     * @throws Error When sockets are used in unsupported environment.
     */
    public function connect(): bool
    {
        if ($this->isConnected()) {
            return true;
        }

        $socket = $this->createSocket();
        if ($socket === false) {
            throw new Exception\RelayException("unable to create socket {$this}");
        }

        try {
            if (socket_connect($socket, $this->address, $this->port ?? 0) === false) {
                throw new Exception\RelayException(socket_strerror(socket_last_error($socket)));
            }
        } catch (\Exception $e) {
            throw new Exception\RelayException("unable to establish connection {$this}", 0, $e);
        }

        $this->socket = $socket;
        $this->connected = true;

        return true;
    }

    /**
     * Close connection.
     *
     * @throws Exception\RelayException
     */
    public function close(): void
    {
        if (!$this->isConnected()) {
            throw new Exception\RelayException("unable to close socket '{$this}', socket already closed");
        }

        socket_close($this->socket);
        $this->connected = false;
        unset($this->socket);
    }

    /**
     * @return resource|false
     * @throws Exception\GoridgeException
     */
    private function createSocket()
    {
        if ($this->type === self::SOCK_UNIX) {
            return socket_create(AF_UNIX, SOCK_STREAM, 0);
        }

        return socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    }
}
