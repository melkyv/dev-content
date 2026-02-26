<?php

namespace App\Livewire\Contents;

use App\Livewire\Forms\ContentForm;
use App\Models\Content;
use App\Traits\TracksViewedContent;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Masmerise\Toaster\Toaster;

class Show extends Component
{
    use TracksViewedContent;
    use WithFileUploads;

    public bool $canEdit = false;

    public bool $canView = false;

    public bool $isEditing = false;

    public ContentForm $form;

    public array $newFilePreviews = [];

    public function mount(Content $content): void
    {
        $this->canView = ! $content->is_premium || Auth::user()?->hasActiveSubscription();
        $this->canEdit = Auth::id() === $content->user_id;

        if (! $this->canView) {
            Toaster::error('Este conteúdo é exclusivo para assinantes premium.');
            $this->redirectRoute('contents.my', navigate: true);

            return;
        }

        if (! $this->hasViewedContent($content->id)) {
            $content->recordView();
            $this->markContentAsViewed($content->id);
        }

        $this->form->setContent($content);
    }

    public function toggleEdit(): void
    {
        if (! $this->canEdit) {
            Toaster::error('Você não tem permissão para editar este conteúdo.');

            return;
        }

        $this->isEditing = ! $this->isEditing;

        if (! $this->isEditing) {
            $this->form->setContent($this->form->content);
            $this->form->newFiles = [];
            $this->newFilePreviews = [];
        }
    }

    public function updatedFormNewFiles(): void
    {
        $this->newFilePreviews = [];

        foreach ($this->form->newFiles as $index => $file) {
            try {
                $this->newFilePreviews[$index] = [
                    'name' => $file->getClientOriginalName(),
                    'size' => $this->formatFileSize($file->getSize()),
                    'url' => $file->temporaryUrl(),
                ];
            } catch (\Exception $e) {
                $this->newFilePreviews[$index] = [
                    'name' => $file->getClientOriginalName(),
                    'size' => $this->formatFileSize($file->getSize()),
                    'url' => null,
                ];
            }
        }
    }

    public function removeNewFile(int $index): void
    {
        unset($this->form->newFiles[$index]);
        unset($this->newFilePreviews[$index]);
        $this->form->newFiles = array_values($this->form->newFiles);
        $this->newFilePreviews = array_values($this->newFilePreviews);
    }

    public function update(): void
    {
        if (! $this->canEdit) {
            Toaster::error('Você não tem permissão para editar este conteúdo.');

            return;
        }

        try {
            $this->form->save();
            $this->form->content->refresh();
            $this->isEditing = false;
            $this->newFilePreviews = [];
            Toaster::success('Conteúdo atualizado com sucesso!');
        } catch (\Exception $e) {
            Toaster::error('Erro ao atualizar conteúdo. Tente novamente.');
        }
    }

    public function removeFile(int $fileId): void
    {
        if (! $this->canEdit) {
            Toaster::error('Você não tem permissão para remover arquivos.');

            return;
        }

        try {
            $this->form->removeFile($fileId);
            $this->form->content->refresh();
            Toaster::success('Arquivo removido com sucesso!');
        } catch (\Exception $e) {
            Toaster::error('Erro ao remover arquivo. Tente novamente.');
        }
    }

    public function downloadFile(int $fileId): void
    {
        $file = $this->form->content->files->firstWhere('id', $fileId);

        if (! $file) {
            Toaster::error('Arquivo não encontrado.');

            return;
        }

        $this->form->content->recordDownload();

        $this->dispatch('file-download', [
            'url' => url('storage/'.$file->path),
            'name' => $file->original_name,
        ]);
    }

    public function delete()
    {
        if (! $this->canEdit) {
            Toaster::error('Você não tem permissão para excluir este conteúdo.');

            return;
        }

        try {
            $this->form->delete();

            return redirect(route('contents.my'))->success('Conteúdo excluído com sucesso!');
        } catch (\Exception $e) {
            Toaster::error('Erro ao excluir conteúdo. Tente novamente.');
        }
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
