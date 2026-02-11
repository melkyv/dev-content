<?php

namespace App\Livewire\Contents;

use App\Livewire\Forms\ContentForm;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    public ContentForm $form;

    public array $filePreviews = [];

    public bool $hasActiveSubscription = false;

    public function mount(): void
    {
        $this->hasActiveSubscription = Auth::user()->hasActiveSubscription();
    }

    public function updatedFormFiles(): void
    {
        $this->filePreviews = [];

        foreach ($this->form->files as $index => $file) {
            try {
                $this->filePreviews[$index] = [
                    'name' => $file->getClientOriginalName(),
                    'size' => $this->formatFileSize($file->getSize()),
                    'url' => $file->temporaryUrl(),
                ];
            } catch (\Exception $e) {
                $this->filePreviews[$index] = [
                    'name' => $file->getClientOriginalName(),
                    'size' => $this->formatFileSize($file->getSize()),
                    'url' => null,
                ];
            }
        }
    }

    public function removeFile(int $index): void
    {
        unset($this->form->files[$index]);
        unset($this->filePreviews[$index]);
        $this->form->files = array_values($this->form->files);
        $this->filePreviews = array_values($this->filePreviews);
    }

    public function save()
    {        
        $this->form->save();

        return redirect(route('contents.my'))->success('Conteúdo criado com sucesso!');   
    }

    private function formatFileSize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $unitIndex = 0;

        while ($bytes >= 1024 && $unitIndex < count($units) - 1) {
            $bytes /= 1024;
            $unitIndex++;
        }

        return round($bytes, 2).' '.$units[$unitIndex];
    }
}
