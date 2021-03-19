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
 * Blocking, duplex relay.
 */
interface RelayInterface
{
    /**
     * @return Frame
     * @throws RelayException
     */
    public function waitFrame(): Frame;

    /**
     * @param Frame $frame
     */
    public function send(Frame $frame): void;
}
