<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public static function getFinance(){

        $user = Auth ::user();
        $offers = Offer::all();
            $offersCount = $offers->count();
        $finance = [];
        if($user->role == 1){ //Admin

            $links = 0;
            $transitions = 0;
            $failTransitions = 0;
            $profit = 0;

            foreach($offers as $offer){

                $links += $offer->links->count();
                $transitions += $offer->transitions()['transitions'];
                $failTransitions += $offer->transitions()['failTransitions'];
                $profit += $offer->appsProfit;
            }
            $finance = [
                'links' => $links,
                'transitions' => $transitions,
                'failTransitions' => $failTransitions,
                'profit' => round($profit, 2),
              ];



        }else if($user->role == 2){ //Advertiser

            $masters = 0;
            $transitions = 0;
            $expenses = 0;

            foreach($offers as $offer){
                $masters+= $offer->followers->count(); //Msters Count
                $transitions += $offer->transitions()['transitions'];  //Transitions
                $expenses += $offer->price; //Expenses
            }
            $finance = [
              'offers' => $offersCount,
              'masters' => $masters,
              'transitions' => $transitions,
              'expenses' => $expenses,
            ];


        }else if($user->role == 3){//Master
            $offers = $user->offers;
            $offersCount = $offers->count();
            $transitions = 0;
            $profit = 0;

            foreach($offers as $offer){
                $transitions += $offer->transitions()['transitions'];  //Transitions
                $profit += $offer->mastersProfit; //Expenses
            }
            $finance = [
                'offers' => $offersCount,
                'transitions' => $transitions,
                'profit' => round($profit, 2),

              ];
        }

        return response([
            'resultCode' => 1,
            'finance' => $finance,
        ]);
    }
}
