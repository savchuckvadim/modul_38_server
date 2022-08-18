<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LinkController extends Controller
{
    public static function create($offerId)
    {
        $authUserId = Auth::user()->id;
        $offer = Offer::where('id', $offerId)->first();
        $advertiserId = $offer->advertiser->id;

        $checkLink = Link::where('advertiser_id', $advertiserId) //проверяем не сущестует ли уже ссылка такая
            ->where('master_id', $authUserId)
            ->where('offer_id', $offerId)
            ->first();
        if ($checkLink) {
            return response([
                'resultCode' => 0,
                'message' => 'Link is already created!'

            ]);
        } else {
            $link = new Link();
            $link->advertiser_id = $advertiserId;
            $link->master_id = $advertiserId;
            $link->offer_id = $offerId;
            $link->transitions = 0;
           
            $link->url = url('/link');
            $link->save();
            $link->url = url("/link/{$link->id}");
            $link->save();
            return response([
                'resultCode' => 1,
                'link' => $link->url

            ]);
        }
    }
}
