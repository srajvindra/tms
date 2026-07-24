<?php

namespace Modules\News\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewsArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
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
}
