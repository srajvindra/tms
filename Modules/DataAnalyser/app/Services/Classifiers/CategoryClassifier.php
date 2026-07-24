<?php

namespace Modules\DataAnalyser\Services\Classifiers;

interface CategoryClassifier
{
    /**
     * Return the matched category, or null to let the next classifier try.
     */
    public function classify(string $url, string $domain, string $path, string $query, string $title): ?string;
}
