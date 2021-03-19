<?php

/**
 * Dead simple, high performance, drop-in bridge to Golang RPC with zero dependencies
 *
 * @author Wolfy-J
 */

declare(strict_types=1);

namespace Spiral\Goridge;

use Throwable;

abstract class Relay implements RelayInterface
{
    public const TCP_SOCKET = 'tcp';
    public const UNIX_SOCKET = 'unix';
    public const PIPES = 'pipes';
    protected const CONNECTION_EXP = '/(?P<protocol>[^:\/]+):\/\/(?P<arg1>[^:]+)(:(?P<arg2>[^:]+))?/';

    /**
     * Create relay using string address.
     *
     * Example:
     *
     * Relay::create("pipes");
     * Relay::create("tpc://localhost:6001");
     *
     *
     * @param string $connection
     * @return RelayInterface
     */
    public static function create(string $connection): RelayInterface
    {
        if ($connection === self::PIPES) {
            return new StreamRelay(STDIN, STDOUT);
        }

        if (!preg_match(self::CONNECTION_EXP, $connection, $match)) {
            throw new Exception\RelayFactoryException('unsupported connection format');
        }

        $protocol = strtolower($match['protocol']);

        switch ($protocol) {
            case self::TCP_SOCKET:
                //fall through
            case self::UNIX_SOCKET:
                $socketType = $protocol === self::TCP_SOCKET
                    ? SocketRelay::SOCK_TCP
                    : SocketRelay::SOCK_UNIX;

                $port = isset($match['arg2'])
                    ? (int)$match['arg2']
                    : null;

                return new SocketRelay($match['arg1'], $port, $socketType);

            case self::PIPES:
                if (!isset($match['arg2'])) {
                    throw new Exception\RelayFactoryException('unsupported stream connection format');
                }

                return new StreamRelay(self::openIn($match['arg1']), self::openOut($match['arg2']));
            default:
                throw new Exception\RelayFactoryException('unknown connection protocol');
        }
    }

    /**
     * @param string $input
     * @return resource
     */
    private static function openIn(string $input)
    {
        try {
            $resource = fopen("php://$input", 'rb');
            if ($resource === false) {
                throw new Exception\RelayFactoryException('could not initiate `in` stream resource');
            }

            return $resource;
        } catch (Throwable $e) {
            throw new Exception\RelayFactoryException(
                'could not initiate `in` stream resource',
                $e->getCode(),
                $e
            );
        }
    }

    /**
     * @param string $output
     * @return resource
     */
    private static function openOut(string $output)
    {
        try {
            $resource = fopen("php://$output", 'wb');
            if ($resource === false) {
                throw new Exception\RelayFactoryException('could not initiate `out` stream resource');
            }

            return $resource;
        } catch (Throwable $e) {
            throw new Exception\RelayFactoryException(
                'could not initiate `out` stream resource',
                $e->getCode(),
                $e
            );
        }
    }
}
