<?php

/**
 * This file is part of RoadRunner package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spiral\RoadRunner\Internal;

use Psr\Log\LoggerInterface;
use Symfony\Component\VarDumper\Dumper\AbstractDumper;
use Symfony\Component\VarDumper\Dumper\CliDumper;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;

/**
 * @internal StdoutHandler is an internal library class, please do not use it in your code.
 * @psalm-internal Spiral\RoadRunner
 */
final class StdoutHandler
{
    /**
     * @var string
     */
    private const ERROR_WRITING_HEADER =
        'Could not explicitly send a headers "%s" using PHP header() function. ' .
        'Please use RoadRunner response object instead';

    /**
     * @var positive-int
     */
    private const OB_CHUNK_SIZE = 10 * 1024;

    /**
     * @param LoggerInterface $logger
     * @param positive-int|0 $chunkSize
     */
    public static function register(LoggerInterface $logger, int $chunkSize = self::OB_CHUNK_SIZE): void
    {
        assert($chunkSize >= 0, 'Invalid chunk size argument value');

        self::restreamOutputBuffer($logger, $chunkSize);
        self::restreamHeaders($logger);

        // Vendor packages
        self::restreamSymfonyDumper();
    }

    /**
     * Intercept all output headers writing.
     *
     * @param LoggerInterface $logger
     * @return void
     */
    private static function restreamHeaders(LoggerInterface $logger): void
    {
        \header_register_callback(static function() use ($logger): void {
            $headers = \headers_list();

            if ($headers !== []) {
                $logger->warning(self::ERROR_WRITING_HEADER, $headers);
            }
        });
    }

    /**
     * Intercept all output buffer write.
     *
     * @param LoggerInterface $logger
     * @param positive-int|0 $chunkSize
     * @return void
     */
    private static function restreamOutputBuffer(LoggerInterface $logger, int $chunkSize): void
    {
        \ob_start(static function (string $chunk, int $phase) use ($logger): void {
            $isWrite = ($phase & \PHP_OUTPUT_HANDLER_WRITE) === \PHP_OUTPUT_HANDLER_WRITE;

            if ($isWrite && $chunk !== '') {
                $logger->debug($chunk);
            }
        }, $chunkSize);
    }

    /**
     * @return void
     */
    private static function restreamSymfonyDumper(): void
    {
        if (\class_exists(AbstractDumper::class)) {
            AbstractDumper::$defaultOutput = 'php://stderr';

            CliDumper::$defaultOutput = 'php://stderr';
            HtmlDumper::$defaultOutput = 'php://stderr';
        }
    }
}
