<?php

namespace Modules\DataAnalyser\Services;

use Modules\DataAnalyser\Models\DomainCategory;
use Modules\DataAnalyser\Services\Classifiers\CategoryClassifier;
use Modules\DataAnalyser\Services\Classifiers\DomainTableClassifier;
use Modules\DataAnalyser\Services\Classifiers\FallbackHeuristicClassifier;
use Modules\DataAnalyser\Services\Classifiers\KeywordClassifier;
use Modules\DataAnalyser\Services\Classifiers\UrlRuleClassifier;
use Modules\DataAnalyser\Services\Classifiers\WikipediaTitleClassifier;

/**
 * Orchestrates the classification pipeline: each layer is a dedicated
 * classifier service, tried in priority order until one returns a category.
 */
class UrlClassifier
{
    /** @var list<CategoryClassifier> */
    private array $classifiers;

    /** @var list<string>|null */
    private ?array $categories;

    /**
     * @param  array<string, list<string>>|null  $domainsByCategory  Category name => hosts, in matching
     *                                                               priority order. When null, domains are
     *                                                               loaded from the database on first use.
     * @param  array<string, string>|null  $keywordPatterns  Category name => regex, in matching priority
     *                                                       order. When null, keywords are loaded from
     *                                                       the database on first use.
     * @param  array<string, array{category: string, pattern: string}>|null  $wikiPatterns  Wikipedia rule
     *                                                                                      type => category and regex. When null, they
     *                                                                                      are loaded from the database on first use.
     * @param  list<string>|null  $categories  All category names. When null, they are loaded
     *                                         from the database on first use.
     */
    public function __construct(
        ?array $domainsByCategory = null,
        ?array $keywordPatterns = null,
        ?array $wikiPatterns = null,
        ?array $categories = null,
    ) {
        $keywordClassifier = new KeywordClassifier($keywordPatterns);

        $this->classifiers = [
            new UrlRuleClassifier,
            new DomainTableClassifier($domainsByCategory),
            new WikipediaTitleClassifier($keywordClassifier, $wikiPatterns),
            $keywordClassifier,
            new FallbackHeuristicClassifier,
        ];

        $this->categories = $categories;
    }

    /**
     * All category names, in matching priority order.
     *
     * @return list<string>
     */
    public function categories(): array
    {
        return $this->categories ??= DomainCategory::activeNames();
    }

    /**
     * Classify a single URL + title into one of the categories.
     */
    public function classify(string $url, string $title): string
    {
        $parts = parse_url($url) ?: [];
        $domain = strtolower($parts['host'] ?? '');

        if (str_starts_with($domain, 'www.')) {
            $domain = substr($domain, 4);
        }

        $path = ($parts['path'] ?? '') !== '' ? $parts['path'] : '/';
        $query = $parts['query'] ?? '';

        foreach ($this->classifiers as $classifier) {
            if ($category = $classifier->classify($url, $domain, $path, $query, $title)) {
                return $category;
            }
        }

        return 'Other';
    }

    /**
     * Classify tab-separated lines of "url<TAB>title".
     *
     * @return array{counts: array<string, int>, rows: list<array{category: string, url: string, title: string}>}
     */
    public function classifyTsv(string $content): array
    {
        $counts = array_fill_keys($this->categories(), 0);
        $rows = [];

        foreach (preg_split('~\r\n|\r|\n~', $content) as $line) {
            if (trim($line) === '') {
                continue;
            }

            $parts = explode("\t", $line);
            $url = trim($parts[0]);
            $title = trim($parts[1] ?? '');

            $category = $this->classify($url, $title !== '' ? $title : $url);
            $counts[$category] = ($counts[$category] ?? 0) + 1;
            $rows[] = ['category' => $category, 'url' => $url, 'title' => $title];
        }

        return ['counts' => $counts, 'rows' => $rows];
    }
}
