<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\Offer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}
