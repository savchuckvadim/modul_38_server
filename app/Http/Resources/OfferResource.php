<?php

namespace App\Http\Resources;

use App\Models\OfferMaster;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class OfferResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        $authUser = Auth::user();

        $findFollow = OfferMaster::where('master_id', $authUser->id)->where('offer_id', $this->id)->first();
       
        $isFollowing = 0;
        if($findFollow ){
            $isFollowing = 1;
        }
        
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'url' => $this->url,
            'price' => $this->price,
            'mastersProfit' => $this->mastersProfit,
            'followers' => $this->followers->count(),
            'advertiser' => $this->advertiser,
            'created_at' => $this->created_at,
            'isFollowing' => $isFollowing,
            'links' => $this->links()
        ];
    }

    public function with($request)
    {
        return [
            'resultCode' => 1,
        ];
    }
}
