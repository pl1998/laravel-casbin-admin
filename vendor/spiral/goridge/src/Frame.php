<?php

/**
 * Dead simple, high performance, drop-in bridge to Golang RPC with zero dependencies
 *
 * @author Wolfy-J
 */

declare(strict_types=1);

namespace Spiral\Goridge;

use Spiral\Goridge\Exception\InvalidArgumentException;

final class Frame
{
    // Current protocol version.
    public const VERSION = 0x01;

    // Frame type
    public const CONTROL = 0x01;
    public const ERROR   = 0x40;

    // BYTE flags, it means, that we can set multiply flags from this group using bitwise OR
    public const CODEC_RAW     = 0x04;
    public const CODEC_JSON    = 0x08;
    public const CODEC_MSGPACK = 0x10;
    public const CODEC_GOB     = 0x20;

    /** @var string|null */
    public ?string $payload;

    /** @var array<int> */
    public array $options = [];

    /** @var int */
    public int $flags;

    /**
     * @param string|null $body
     * @param array<int>  $options
     * @param int         $flags
     */
    public function __construct(?string $body, array $options = [], int $flags = 0)
    {
        $this->payload = $body;
        $this->options = $options;
        $this->flags = $flags;
    }

    /**
     * @param int ...$flag
     */
    public function setFlag(int ...$flag): void
    {
        foreach ($flag as $f) {
            if ($f > 255) {
                throw new InvalidArgumentException('Flags can be byte only');
            }

            $this->flags = $this->flags | $f;
        }
    }

    /**
     * @param int $flag
     * @return bool
     */
    public function hasFlag(int $flag): bool
    {
        if ($flag > 255) {
            throw new InvalidArgumentException('Flags can be byte only');
        }

        return ($this->flags & $flag) !== 0;
    }

    /**
     * @param int ...$options
     */
    public function setOptions(int ...$options): void
    {
        $this->options = $options;
    }

    /**
     * @param Frame $frame
     * @return string
     * @internal
     */
    public static function packFrame(Frame $frame): string
    {
        $header = pack(
            'CCL',
            self::VERSION << 4 | (count($frame->options) + 3),
            $frame->flags,
            strlen((string) $frame->payload)
        );

        if ($frame->options !== []) {
            $header .= pack('LCCL*', crc32($header), 0, 0, ...$frame->options);
        } else {
            $header .= pack('LCC', crc32($header), 0, 0);
        }

        return $header . (string) $frame->payload;
    }

    /**
     * Parse header and return [flags, num options, payload length].
     *
     * @param string $header 8 bytes.
     * @return array<int>
     * @internal
     */
    public static function readHeader(string $header): array
    {
        return [
            ord($header[1]),
            (ord($header[0]) & 0x0F) - 3,
            ord($header[2]) | ord($header[3]) << 8 | ord($header[4]) << 16 | ord($header[5]) << 24
        ];
    }

    /**
     * @param array<int> $header
     * @param string     $body
     * @return Frame
     * @internal
     */
    public static function initFrame(array $header, string $body): Frame
    {
        // optimize?
        $options = array_values(unpack('L*', substr($body, 0, $header[1] * 4)));
        return new self(
            substr($body, $header[1] * 4),
            $options ?? [],
            $header[0]
        );
    }
}
