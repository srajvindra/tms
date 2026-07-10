<?php

namespace Modules\DataAnalyser\Services\Classifiers;

use Modules\DataAnalyser\Models\Keyword;

/**
 * Layer 3: Wikipedia pages carry no signal in the URL, so the article title
 * is classified with dedicated wiki patterns and a person-name heuristic.
 * Always resolves wikipedia.org hosts (falling back to "Other"), so later
 * layers never see them.
 */
class WikipediaTitleClassifier implements CategoryClassifier
{
    private const PERSON_STOP = 'the of and in on at a an for to institute university college school district city state river
mountain lake temple fort palace museum award prize war battle history language film movie series album song period migration power apache query
station railway airport bridge road street park garden festival dynasty empire kingdom party election
constituency operation mission project program act bill treaty agreement';

    /** @var array<string, array{category: string, pattern: string}>|null */
    private ?array $wikiPatterns;

    /** @var array<string, true> */
    private array $personStopWords;

    /**
     * @param  array<string, array{category: string, pattern: string}>|null  $wikiPatterns  Wikipedia rule
     *                                                                                      type => category and regex. When null, they
     *                                                                                      are loaded from the database on first use.
     */
    public function __construct(
        private readonly KeywordClassifier $keywords,
        ?array $wikiPatterns = null,
    ) {
        $this->wikiPatterns = $wikiPatterns;
        $this->personStopWords = $this->toWordSet(self::PERSON_STOP);
    }

    public function classify(string $url, string $domain, string $path, string $query, string $title): ?string
    {
        if (! str_contains($domain, 'wikipedia.org')) {
            return null;
        }

        return $this->classifyTitle($title);
    }

    private function classifyTitle(string $title): string
    {
        $wiki = $this->wikiPatterns();

        if (isset($wiki['wiki_skip']) && preg_match($wiki['wiki_skip']['pattern'], $title)) {
            return $wiki['wiki_skip']['category'];
        }

        $base = str_replace(' - Wikipedia', '', $title);

        $patterns = $this->keywords->patterns();

        if (isset($patterns['AI']) && preg_match($patterns['AI'], $base)) {
            return 'AI';
        }

        if (isset($wiki['wiki_tech']) && preg_match($wiki['wiki_tech']['pattern'], $base)) {
            return $wiki['wiki_tech']['category'];
        }

        if (isset($wiki['wiki_corp']) && preg_match($wiki['wiki_corp']['pattern'], $base)) {
            return $wiki['wiki_corp']['category'];
        }

        if ($this->isPersonTitle($base)) {
            return 'individuals';
        }

        if ($category = $this->keywords->match($base)) {
            return $category;
        }

        if (isset($wiki['wiki_place']) && preg_match($wiki['wiki_place']['pattern'], $base)) {
            return $wiki['wiki_place']['category'];
        }

        $words = preg_split('~\s+~', trim($base), -1, PREG_SPLIT_NO_EMPTY);

        if (count($words) === 1 && $base !== '') {
            $isAllUpper = strtoupper($base) === $base && preg_match('~[A-Za-z]~', $base);
            $startsUpper = preg_match('~^\p{Lu}~u', $base) === 1;

            if ($isAllUpper || ($startsUpper && ! isset($this->personStopWords[strtolower($base)]))) {
                return 'private or corporate';
            }
        }

        return 'Other';
    }

    /**
     * Heuristic: a 2-4 word Wikipedia title of capitalised, digit-free words
     * that avoids stop words and corp/tech vocabulary is likely a person.
     */
    private function isPersonTitle(string $title): bool
    {
        $title = trim(str_replace(' - Wikipedia', '', $title));
        $words = preg_split('~\s+~', $title, -1, PREG_SPLIT_NO_EMPTY);
        $wordCount = count($words);

        if ($wordCount < 2 || $wordCount > 4) {
            return false;
        }

        foreach ($words as $word) {
            $normalised = strtolower(trim($word, '.,()'));

            if (isset($this->personStopWords[$normalised])) {
                return false;
            }

            if (! preg_match('~^\p{Lu}~u', $word) || preg_match('~\d~', $word)) {
                return false;
            }
        }

        $wiki = $this->wikiPatterns();

        foreach (['wiki_corp', 'wiki_tech'] as $type) {
            if (isset($wiki[$type]) && preg_match($wiki[$type]['pattern'], $title)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return array<string, array{category: string, pattern: string}>
     */
    private function wikiPatterns(): array
    {
        return $this->wikiPatterns ??= $this->loadWikiPatternsFromDatabase();
    }

    /**
     * @return array<string, array{category: string, pattern: string}>
     */
    private function loadWikiPatternsFromDatabase(): array
    {
        return Keyword::query()
            ->active()
            ->where('type', '!=', 'title')
            ->orderBy('sort_order')
            ->with('category')
            ->get()
            ->mapWithKeys(fn (Keyword $keyword) => [
                $keyword->type => [
                    'category' => $keyword->category->name,
                    'pattern' => $keyword->pattern,
                ],
            ])
            ->all();
    }

    /**
     * @return array<string, true>
     */
    private function toWordSet(string $whitespaceSeparated): array
    {
        $items = preg_split('~\s+~', trim($whitespaceSeparated), -1, PREG_SPLIT_NO_EMPTY);

        return array_fill_keys($items, true);
    }
}
