<?php

declare(strict_types=1);

/*
 * This file is part of the TYPO3 CMS extension "warming".
 *
 * Copyright (C) 2021 Elias Häußler <elias@haeussler.dev>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */

namespace EliasHaeussler\Typo3Warming\Command;

use EliasHaeussler\CacheWarmup\Command\CacheWarmupCommand;
use EliasHaeussler\Typo3Warming\Configuration\Configuration;
use EliasHaeussler\Typo3Warming\Crawler\OutputtingUserAgentCrawler;
use EliasHaeussler\Typo3Warming\Exception\UnsupportedConfigurationException;
use EliasHaeussler\Typo3Warming\Exception\UnsupportedSiteException;
use EliasHaeussler\Typo3Warming\Service\CacheWarmupService;
use EliasHaeussler\Typo3Warming\Sitemap\SiteAwareSitemap;
use EliasHaeussler\Typo3Warming\Sitemap\SitemapLocator;
use Psr\Http\Message\UriInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Exception\SiteNotFoundException;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;

/**
 * WarmupCommand
 *
 * @author Elias Häußler <elias@haeussler.dev>
 * @license GPL-2.0-or-later
 */
class WarmupCommand extends Command
{
    /**
     * @var CacheWarmupService
     */
    protected $warmupService;

    /**
     * @var Configuration
     */
    protected $configuration;

    /**
     * @var SitemapLocator
     */
    protected $sitemapLocator;

    /**
     * @var SiteFinder
     */
    protected $siteFinder;

    public function __construct(
        CacheWarmupService $warmupService,
        Configuration $configuration,
        SitemapLocator $sitemapLocator,
        SiteFinder $siteFinder,
        string $name = null
    ) {
        parent::__construct($name);

        $this->warmupService = $warmupService;
        $this->configuration = $configuration;
        $this->sitemapLocator = $sitemapLocator;
        $this->siteFinder = $siteFinder;
    }

    protected function configure(): void
    {
        $this->setDescription('Warm up Frontend caches of single pages and/or whole sites using their XML sitemaps.');

        $this->addOption(
            'pages',
            'p',
            InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
            'Pages whose Frontend caches are to be warmed up.'
        );
        $this->addOption(
            'sites',
            's',
            InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
            'Site identifiers or root page IDs of sites whose caches are to be warmed up.'
        );
        $this->addOption(
            'languages',
            'l',
            InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
            'Optional identifiers of languages for which caches are to be warmed up'
        );
        $this->addOption(
            'strict',
            'x',
            InputOption::VALUE_NONE,
            'Fail if an error occurred during cache warmup.'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $languages = iterator_to_array($this->resolveLanguages($input->getOption('languages')));
        $urls = array_unique(iterator_to_array($this->resolveUrls($input->getOption('pages'), $languages)));
        $sitemaps = array_unique(iterator_to_array($this->resolveSitemaps($input->getOption('sites'), $languages)), SORT_REGULAR);

        // Exit if neither pages nor sites are given
        if (count($urls) + count($sitemaps) === 0) {
            $io->error('You need to define at least one page or site.');
            return 1;
        }

        $io->writeln('Running <info>cache warmup</info> by <info>Elias Häußler</info> and contributors.');

        // Initialize crawler
        $crawler = $this->configuration->getVerboseCrawler();
        if (empty($crawler)) {
            $crawler = OutputtingUserAgentCrawler::class;
        }

        // Initialize application
        $application = $this->getApplication();
        if ($application === null) {
            $application = new Application();
            $application->add($this);
        }

        // Run cache warmup in sub command from eliashaeussler/cache-warmup
        $subCommand = $application->add(new CacheWarmupCommand());
        $subCommandParameters = [
            'sitemaps' => $sitemaps,
            '--urls' => $urls,
            '--limit' => $this->configuration->getLimit(),
            '--crawler' => $crawler,
        ];
        $subCommandInput = new ArrayInput($subCommandParameters);
        $returnCode = $subCommand->run($subCommandInput, $output);

        // Fail if strict mode is enabled and at least one crawl was erroneous
        if ($input->getOption('strict') && $returnCode > 0) {
            return 2;
        }

        return 0;
    }

    /**
     * @param string[]|int[] $pages
     * @param int[] $languages
     * @return \Generator<UriInterface>
     * @throws SiteNotFoundException
     */
    protected function resolveUrls(array $pages, array $languages): \Generator
    {
        foreach ($languages as $languageId) {
            foreach ($pages as $pageList) {
                foreach (GeneralUtility::intExplode(',', (string)$pageList, true) as $page) {
                    yield $this->warmupService->generateUri($page, $languageId);
                }
            }
        }
    }

    /**
     * @param string[]|int[] $sites
     * @param int[] $languages
     * @return \Generator<SiteAwareSitemap>
     * @throws SiteNotFoundException
     * @throws UnsupportedConfigurationException
     * @throws UnsupportedSiteException
     */
    protected function resolveSitemaps(array $sites, array $languages): \Generator
    {
        foreach ($languages as $languageId) {
            foreach ($sites as $siteList) {
                foreach (GeneralUtility::trimExplode(',', $siteList, true) as $site) {
                    if (MathUtility::canBeInterpretedAsInteger($site)) {
                        $site = $this->siteFinder->getSiteByRootPageId((int)$site);
                    } else {
                        $site = $this->siteFinder->getSiteByIdentifier($site);
                    }
                    yield $this->sitemapLocator->locateBySite($site, $site->getLanguageById($languageId));
                }
            }
        }
    }

    /**
     * @param string[]|int[] $languages
     * @return \Generator<int>
     */
    protected function resolveLanguages(array $languages): \Generator
    {
        if ([] === $languages) {
            yield 0;
            return;
        }

        foreach ($languages as $languageList) {
            foreach (GeneralUtility::intExplode(',', (string)$languageList, true) as $languageId) {
                yield $languageId;
            }
        }
    }
}
