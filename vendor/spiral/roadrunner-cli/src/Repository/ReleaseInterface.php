<?php

/**
 * This file is part of RoadRunner package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spiral\RoadRunner\Console\Repository;

use JetBrains\PhpStorm\ExpectedValues;
use Spiral\RoadRunner\Console\Environment\Stability;

/**
 * @psalm-import-type StabilityType from Stability
 */
interface ReleaseInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getVersion(): string;

    /**
     * @return string
     */
    public function getRepositoryName(): string;

    /**
     * @return StabilityType
     */
    #[ExpectedValues(valuesFromClass: Stability::class)]
    public function getStability(): string;

    /**
     * @return AssetsCollection
     */
    public function getAssets(): AssetsCollection;

    /**
     * @param string $constraint
     * @return bool
     */
    public function satisfies(string $constraint): bool;

    /**
     * @return string
     */
    public function getConfig(): string;
}
