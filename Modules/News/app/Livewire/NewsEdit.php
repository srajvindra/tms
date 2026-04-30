<?php

namespace Modules\News\Livewire;

use Livewire\Component;
use Modules\News\Models\NewsArticle;

class NewsEdit extends Component
{
    public NewsArticle $article;

    public string $title = '';
    public string $url = '';
    public string $source = '';
    public string $author = '';
    public string $description = '';
    public string $category = '';
    public string $published_at = '';
    public string $status = 'unread';
    public string $notes = '';

    public function mount(NewsArticle $article): void
    {
        $this->article      = $article;
        $this->title        = $article->title;
        $this->url          = $article->url;
        $this->source       = $article->source;
        $this->author       = $article->author ?? '';
        $this->description  = $article->description ?? '';
        $this->category     = $article->category;
        $this->published_at = $article->published_at?->format('Y-m-d') ?? '';
        $this->status       = $article->status;
        $this->notes        = $article->notes ?? '';
    }

    protected function rules(): array
    {
        return [
            'title'        => 'required|string|max:255',
            'url'          => 'required|url|max:2048',
            'source'       => 'required|string|max:255',
            'author'       => 'nullable|string|max:255',
            'description'  => 'nullable|string|max:65535',
            'category'     => 'required|string|max:255',
            'published_at' => 'nullable|date',
            'status'       => 'required|in:unread,reading,read,saved',
            'notes'        => 'nullable|string|max:65535',
        ];
    }

    protected function messages(): array
    {
        return [
            'title.required'    => 'The article title is required.',
            'url.required'      => 'The article URL is required.',
            'url.url'           => 'Please enter a valid URL.',
            'source.required'   => 'The news source is required.',
            'category.required' => 'The category is required.',
            'status.required'   => 'The status is required.',
            'status.in'         => 'Invalid status selected.',
        ];
    }

    public function updated(string $propertyName): void
    {
        $this->validateOnly($propertyName);
    }

    public function update(): void
    {
        $this->validate();

        $this->article->update([
            'title'        => $this->title,
            'url'          => $this->url,
            'source'       => $this->source,
            'author'       => $this->author ?: null,
            'description'  => $this->description ?: null,
            'category'     => $this->category,
            'published_at' => $this->published_at ?: null,
            'status'       => $this->status,
            'notes'        => $this->notes ?: null,
        ]);

        session()->flash('message', 'Article updated successfully!');
    }

    public function render()
    {
        return view('news::livewire.news-edit');
    }
}
