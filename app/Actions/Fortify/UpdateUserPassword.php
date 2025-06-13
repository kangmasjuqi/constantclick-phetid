<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\UpdatesUserPasswords; // Import the contract

class UpdateUserPassword implements UpdatesUserPasswords
{
    /**
     * Validate and update the given user's password.
     *
     * @param  array<string, string>  $input
     * @return void
     */
    public function update(User $user, array $input): void
    {
        // You can customize the validation rules here if needed
        Validator::make($input, [
            'current_password' => ['required', 'string', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ])->validateWithBag('updatePassword'); // Using validateWithBag for Fortify's default error bag

        $user->forceFill([
            'password' => Hash::make($input['password']),
        ])->save();
    }
}