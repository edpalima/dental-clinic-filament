<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRegistrationForm extends Component
{
    public $name, $email, $password, $passwordConfirmation;

    public function register()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'passwordConfirmation' => 'required|same:password',
        ]);

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => Hash::make($this->password),
        ]);

        // auth()->login($user);

        // Flash success message
        session()->flash('message', 'Registration successful!');

        // Redirect back to the registration page
        return redirect()->route('register');
    }

    public function render()
    {
        return view('livewire.user-registration-form');
    }
}
