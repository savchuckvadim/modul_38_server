<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Gravatar;
class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public static function create($user)
    {
        $profile = Profile::create([
            'name' => $user->name,
            'surname' => $user->surname,
            'email' => $user->email,
            'user_id' => $user,
            'hero' => 'url//hero'

        ]);

        $profile->save();
        return $profile;
    }

    public static function getProfile($user_id)
    {
        return Profile::where('user_id', $user_id)->first();
    }

    public static function getAboutMe($userId)
    {
        $user = User::findOrFail($userId);
        return response(['resultCode' => 0, 'aboutMe' => $user->profile->about_me]);
    }
    public static function updateAboutMe($aboutMe)
    {
        $user = Auth::user();
        $user->profile->about_me = $aboutMe;
        $user->profile->save();
        return response(['resultCode' => 0, 'updatingProfule' => $user->profile]);
    }

    public static function getGravatar($userId)
    {
        $user = User::find($userId);
        $email = $user->email;
        Gravatar ::get($email);
    } 

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function show(Profile $profile)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function edit(Profile $profile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Profile $profile)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function destroy(Profile $profile)
    {
        //
    }
}
