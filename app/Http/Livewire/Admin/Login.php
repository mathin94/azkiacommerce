<?php

namespace App\Http\Livewire\Admin;

use App\Models\User;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Filament\Facades\Filament;
use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

/**
 * @property ComponentContainer $form
 */
class Login extends Component implements HasForms
{
    use InteractsWithForms;
    use WithRateLimiting;

    protected $baseUrl, $user_id;

    public $email = '';

    public $password = '';

    public $remember = false;

    public function __construct($id = null)
    {
        parent::__construct($id);

        $this->baseUrl = env('BACKOFFICE_API_BASE_URL');
    }

    public function mount(): void
    {
        if (Filament::auth()->check()) {
            redirect()->intended(Filament::getUrl());
        }

        $this->form->fill();
    }

    public function authenticate(): ?LoginResponse
    {
        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            throw ValidationException::withMessages([
                'email' => __('filament::login.messages.throttled', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]),
            ]);
        }

        $data = $this->form->getState();

        $credentials = [
            'email'    => $data['email'],
            'password' => $data['password']
        ];

        if (!$this->remoteLogin($credentials)) {
            throw ValidationException::withMessages([
                'email' => __('filament::login.messages.failed'),
            ]);
        }

        Filament::auth()->loginUsingId($this->user_id);

        session()->regenerate();

        return app(LoginResponse::class);
    }

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('email')
                ->label(__('filament::login.fields.email.label'))
                ->email()
                ->required()
                ->autocomplete(),
            TextInput::make('password')
                ->label(__('filament::login.fields.password.label'))
                ->password()
                ->required(),
            Checkbox::make('remember')
                ->label(__('filament::login.fields.remember.label')),
        ];
    }

    private function remoteLogin(array $credentials = [])
    {
        $request = Http::withOptions([
            'verify' => false,
        ])->withHeaders([
            'accept' => 'application/json',
        ])->post("{$this->baseUrl}/auth/user/login", $credentials);

        if ($request->failed()) {
            return false;
        }

        $data = $request->json();

        $remote_user = $data['user'];

        $user = User::updateOrCreate(
            ['email' => $remote_user['email']],
            [
                'external_id' => $remote_user['id'],
                'name'        => $remote_user['name'],
                'password'    => bcrypt($credentials['password']),
                'data'        => json_encode($remote_user),
                'auth_token'  => $data['token']
            ]
        );

        $this->user_id = $user->id;

        return true;
    }

    public function render(): View
    {
        return view('filament::login')
            ->layout('filament::components.layouts.card', [
                'title' => __('filament::login.title'),
            ]);
    }
}
