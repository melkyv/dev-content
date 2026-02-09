<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Masmerise\Toaster\Toaster;

class Profile extends Component
{
    use WithFileUploads;

    public string $name = '';

    public string $email = '';

    public $avatar = null;

    public string $current_password = '';

    public string $new_password = '';

    public string $new_password_confirmation = '';

    public ?string $avatarPreviewUrl = null;

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore(Auth::user()->id),
            ],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:1024'],
        ];
    }

    public function updatedAvatar(): void
    {
        if ($this->avatar) {
            try {
                $this->avatarPreviewUrl = $this->avatar->temporaryUrl();
            } catch (\Exception $e) {
                $this->avatarPreviewUrl = null;
            }
        } else {
            $this->avatarPreviewUrl = null;
        }
    }

    public function mount(): void
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
    }

    public function updateProfile(): void
    {
        $user = Auth::user();

        $validated = $this->validate();

        if ($this->avatar) {
            if ($user->avatar_path && ! filter_var($user->avatar_path, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($user->avatar_path);
            }

            $path = $this->avatar->store('avatars', 'public');
            $validated['avatar_path'] = $path;
        }

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'avatar_path' => $validated['avatar_path'] ?? $user->avatar_path,
        ]);

        $this->avatar = null;
        $this->avatarPreviewUrl = null;

        Toaster::success('Perfil atualizado com sucesso!');
    }

    public function updatePassword(): void
    {
        $user = Auth::user();
        $hasPassword = filled($user->password);

        if ($hasPassword) {
            $this->validateOnly('current_password', [
                'current_password' => ['required', 'string'],
            ]);

            if (! Hash::check($this->current_password, $user->password)) {
                $this->addError('current_password', 'A senha atual está incorreta.');

                return;
            }
        }

        $this->validateOnly('new_password', [
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user->update([
            'password' => Hash::make($this->new_password),
        ]);

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);

        $message = $hasPassword ? 'Senha atualizada com sucesso!' : 'Senha definida com sucesso!';
        Toaster::success($message);
    }

    public function removeAvatar(): void
    {
        $user = Auth::user();

        if ($user->avatar_path && ! filter_var($user->avatar_path, FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete($user->avatar_path);
        }

        $user->update(['avatar_path' => null]);

        Toaster::success('Avatar removido com sucesso!');
    }
}
