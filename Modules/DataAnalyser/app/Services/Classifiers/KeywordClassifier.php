<?php

namespace Modules\DataAnalyser\Services\Classifiers;

use Modules\DataAnalyser\Models\Keyword;

/**
 * Layer 4: matches the page title against the per-category keyword regexes,
 * in matching priority order.
 */
class KeywordClassifier implements CategoryClassifier
{
    /** @var array<string, string>|null */
    private ?array $patterns;

    /**
     * @param  array<string, string>|null  $patterns  Category name => regex, in matching priority order.
     *                                                When null, keywords are loaded from the database
     *                                                on first use.
     */
    public function __construct(?array $patterns = null)
    {
        $this->patterns = $patterns;
    }

    public function classify(string $url, string $domain, string $path, string $query, string $title): ?string
    {
        return $this->match($title);
    }

    /**
     * Return the first category whose pattern matches the given text, if any.
     */
    public function match(string $text): ?string
    {
        foreach ($this->patterns() as $category => $pattern) {
            if (preg_match($pattern, $text)) {
                return $category;
            }
        }

        return null;
    }

    /**
     * @return array<string, string>
     */
    public function patterns(): array
    {
        return $this->patterns ??= $this->loadPatternsFromDatabase();
    }

    /**
     * @return array<string, string>
     */
    private function loadPatternsFromDatabase(): array
    {
        return Keyword::query()
            ->active()
            ->where('type', 'title')
            ->orderBy('sort_order')
            ->with('category')
            ->get()
            ->mapWithKeys(fn (Keyword $keyword) => [$keyword->category->name => $keyword->pattern])
            ->all();
    }
}
