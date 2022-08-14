<?php

namespace App\Http\Controllers;

use App\Models\Followers;
use App\Models\User;
use Illuminate\Http\Request;

class FollowersController extends Controller
{
    public static function follow($auth_user_id, $followed_id)
    {
        if (!Followers::where('user_id', $auth_user_id) && !Followers::where('followed_id', $followed_id)) {
          return response('already followed', 1);
        }else{
            $connect = new Followers;
            $connect->user_id = $auth_user_id;
            $connect->followed_id = $followed_id;
            $connect->save();
            $followed_user = User::where('id', $followed_id)->first();
            $followed_user->followed = 1;


            // return  $followed_user;
            return response([
                'resultCode' => 1,
                'followedUser' => $followed_user
            ], 200);

        };
        // $result = Followers::where('user_id', $auth_user_id)->first();
        // return  $result;
    }

    public static function unfollow($user_id, $unfollowed_id)
    {   
        Followers::where('user_id', $user_id)->where('followed_id', $unfollowed_id)->delete();
        $unfollowed_user = User::where('id', $unfollowed_id)->first();
        
        return response([
            'resultCode' => 1,
            'unfollowedUser' => $unfollowed_user
        ], 200);
    }
}
