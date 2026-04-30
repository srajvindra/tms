<?php

namespace Modules\News\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Modules\News\Models\NewsArticle;

class NewsList extends Component
{
    use WithPagination;

    public string $search = '';
    public string $status_filter = '';
    public string $category_filter = '';
    public string $source_filter = '';
    public int $per_page = 10;

    protected $queryString = [
        'search'          => ['except' => ''],
        'status_filter'   => ['except' => ''],
        'category_filter' => ['except' => ''],
        'source_filter'   => ['except' => ''],
        'per_page'        => ['except' => 10],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter(): void
    {
        $this->resetPage();
    }

    public function updatingSourceFilter(): void
    {
        $this->resetPage();
    }

    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    public function deleteArticle(int $id): void
    {
        $article = NewsArticle::findOrFail($id);
        $article->delete();

        session()->flash('message', 'Article deleted successfully!');
        $this->resetPage();
    }

    public function getArticles()
    {
        $query = NewsArticle::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%'.$this->search.'%')
                    ->orWhere('source', 'like', '%'.$this->search.'%')
                    ->orWhere('author', 'like', '%'.$this->search.'%')
                    ->orWhere('description', 'like', '%'.$this->search.'%')
                    ->orWhere('category', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->status_filter) {
            $query->where('status', $this->status_filter);
        }

        if ($this->category_filter) {
            $query->where('category', 'like', '%'.$this->category_filter.'%');
        }

        if ($this->source_filter) {
            $query->where('source', 'like', '%'.$this->source_filter.'%');
        }

        return $query->orderBy('created_at', 'desc')->paginate($this->per_page);
    }

    public function render()
    {
        return view('news::livewire.news-list', [
            'articles' => $this->getArticles(),
        ]);
    }
}
