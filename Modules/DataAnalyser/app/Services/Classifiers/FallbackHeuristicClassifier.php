<?php

namespace Modules\DataAnalyser\Services\Classifiers;

/**
 * Layer 5: last-resort heuristics — developer-looking domain shapes, bare
 * homepages, and search engine result pages.
 */
class FallbackHeuristicClassifier implements CategoryClassifier
{
    public function classify(string $url, string $domain, string $path, string $query, string $title): ?string
    {
        if (in_array($domain, ['medium.com', 'dev.to', 'hashnode.com'], true) || str_ends_with($domain, '.medium.com')) {
            return 'Development';
        }

        foreach (['docs.', 'developer.', 'developers.', 'api.', 'devcenter.', 'support.'] as $prefix) {
            if (str_starts_with($domain, $prefix)) {
                return 'Development';
            }
        }

        if (str_contains($domain, 'tutorial')) {
            return 'Development';
        }

        foreach (['.github.io', '.dev', '.readthedocs.io'] as $suffix) {
            if (str_ends_with($domain, $suffix)) {
                return 'Development';
            }
        }

        if ($path === '/' && $query === '') {
            return 'portals';
        }

        if (in_array($domain, ['msn.com', 'bing.com', 'google.com', 'duckduckgo.com', 'yahoo.com'], true)
            && ($path === '/' || str_starts_with($path, '/search'))) {
            return 'portals';
        }

        return null;
    }
}
