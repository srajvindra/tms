<?php

namespace Modules\News\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\News\Models\NewsArticle;
use Modules\News\Http\Requests\NewsArticleRequest;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        return view('news::index');
    }

    public function create()
    {
        return view('news::create');
    }

    public function store(NewsArticleRequest $request)
    {
        NewsArticle::create($request->validated());

        return redirect()->route('news.index')->with('success', 'Article saved successfully!');
    }

    public function show(NewsArticle $news)
    {
        return view('news::show', compact('news'));
    }

    public function edit(NewsArticle $news)
    {
        return view('news::edit', compact('news'));
    }

    public function update(NewsArticleRequest $request, NewsArticle $news)
    {
        $news->update($request->validated());

        return redirect()->route('news.index')->with('success', 'Article updated successfully!');
    }

    public function destroy(NewsArticle $news)
    {
        $news->delete();

        return redirect()->route('news.index')->with('success', 'Article deleted successfully!');
    }
}
