<?php

declare(strict_types=1);

namespace Casbin\Bridge\Logger;

use Casbin\Log\Logger;
use Psr\Log\LoggerInterface;

class LoggerBridge implements Logger
{
    /**
     * Whether to enable logging.
     *
     * @var bool
     */
    public $enable = false;

    /**
     * The log level.
     *
     * @var mixed
     */
    protected $defaultLevel;

    /**
     * The logger instance.
     *
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * LoggerBridge constructor.
     *
     * @param LoggerInterface $logger
     * @param string          $defaultLevel
     */
    public function __construct(LoggerInterface $logger, $defaultLevel = 'info')
    {
        $this->logger = $logger;
        $this->defaultLevel = $defaultLevel;
    }

    /**
     * controls whether print the message.
     *
     * @param bool $enable
     */
    public function enableLog(bool $enable): void
    {
        $this->enable = $enable;
    }

    /**
     * returns if logger is enabled.
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enable;
    }

    /**
     * formats using the default formats for its operands and logs the message.
     *
     * @param mixed ...$v
     */
    public function write(...$v): void
    {
        if (!$this->enable) {
            return;
        }

        $content = '';

        foreach ($v as $value) {
            if (\is_array($value) || \is_object($value)) {
                $value = json_encode($value);
            }

            $content .= $value;
        }

        $this->logger->log($this->defaultLevel, $content);
    }

    /**
     * formats according to a format specifier and logs the message.
     *
     * @param string $format
     * @param mixed  ...$v
     */
    public function writef(string $format, ...$v): void
    {
        if (!$this->enable) {
            return;
        }

        $this->logger->log($this->defaultLevel, sprintf($format, ...$v));
    }
}
