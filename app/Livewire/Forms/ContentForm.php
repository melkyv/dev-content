<?php

namespace App\Livewire\Forms;

use App\Models\Content;
use App\Models\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Form;

class ContentForm extends Form
{
    public string $title = '';

    public string $description = '';

    public bool $is_premium = false;

    public string $publish_type = 'now';

    public ?string $scheduled_date = null;

    public array $files = [];

    protected function rules(): array
    {
        $rules = [
            'title' => ['required', 'string', 'min:3', 'max:255'],
            'description' => ['required', 'string', 'min:5'],
            'publish_type' => ['required', 'in:now,schedule'],
            'files' => ['nullable', 'array', 'max:10'],
            'files.*' => ['file', 'max:10240'],
        ];

        if ($this->publish_type === 'schedule') {
            $rules['scheduled_date'] = ['required', 'date', 'after:now'];
        }

        if (Auth::user()?->hasActiveSubscription()) {
            $rules['is_premium'] = ['boolean'];
        }

        return $rules;
    }

    protected function validationAttributes(): array
    {
        return [
            'title' => 'título',
            'description' => 'descrição',
            'publish_type' => 'tipo de publicação',
            'scheduled_date' => 'data de publicação',
            'is_premium' => 'conteúdo premium',
            'files' => 'arquivos',
        ];
    }

    protected function messages(): array
    {
        return [
            'title.required' => 'O título é obrigatório.',
            'title.min' => 'O título deve ter pelo menos 3 caracteres.',
            'description.required' => 'A descrição é obrigatória.',
            'description.min' => 'A descrição deve ter pelo menos 5 caracteres.',
            'scheduled_date.required' => 'A data de publicação é obrigatória quando agendado.',
            'scheduled_date.after' => 'A data de publicação deve ser futura.',
            'files.max' => 'Você pode anexar no máximo 10 arquivos.',
            'files.*.max' => 'Cada arquivo deve ter no máximo 10MB.',
        ];
    }

    public function getPublishedAt(): ?string
    {
        return $this->publish_type === 'now'
            ? now()->toDateTimeString()
            : $this->scheduled_date;
    }

    public function shouldBePremium(): bool
    {
        if (! Auth::user()?->hasActiveSubscription()) {
            return false;
        }

        return $this->is_premium;
    }

    public function save(): Content
    {
        return DB::transaction(function () {
            $content = Content::create([
                'user_id' => Auth::id(),
                'title' => $this->title,
                'description' => $this->description,
                'is_premium' => $this->shouldBePremium(),
                'published_at' => $this->getPublishedAt(),
            ]);

            if (! empty($this->files)) {
                foreach ($this->files as $file) {
                    $path = $file->store('content-files', 'public');

                    File::create([
                        'content_id' => $content->id,
                        'path' => $path,
                        'original_name' => $file->getClientOriginalName(),
                        'mime_type' => $file->getMimeType(),
                        'size' => $file->getSize(),
                        'disk' => 'public',
                        'is_processed' => false,
                    ]);
                }
            }

            return $content;
        });
    }
}
