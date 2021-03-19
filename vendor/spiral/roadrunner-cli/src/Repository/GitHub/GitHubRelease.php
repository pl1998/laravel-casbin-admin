<?php

/**
 * This file is part of RoadRunner package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spiral\RoadRunner\Console\Repository\GitHub;

use Spiral\RoadRunner\Console\Repository\AssetsCollection;
use Spiral\RoadRunner\Console\Repository\Release;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * @psalm-import-type GitHubAssetApiResponse from GitHubAsset
 *
 * @psalm-type GitHubReleaseApiResponse = array {
 *      name: string,
 *      assets: array<array-key, GitHubAssetApiResponse>
 * }
 */
final class GitHubRelease extends Release
{
    /**
     * @var HttpClientInterface
     */
    private HttpClientInterface $client;

    /**
     * @param HttpClientInterface $client
     * @param string $name
     * @param string $repository
     * @param iterable|array $assets
     */
    public function __construct(HttpClientInterface $client, string $name, string $repository, iterable $assets = [])
    {
        $this->client = $client;

        parent::__construct($name, $repository, $assets);
    }

    /**
     * {@inheritDoc}
     */
    public function getConfig(): string
    {
        $config = \vsprintf('https://raw.githubusercontent.com/%s/%s/.rr.yaml', [
            $this->getRepositoryName(),
            $this->getName()
        ]);

        $response = $this->client->request('GET', $config);

        return $response->getContent();
    }

    /**
     * @param GitHubRepository $repository
     * @param HttpClientInterface $client
     * @param GitHubReleaseApiResponse $release
     * @return static
     */
    public static function fromApiResponse(GitHubRepository $repository, HttpClientInterface $client, array $release): self
    {
        if (! isset($release['name'])) {
            throw new \InvalidArgumentException(
                'Passed array must contain "name" value of type string'
            );
        }

        $instantiator = static function () use ($client, $release): \Generator {
            foreach ($release['assets'] ?? [] as $item) {
                yield GitHubAsset::fromApiResponse($client, $item);
            }
        };

        return new self($client, $release['name'], $repository->getName(), AssetsCollection::from($instantiator));
    }
}
