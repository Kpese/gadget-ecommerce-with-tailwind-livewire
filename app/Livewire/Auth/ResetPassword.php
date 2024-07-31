<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class ResetPassword extends Component
{
    public $token;

    #[Url()]
    public $email;
    public $password;
    public $password_confirmation;

    public function mount($token){
        $this->token = $token;
    }

    public function save(){
        $this->validate([
            'password' => 'required|min:8|confirmed',
            'email' => 'required|email',
            'token' => 'required',
        ]);

        $status = Password::reset(
            [
                'email' => $this->email,
                'token' => $this->token,
                'password' => $this->password,
                'password_confirmation' => $this->password_confirmation,
            ],

            function(User $user, string $password){
                $password = $this->password;
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));
                $user->save();
                event(new PasswordReset($user));
            }
        );
        return $status === Password::PASSWORD_RESET ? redirect('/login') : session()->flash('success', 'Password reset failed');
    }

    public function render()
    {
        return view('livewire.auth.reset-password')->title('Reset Password - DStore');
    }
}
