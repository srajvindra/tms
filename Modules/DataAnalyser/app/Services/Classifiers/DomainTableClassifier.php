<?php

namespace Modules\DataAnalyser\Services\Classifiers;

use Modules\DataAnalyser\Models\DomainCategory;

/**
 * Layer 2: looks the host up in the curated domain tables, matching exact
 * hosts and parent-domain suffixes, in category priority order.
 */
class DomainTableClassifier implements CategoryClassifier
{
    /** @var array<int, array{0: string, 1: array<string, true>}>|null */
    private ?array $domainCategories;

    /**
     * @param  array<string, list<string>>|null  $domainsByCategory  Category name => hosts, in matching
     *                                                               priority order. When null, domains are
     *                                                               loaded from the database on first use.
     */
    public function __construct(?array $domainsByCategory = null)
    {
        $this->domainCategories = $domainsByCategory === null
            ? null
            : $this->buildDomainCategories($domainsByCategory);
    }

    public function classify(string $url, string $domain, string $path, string $query, string $title): ?string
    {
        foreach ($this->domainCategories() as [$category, $domains]) {
            if (isset($domains[$domain])) {
                return $category;
            }

            foreach ($domains as $known => $unused) {
                if (str_ends_with($domain, '.'.$known)) {
                    return $category;
                }
            }
        }

        return null;
    }

    /**
     * @return array<int, array{0: string, 1: array<string, true>}>
     */
    private function domainCategories(): array
    {
        return $this->domainCategories ??= $this->loadDomainCategoriesFromDatabase();
    }

    /**
     * @return array<int, array{0: string, 1: array<string, true>}>
     */
    private function loadDomainCategoriesFromDatabase(): array
    {
        $categories = DomainCategory::query()
            ->active()
            ->orderBy('sort_order')
            ->with(['domains' => fn ($query) => $query->active()])
            ->get();

        $map = [];

        foreach ($categories as $category) {
            $map[] = [$category->name, array_fill_keys($category->domains->pluck('host')->all(), true)];
        }

        return $map;
    }

    /**
     * @param  array<string, list<string>>  $domainsByCategory
     * @return array<int, array{0: string, 1: array<string, true>}>
     */
    private function buildDomainCategories(array $domainsByCategory): array
    {
        $map = [];

        foreach ($domainsByCategory as $category => $hosts) {
            $map[] = [$category, array_fill_keys($hosts, true)];
        }

        return $map;
    }
}
