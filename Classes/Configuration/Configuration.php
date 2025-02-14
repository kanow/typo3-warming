<?php

declare(strict_types=1);

/*
 * This file is part of the TYPO3 CMS extension "warming".
 *
 * Copyright (C) 2023 Elias Häußler <elias@haeussler.dev>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */

namespace EliasHaeussler\Typo3Warming\Configuration;

use EliasHaeussler\CacheWarmup;
use EliasHaeussler\Typo3Warming\Crawler;
use EliasHaeussler\Typo3Warming\Extension;
use TYPO3\CMS\Core;
use TYPO3\CMS\Extbase;

/**
 * Configuration
 *
 * @author Elias Häußler <elias@haeussler.dev>
 * @license GPL-2.0-or-later
 */
final class Configuration
{
    private const DEFAULT_CRAWLER = Crawler\ConcurrentUserAgentCrawler::class;
    private const DEFAULT_VERBOSE_CRAWLER = Crawler\OutputtingUserAgentCrawler::class;
    private const DEFAULT_LIMIT = 250;
    private const DEFAULT_SUPPORTED_DOKTYPES = [
        Core\Domain\Repository\PageRepository::DOKTYPE_DEFAULT,
    ];

    private readonly string $userAgent;

    public function __construct(
        private readonly Core\Configuration\ExtensionConfiguration $configuration,
        private readonly CacheWarmup\Crawler\CrawlerFactory $crawlerFactory,
        private readonly Crawler\Strategy\CrawlingStrategyFactory $crawlingStrategyFactory,
        private readonly Extbase\Security\Cryptography\HashService $hashService,
    ) {
        $this->userAgent = $this->generateUserAgent();
    }

    /**
     * @return class-string<CacheWarmup\Crawler\CrawlerInterface>
     */
    public function getCrawler(): string
    {
        try {
            /** @var class-string<CacheWarmup\Crawler\CrawlerInterface>|null $crawler */
            $crawler = $this->configuration->get(Extension::KEY, 'crawler');

            if (!\is_string($crawler)) {
                return self::DEFAULT_CRAWLER;
            }

            if (!is_a($crawler, CacheWarmup\Crawler\CrawlerInterface::class, true)) {
                return self::DEFAULT_CRAWLER;
            }

            return $crawler;
        } catch (Core\Exception) {
            return self::DEFAULT_CRAWLER;
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function getCrawlerOptions(): array
    {
        try {
            $json = $this->configuration->get(Extension::KEY, 'crawlerOptions');

            // Early return if no crawler options are configured
            if (!\is_string($json) || $json === '') {
                return [];
            }

            return $this->crawlerFactory->parseCrawlerOptions($json);
        } catch (Core\Exception) {
            return [];
        }
    }

    /**
     * @return class-string<CacheWarmup\Crawler\VerboseCrawlerInterface>
     */
    public function getVerboseCrawler(): string
    {
        try {
            /** @var class-string<CacheWarmup\Crawler\VerboseCrawlerInterface>|null $crawler */
            $crawler = $this->configuration->get(Extension::KEY, 'verboseCrawler');

            if (!\is_string($crawler)) {
                return self::DEFAULT_VERBOSE_CRAWLER;
            }

            if (!is_a($crawler, CacheWarmup\Crawler\VerboseCrawlerInterface::class, true)) {
                return self::DEFAULT_VERBOSE_CRAWLER;
            }

            return $crawler;
        } catch (Core\Exception) {
            return self::DEFAULT_VERBOSE_CRAWLER;
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function getVerboseCrawlerOptions(): array
    {
        try {
            $json = $this->configuration->get(Extension::KEY, 'verboseCrawlerOptions');

            // Early return if no crawler options are configured
            if (!\is_string($json) || $json === '') {
                return [];
            }

            return $this->crawlerFactory->parseCrawlerOptions($json);
        } catch (Core\Exception) {
            return [];
        }
    }

    public function getLimit(): int
    {
        try {
            $limit = $this->configuration->get(Extension::KEY, 'limit');

            if (!is_numeric($limit)) {
                return self::DEFAULT_LIMIT;
            }

            return abs((int)$limit);
        } catch (Core\Exception) {
            return self::DEFAULT_LIMIT;
        }
    }

    /**
     * @return list<string>
     */
    public function getExcludePatterns(): array
    {
        try {
            $exclude = $this->configuration->get(Extension::KEY, 'exclude');

            // Early return if no exclude patterns are configured
            if (!\is_string($exclude) || $exclude === '') {
                return [];
            }

            return Core\Utility\GeneralUtility::trimExplode(',', $exclude, true);
        } catch (Core\Exception) {
            return [];
        }
    }

    public function getStrategy(): ?string
    {
        try {
            $strategy = $this->configuration->get(Extension::KEY, 'strategy');

            // Early return if no crawling strategy is configured
            if (!\is_string($strategy) || $strategy === '') {
                return null;
            }

            // Early return if configured crawling strategy is invalid
            if (!$this->crawlingStrategyFactory->has($strategy)) {
                return null;
            }

            return $strategy;
        } catch (Core\Exception) {
            return null;
        }
    }

    public function isEnabledInPageTree(): bool
    {
        try {
            $enablePageTree = $this->configuration->get(Extension::KEY, 'enablePageTree');

            return (bool)$enablePageTree;
        } catch (Core\Exception) {
            return true;
        }
    }

    /**
     * @return list<int>
     */
    public function getSupportedDoktypes(): array
    {
        try {
            $doktypes = $this->configuration->get(Extension::KEY, 'supportedDoktypes');

            if (!\is_string($doktypes)) {
                return self::DEFAULT_SUPPORTED_DOKTYPES;
            }

            return array_values(Core\Utility\GeneralUtility::intExplode(',', $doktypes, true));
        } catch (Core\Exception) {
            return self::DEFAULT_SUPPORTED_DOKTYPES;
        }
    }

    public function isEnabledInToolbar(): bool
    {
        try {
            $enableToolbar = $this->configuration->get(Extension::KEY, 'enableToolbar');

            return (bool)$enableToolbar;
        } catch (Core\Exception) {
            return true;
        }
    }

    public function getUserAgent(): string
    {
        return $this->userAgent;
    }

    private function generateUserAgent(): string
    {
        return $this->hashService->appendHmac('TYPO3/tx_warming_crawler');
    }
}
