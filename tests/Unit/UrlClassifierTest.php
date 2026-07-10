<?php

use Modules\DataAnalyser\Database\Seeders\DomainSeeder;
use Modules\DataAnalyser\Database\Seeders\KeywordSeeder;
use Modules\DataAnalyser\Services\UrlClassifier;

beforeEach(function () {
    $this->categories = array_values(array_unique(array_merge(
        array_keys(DomainSeeder::domainsByCategory()),
        array_keys(KeywordSeeder::patternsByCategory()),
        array_column(KeywordSeeder::wikiPatternsByType(), 'category'),
    )));

    $this->classifier = new UrlClassifier(
        DomainSeeder::domainsByCategory(),
        KeywordSeeder::patternsByCategory(),
        KeywordSeeder::wikiPatternsByType(),
        $this->categories,
    );
});

it('classifies known domains via the domain tables', function (string $url, string $expected) {
    expect($this->classifier->classify($url, 'some title'))->toBe($expected);
})->with([
    ['https://stackoverflow.com/questions/1', 'Development'],
    ['https://www.github.com/laravel/framework', 'Development'],
    ['https://chat.openai.com/c/abc', 'AI'],
    ['https://huggingface.co/models', 'AI'],
    ['https://www.merriam-webster.com/dictionary/test', 'vocab'],
    ['https://www.flipkart.com/some-product', 'shop'],
    ['https://www.imdb.com/title/tt0111161/', 'movie'],
    ['https://www.espncricinfo.com/series/xyz', 'Sports'],
    ['https://www.cardekho.com/cars', 'auto'],
    ['https://www.healthline.com/nutrition', 'health'],
    ['https://www.naukri.com/php-jobs', 'Career'],
    ['https://www.makemytrip.com/hotels', 'place'],
    ['https://mail.google.com/mail/u/0/', 'portals'],
]);

it('matches subdomains of known domains', function () {
    expect($this->classifier->classify('https://meta.stackoverflow.com/questions/1', 'title'))
        ->toBe('Development');
});

it('applies url-specific rules before domain tables', function (string $url, string $expected) {
    expect($this->classifier->classify($url, 'anything'))->toBe($expected);
})->with([
    ['https://en.wiktionary.org/wiki/ubiquitous', 'vocab'],
    ['https://www.linkedin.com/in/some-person', 'individuals'],
    ['https://www.linkedin.com/jobs/view/123', 'Career'],
    ['https://x.com/elonmusk', 'individuals'],
    ['https://twitter.com/naval/', 'individuals'],
    ['https://www.amazon.in/dp/B0ABCDEF', 'shop'],
    ['https://www.amazon.com/gp/product/B0ABCDEF', 'shop'],
    ['https://www.amazon.in/s?k=headphones', 'shop'],
    ['https://www.walmart.com/ip/some-item/123', 'shop'],
]);

it('classifies wikipedia titles heuristically', function (string $title, string $expected) {
    expect($this->classifier->classify('https://en.wikipedia.org/wiki/X', $title))->toBe($expected);
})->with([
    ['Machine learning - Wikipedia', 'AI'],
    ['PostgreSQL - Wikipedia', 'Development'],
    ['Infosys Ltd - Wikipedia', 'private or corporate'],
    ['Rahul Sharma - Wikipedia', 'individuals'],
    ['Category:Software - Wikipedia', 'Other'],
    ['List of rivers of India - Wikipedia', 'Other'],
    ['Jaipur district - Wikipedia', 'place'],
]);

it('does not treat wikipedia titles with stop words as people', function () {
    expect($this->classifier->classify('https://en.wikipedia.org/wiki/X', 'Taj Mahal Palace - Wikipedia'))
        ->not->toBe('individuals');
});

it('classifies unknown domains by title keywords', function (string $title, string $expected) {
    expect($this->classifier->classify('https://random-blog.example.com/post/1', $title))->toBe($expected);
})->with([
    ['How ChatGPT changed everything', 'AI'],
    ['Fixing a Laravel middleware bug', 'Development'],
    ['Virat Kohli scores century in Test match', 'Sports'],
    ['New Bollywood movie trailer released', 'movie'],
    ['Man arrested for bank fraud', 'crime'],
    ['10 yoga poses for better health', 'health'],
    ['Tiger spotted in wildlife sanctuary', 'nature'],
    ['New SUV launched with six airbags', 'auto'],
    ['Synonyms and antonyms explained', 'vocab'],
    ['How to negotiate your salary', 'Career'],
    ['Best places to visit in Goa', 'place'],
    ['Who is the richest man in Asia', 'individuals'],
    ['Startup raises $10M funding round', 'private or corporate'],
]);

it('treats developer-ish domains as Development', function (string $url) {
    expect($this->classifier->classify($url, 'untitled'))->toBe('Development');
})->with([
    ['https://medium.com/@someone/some-story-slug-9f8e'],
    ['https://engineering.medium.com/post-slug-1a2b'],
    ['https://docs.example.com/guide/intro'],
    ['https://someproject.github.io/manual'],
    ['https://mypackage.readthedocs.io/en/latest'],
]);

it('classifies bare homepages as portals', function () {
    expect($this->classifier->classify('https://example.com/', 'untitled homepage'))->toBe('portals');
});

it('classifies search engine result pages as portals', function () {
    expect($this->classifier->classify('https://www.google.com/search?q=untitled', 'untitled'))->toBe('portals');
});

it('falls back to Other', function () {
    expect($this->classifier->classify('https://unknown-site.example.org/deep/page?x=1', 'zzz qqq'))->toBe('Other');
});

it('classifies a tsv payload into counts and rows', function () {
    $tsv = "https://laravel.com/docs/12.x\tLaravel Documentation\n"
        ."https://chatgpt.com/c/1\tChat\n"
        ."https://unknown.example.org/page?x=1\tzzz\n"
        ."\n"
        ."https://www.imdb.com/title/tt1\t\n";

    $result = $this->classifier->classifyTsv($tsv);

    expect($result['rows'])->toHaveCount(4)
        ->and($result['counts']['Development'])->toBe(1)
        ->and($result['counts']['AI'])->toBe(1)
        ->and($result['counts']['movie'])->toBe(1)
        ->and($result['counts']['Other'])->toBe(1)
        ->and(array_sum($result['counts']))->toBe(4)
        ->and($result['counts'])->toHaveCount(count($this->categories));
});

it('uses the url as title fallback when the title column is missing', function () {
    $result = $this->classifier->classifyTsv('https://someblog.example.com/laravel-tips');

    expect($result['rows'][0]['category'])->toBe('Development');
});
