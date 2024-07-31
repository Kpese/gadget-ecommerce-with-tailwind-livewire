<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;

class Register extends Component
{

    public $name;
    public $email;
    public $password;

    public function save(){
        $this->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8'
        ]);

       $user =  User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password)
        ]);

        auth()->login($user);

        $this->reset('name', 'email', 'password');
       return redirect()->intended();
    }

    public function render()
    {
        return view('livewire.auth.register')->title('Register - DStore');
    }
}
