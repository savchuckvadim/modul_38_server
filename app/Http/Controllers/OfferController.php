<?php

namespace App\Http\Controllers;

use App\Http\Resources\OfferCollection;
use App\Http\Resources\OfferResource;
use App\Models\Offer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// 'name'
// 'description'
// 'url'
// 'price'
// tags...
class OfferController extends Controller
{
    public static function newOffer(Request $request)
    {
        $offer = new Offer;
        $offer->name = $request->name;
        $offer->description = $request->description;
        $offer->advertiser_id = $request->userId;
        $offer->url = $request->url;
        $offer->price = $request->price;
        $offer->mastersProfit = round(($offer->price) * 0.8, 2);
        $offer->appsProfit = $offer->price - $offer->mastersProfit;
        $offer->save();
        $result = new OfferResource($offer);
        return $result;
    }

    public static function getOffers()
    {
        // $user = User::findOrFail($userId);
        $user = Auth::user();
        if ($user->role->id == 2) { //advertiser
            $offers = $user->createdOffers;
            return new OfferCollection($offers);
        } else if ($user->role->id == 1 ||  $user->role->id == 3) { //admin and master
            $offers = Offer::all();
            return new OfferCollection($offers);
        } else {
            return response([
                'resultCode' => 0,
                'message' => 'you are not User!'
            ]);
        }
    }
    public static function deleteOffer($offerId)
    {
        $offer = Offer::findOrFail($offerId);
        $offer->delete();
        return response([
            'resultCode' => 1,
            'deletedOffer' => $offer
        ]);
    }
}
