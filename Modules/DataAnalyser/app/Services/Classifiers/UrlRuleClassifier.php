<?php

namespace Modules\DataAnalyser\Services\Classifiers;

/**
 * Layer 1: hardcoded URL-shape rules for sites where the URL structure alone
 * identifies the category (profile pages, product pages, job listings).
 */
class UrlRuleClassifier implements CategoryClassifier
{
    public function classify(string $url, string $domain, string $path, string $query, string $title): ?string
    {
        if (str_contains($domain, 'wiktionary.org')) {
            return 'vocab';
        }

        if ($domain === 'linkedin.com') {
            if (str_starts_with($path, '/in/')) {
                return 'individuals';
            }
            if (str_starts_with($path, '/jobs')) {
                return 'Career';
            }
        }

        if (in_array($domain, ['x.com', 'twitter.com', 'instagram.com'], true)
            && preg_match('~^/[A-Za-z0-9_.]+/?$~', $path)) {
            return 'individuals';
        }

        if (in_array($domain, ['amazon.in', 'amazon.com'], true)
            && (str_contains($path, '/dp/') || str_contains($path, '/gp/product')
                || str_contains($url, '/s?') || str_starts_with($path, '/s/'))) {
            return 'shop';
        }

        if ($domain === 'walmart.com' && str_contains($path, '/ip/')) {
            return 'shop';
        }

        return null;
    }
}
