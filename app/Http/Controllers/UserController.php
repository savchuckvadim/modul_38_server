<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\CreateNewUser;
use App\Http\Resources\UserRecource;
use App\Models\Link;
use App\Models\Offer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public static function getFinance()
    {

        $user = Auth::user();

        if ($user->role_id == 1) { //Admin
            $finance = User::adminsFinance();
        } else if ($user->role_id == 2) { //Advertiser
            $finance =  User::advertFinance();
        } else if ($user->role_id == 3) { //Master
            $finance = User::mastersFinance();
        }

        return response([
            'resultCode' => 1,
            'role' =>  $user->role->name,
            'finance' => $finance,
        ]);
    }
    public static function getAuthUser()
    {
        $authUser = Auth::user();
        $userResource = null;
        if ($authUser) {
            $userResource = new UserRecource($authUser);
            return response([
                'resultCode' => 1,
                'authUser' => $userResource
            ], 200);
        }


        return response([
            'resultCode' => 0,
            'authUser' => $authUser
        ], 200);
    }

    public static function addUser($request)
    {
        $input = [
            'name' => $request->name,
            'surname' => $request->surname,
            'email' => $request->email,
            'password' => $request->password,
            'password_confirmation' => $request->password_confirmation,
            'role' => $request->role,
        ];
        $userCreating = new CreateNewUser;
        $user = $userCreating->create($input);
        // $user = new User;
        // $user->name = $request->name;
        // $user->surname = $request->surname;
        // $user->email = $request->email;
        // $user->password  = Hash::make($request->password);
        // $user->role_id = $request->role;
        // $user->save();
        return response([
            'resultCode' => 1,
            'createdUser' => $user
        ]);
    }

    public static function deleteUser($userId)
    {
        $user = User::find($userId);
        if ($user) {
            $user->delete();
            return response([
                'resultCode' => 1,
                'message' => 'user ' . $userId . ' was deleted'
            ]);
        } else {
            return response([
                'resultCode' => 0,
                'message' => 'user ' . $userId . ' was not found'
            ]);
        }
    }
}
