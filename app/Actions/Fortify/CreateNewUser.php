<?php

namespace App\Actions\Fortify;

use App\Http\Controllers\ProfileController;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
        ])->validate();

        
        $user = User::create([
            'name' => $input['name'],
            'surname' => $input['surname'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'followed' => 0
            
        ]);
        // $user->profile->create([
        //     'name' => $input['name'],
        //     'surname' => $input['surname'],
        //     'email' => $input['email'],
        //     'user_id' => $user->id,
        // ])->save();
        // $profilr_contr = new ProfileController;
        // ProfileController::create($user);
        // $profile = Profile::create([
        //     'name' => $input['name'],
        //     'surname' => $input['surname'],
        //     'email' => $input['email'],
        //     'user_id' => $user,
        //     'hero' => 'url//hero'
            
        // ]);
        // $user->profile()->create([
        //     'name' => $input['name'],
        //     'surname' => $input['surname'],
        //     'email' => $input['email'],
        //     'user_id' => $user,
        //     'hero' => 'url//hero'
        // ]);
        return $user;
    }
}
