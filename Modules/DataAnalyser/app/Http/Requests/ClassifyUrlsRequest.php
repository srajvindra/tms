<?php

namespace Modules\DataAnalyser\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClassifyUrlsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'content' => ['required_without:file', 'nullable', 'string', 'max:5242880'],
            'file' => ['required_without:content', 'nullable', 'file', 'mimes:tsv,txt,csv', 'max:10240'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'content.required_without' => 'Paste bookmark lines or upload a TSV file.',
            'file.required_without' => 'Paste bookmark lines or upload a TSV file.',
        ];
    }

    /**
     * The raw TSV payload, from the uploaded file when present, otherwise the pasted text.
     */
    public function tsvContent(): string
    {
        if ($this->hasFile('file')) {
            return (string) $this->file('file')->get();
        }

        return (string) $this->input('content', '');
    }
}
