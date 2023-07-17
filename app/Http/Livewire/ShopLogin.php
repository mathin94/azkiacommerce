<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Shop\Customer;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class ShopLogin extends Component
{
    protected $baseUrl, $customer_id;

    public $tab, $title = 'Login';

    # signin properties
    public $email,
        $password,
        $remember = false;

    #register properties
    public $fullName,
        $phoneNumber,
        $emailAddress,
        $gender,
        $registerPassword,
        $registerPasswordConfirmation;

    protected $queryString = ['tab'];

    public function __construct()
    {
        $this->baseUrl = config('app.backoffice_api_url');
    }

    public function mount()
    {
        $this->title = $this->tab === 'register' ? 'Daftar Akun' : 'Login Akun';
    }

    public function submitLogin()
    {
        $credentials = $this->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        if (!$this->remoteLogin($credentials)) {
            throw ValidationException::withMessages([
                'email' => __('filament::login.messages.failed'),
            ]);
        }

        auth()->guard('shop')->loginUsingId($this->customer_id);
        session()->regenerate();

        return redirect()->to('/');
    }

    public function submitRegister()
    {
        $this->validate([
            'fullName'                     => 'required',
            'phoneNumber'                  => 'required',
            'emailAddress'                 => 'required|email|unique:App\Models\Backoffice\Customer,email',
            'gender'                       => 'required|in:L,P',
            'registerPassword'             => 'required|min:6',
            'registerPasswordConfirmation' => 'required|same:registerPassword',
        ]);

        $params = [
            'name'     => $this->fullName,
            'email'    => $this->emailAddress,
            'phone'    => $this->phoneNumber,
            'gender'   => $this->gender,
            'password' => $this->registerPassword,
        ];

        if (!$this->registerApi($params)) {
            Session::flash('error', 'Register akun gagal. silahkan coba lagi');
            return;
        }

        Session::flash('success', 'Register akun berhasil. silahkan cek email dan konfirmasi pembuatan akun untuk mengaktifkan akun kamu.');
        $this->reset(['fullName', 'phoneNumber', 'emailAddress', 'gender', 'registerPassword', 'registerPasswordConfirmation']);
    }

    private function registerApi(array $params = [])
    {
        $request = Http::withOptions([
            'verify' => false,
        ])->withHeaders([
            'accept' => 'application/json',
        ])->post("{$this->baseUrl}/auth/customer/register", $params);

        if ($request->failed()) {
            return false;
        }

        return $request->json();
    }

    private function remoteLogin(array $credentials = [])
    {
        $request = Http::withOptions([
            'verify' => false,
        ])->withHeaders([
            'accept' => 'application/json',
        ])->post("{$this->baseUrl}/auth/customer/login", $credentials);

        if ($request->failed()) {
            return false;
        }

        $data = $request->json();

        $remote_user = $data['customer'];

        $customer = Customer::updateOrCreate(
            [
                'email'         => $remote_user['email'],
                'resource_id'   => $remote_user['id'],
            ],
            [
                'authorization_token' => $data['token'],
                'customer_type_id'    => $remote_user['customer_type']['id'],
                'customer_type'       => $remote_user['customer_type'],
                'password'            => bcrypt($credentials['password']),
                ...$remote_user
            ]
        );

        $this->customer_id = $customer->id;

        return true;
    }

    public function render()
    {
        return view('livewire.shop-login')
            ->layout('layouts.frontpage')
            ->layoutData([
                'title' => $this->title
            ]);
    }

    public function updateLayoutData($tab = null)
    {
        $this->tab = $tab;

        $this->render()->layoutData([
            'title' => $this->tab === 'register' ? 'Daftar Akun' : 'Login Akun'
        ]);
    }
}
