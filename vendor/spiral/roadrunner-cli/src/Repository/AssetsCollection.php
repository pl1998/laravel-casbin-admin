<?php

/**
 * This file is part of RoadRunner package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spiral\RoadRunner\Console\Repository;

/**
 * @template-extends Collection<AssetInterface>
 */
final class AssetsCollection extends Collection
{
    /**
     * @param string $arch
     * @return $this
     */
    public function whereArchitecture(string $arch): self
    {
        return $this->filter(static fn (AssetInterface $asset): bool =>
            \str_contains($asset->getName(), '-' . \strtolower($arch) . '.')
        );
    }

    /**
     * @param string $os
     * @return $this
     */
    public function whereOperatingSystem(string $os): self
    {
        return $this->filter(static fn (AssetInterface $asset): bool =>
            \str_contains($asset->getName(), '-' . \strtolower($os) . '-')
        );
    }
}
