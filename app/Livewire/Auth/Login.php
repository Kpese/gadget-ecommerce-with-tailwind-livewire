<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Login extends Component
{
    use LivewireAlert;
    public $email;
    public $password;

    public function validates(){
        $this->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8',
        ]);

        if(Auth::attempt(['email'=>$this->email, 'password'=>$this->password])){
            $this->alert('success', 'user is succesfully logged in', [
                'position' => 'top-end',
                'timer' => 3000,
                'toast' => true,
               ]);
            return redirect()->intended();
        } else {
            return redirect()->back()->with('success', 'email and password do not match');
        }
    }

    public function render()
    {
        return view('livewire.auth.login')->title('Login - DStore');
    }
}
