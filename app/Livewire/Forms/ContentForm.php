<?php

namespace App\Livewire\Forms;

use App\Models\Content;
use App\Models\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Form;

class ContentForm extends Form
{
    public ?Content $content = null;

    public string $title = '';

    public string $description = '';

    public bool $is_premium = false;

    public string $publish_type = 'now';

    public ?string $scheduled_date = null;

    public array $files = [];

    public array $newFiles = [];

    public function setContent(Content $content): void
    {
        $this->content = $content;
        $this->title = $content->title;
        $this->description = $content->description;
        $this->is_premium = $content->is_premium;
        $this->publish_type = $content->published_at && $content->published_at > now() ? 'schedule' : 'now';
        $this->scheduled_date = $content->published_at?->format('Y-m-d\TH:i');
    }

    protected function rules(): array
    {
        $rules = [
            'title' => ['required', 'string', 'min:3', 'max:255'],
            'description' => ['required', 'string', 'min:5'],
            'publish_type' => ['required', 'in:now,schedule'],
            'files' => ['nullable', 'array', 'max:10'],
            'files.*' => ['file', 'max:10240'],
            'newFiles' => ['nullable', 'array', 'max:10'],
            'newFiles.*' => ['file', 'max:10240'],
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
            'newFiles' => 'novos arquivos',
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
            'newFiles.max' => 'Você pode anexar no máximo 10 arquivos.',
            'newFiles.*.max' => 'Cada arquivo deve ter no máximo 10MB.',
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
        $this->validate();

        if ($this->content) {
            return $this->update();
        }

        return $this->create();
    }

    private function create(): Content
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
                $this->storeFiles($content, $this->files);
            }

            return $content;
        });
    }

    private function update(): Content
    {
        if (! $this->content) {
            throw new \InvalidArgumentException('Content not set for update');
        }

        return DB::transaction(function () {
            $this->content->update([
                'title' => $this->title,
                'description' => $this->description,
                'is_premium' => $this->shouldBePremium(),
                'published_at' => $this->getPublishedAt(),
            ]);

            if (! empty($this->newFiles)) {
                $this->storeFiles($this->content, $this->newFiles);
                $this->newFiles = [];
            }

            $this->content->refresh();

            return $this->content;
        });
    }

    public function delete(): void
    {
        if (! $this->content) {
            throw new \InvalidArgumentException('Content not set for delete');
        }

        if ($this->content->user_id !== Auth::id()) {
            throw new \Exception('Você só pode excluir seus próprios conteúdos');
        }

        DB::transaction(function () {
            foreach ($this->content->files as $file) {
                Storage::disk($file->disk)->delete($file->path);
            }

            $this->content->delete();
        });
    }

    public function removeFile(int $fileId): void
    {
        if (! $this->content) {
            throw new \InvalidArgumentException('Content not set');
        }

        $file = File::where('id', $fileId)
            ->where('content_id', $this->content->id)
            ->first();

        if (! $file) {
            throw new \InvalidArgumentException('File not found');
        }

        Storage::disk($file->disk)->delete($file->path);
        $file->delete();
        $this->content->refresh();
    }

    private function storeFiles(Content $content, array $files): void
    {
        foreach ($files as $file) {
            $path = $file->store('content-files', 'public');

            File::create([
                'content_id' => $content->id,
                'path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'disk' => 'public',
                'is_processed' => true,
            ]);
        }
    }

    public function resetForm(): void
    {
        $this->content = null;
        $this->title = '';
        $this->description = '';
        $this->is_premium = false;
        $this->publish_type = 'now';
        $this->scheduled_date = null;
        $this->files = [];
        $this->newFiles = [];
    }
}
