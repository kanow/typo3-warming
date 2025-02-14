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

namespace EliasHaeussler\Typo3Warming\Tests\Acceptance\Backend\ContextMenu\ItemProviders;

use EliasHaeussler\Typo3Warming\Tests;

/**
 * CacheWarmupProviderCest
 *
 * @author Elias Häußler <elias@haeussler.dev>
 * @license GPL-2.0-or-later
 */
final class CacheWarmupProviderCest
{
    public function canSeeContextMenuItemsAsAdmin(
        Tests\Acceptance\Support\AcceptanceTester $I,
        Tests\Acceptance\Support\Helper\PageTree $pageTree,
    ): void {
        $I->loginAs('admin');

        $pageTree->openContextMenu(['Main']);

        $I->see('Warmup cache for this page', Tests\Acceptance\Support\Enums\Selectors::ContextMenu->value);
        $I->see('Warmup all caches', Tests\Acceptance\Support\Enums\Selectors::ContextMenu->value);
    }

    public function cannotSeeContextMenuItemsAsNonPermittedUser(
        Tests\Acceptance\Support\AcceptanceTester $I,
        Tests\Acceptance\Support\Helper\PageTree $pageTree,
    ): void {
        $I->loginAs('editor.1');

        $pageTree->openContextMenu(['Main']);

        $I->dontSee('Warmup cache for this page', Tests\Acceptance\Support\Enums\Selectors::ContextMenu->value);
        $I->dontSee('Warmup all caches', Tests\Acceptance\Support\Enums\Selectors::ContextMenu->value);
    }

    public function canSeeContextMenuItemsAsPermittedUser(
        Tests\Acceptance\Support\AcceptanceTester $I,
        Tests\Acceptance\Support\Helper\PageTree $pageTree,
    ): void {
        $I->loginAs('editor.2');

        $pageTree->openContextMenu(['Main']);

        $I->see('Warmup cache for this page', Tests\Acceptance\Support\Enums\Selectors::ContextMenu->value);
        $I->see('Warmup all caches', Tests\Acceptance\Support\Enums\Selectors::ContextMenu->value);
    }

    public function cannotSeeContextMenuItemsIfDisabledInExtensionConfiguration(
        Tests\Acceptance\Support\AcceptanceTester $I,
        Tests\Acceptance\Support\Helper\ExtensionConfiguration $extensionConfiguration,
        Tests\Acceptance\Support\Helper\PageTree $pageTree,
    ): void {
        $extensionConfiguration->write('enablePageTree', false);

        $I->loginAs('admin');

        $pageTree->openContextMenu(['Main']);

        $I->dontSee('Warmup cache for this page', Tests\Acceptance\Support\Enums\Selectors::ContextMenu->value);
        $I->dontSee('Warmup all caches', Tests\Acceptance\Support\Enums\Selectors::ContextMenu->value);

        $extensionConfiguration->write('enablePageTree', true);
    }

    public function cannotSeeContextMenuItemForSiteIfConfiguredSitemapIsInvalid(
        Tests\Acceptance\Support\AcceptanceTester $I,
        Tests\Acceptance\Support\Helper\PageTree $pageTree,
    ): void {
        $I->loginAs('admin');

        $pageTree->openContextMenu(['Root 2']);

        $I->see('Warmup cache for this page', Tests\Acceptance\Support\Enums\Selectors::ContextMenu->value);
        $I->dontSee('Warmup all caches', Tests\Acceptance\Support\Enums\Selectors::ContextMenu->value);
    }

    public function cannotSeeContextMenuItemForSiteIfNoSiteIsConfiguredForRootPage(
        Tests\Acceptance\Support\AcceptanceTester $I,
        Tests\Acceptance\Support\Helper\PageTree $pageTree,
    ): void {
        $I->loginAs('admin');

        $pageTree->openContextMenu(['Root 3']);

        $I->see('Warmup cache for this page', Tests\Acceptance\Support\Enums\Selectors::ContextMenu->value);
        $I->dontSee('Warmup all caches', Tests\Acceptance\Support\Enums\Selectors::ContextMenu->value);
    }

    public function cannotSeeContextMenuItemsIfNoLanguagesAreAllowed(
        Tests\Acceptance\Support\AcceptanceTester $I,
        Tests\Acceptance\Support\Helper\PageTree $pageTree,
    ): void {
        $I->loginAs('editor.3');

        $pageTree->openContextMenu(['Main']);

        $I->dontSee('Warmup cache for this page', Tests\Acceptance\Support\Enums\Selectors::ContextMenu->value);
        $I->dontSee('Warmup all caches', Tests\Acceptance\Support\Enums\Selectors::ContextMenu->value);
    }

    public function canSeeAllLanguagesAsAdmin(
        Tests\Acceptance\Support\AcceptanceTester $I,
        Tests\Acceptance\Support\Helper\PageTree $pageTree,
    ): void {
        $I->loginAs('admin');

        $pageTree->openContextMenu(['Main']);
        $pageTree->selectInContextMenu(['Warmup cache for this page']);

        $I->see('English', Tests\Acceptance\Support\Enums\Selectors::ContextMenuSubmenu->value);
        $I->see('German', Tests\Acceptance\Support\Enums\Selectors::ContextMenuSubmenu->value);
    }

    public function canSeeOnlyPermittedLanguagesAsPermittedUser(
        Tests\Acceptance\Support\AcceptanceTester $I,
        Tests\Acceptance\Support\Helper\PageTree $pageTree,
    ): void {
        $I->loginAs('editor.2');

        $pageTree->openContextMenu(['Main']);
        $pageTree->selectInContextMenu(['Warmup cache for this page']);

        $I->see('English', Tests\Acceptance\Support\Enums\Selectors::ContextMenuSubmenu->value);
        $I->dontSee('German', Tests\Acceptance\Support\Enums\Selectors::ContextMenuSubmenu->value);
    }

    public function canRunCacheWarmupForSite(
        Tests\Acceptance\Support\AcceptanceTester $I,
        Tests\Acceptance\Support\Helper\ModalDialog $modalDialog,
        Tests\Acceptance\Support\Helper\PageTree $pageTree,
    ): void {
        $I->loginAs('admin');

        $pageTree->openContextMenu(['Main']);
        $pageTree->selectInContextMenu(['Warmup all caches', 'English']);

        $modalDialog->canSeeDialog();

        $I->canSee('Cache warmup is in progress', Tests\Acceptance\Support\Enums\Selectors::ModalTitle->value);
        $I->waitForElementNotVisible(Tests\Acceptance\Support\Enums\Selectors::ProgressPlaceholder->value);

        $modalDialog->clickButtonInDialog('Close');
    }

    public function canRunCacheWarmupForPage(
        Tests\Acceptance\Support\AcceptanceTester $I,
        Tests\Acceptance\Support\Helper\ModalDialog $modalDialog,
        Tests\Acceptance\Support\Helper\PageTree $pageTree,
    ): void {
        $I->loginAs('admin');

        $pageTree->openContextMenu(['Main', 'Subsite 1']);
        $pageTree->selectInContextMenu(['Warmup cache for this page', 'English']);

        $modalDialog->canSeeDialog();

        $I->canSee('Cache warmup is in progress', Tests\Acceptance\Support\Enums\Selectors::ModalTitle->value);
        $I->waitForElementNotVisible(Tests\Acceptance\Support\Enums\Selectors::ProgressPlaceholder->value);

        $modalDialog->clickButtonInDialog('Close');
    }
}
