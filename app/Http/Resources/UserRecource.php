<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class UserRecource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

      
        $currentUser = Auth::user();
        $id = $currentUser->id;
        for ($i = 0; $i < $this->followers->count(); $i++) {
            if($this->followers[$i]->id == $id){
                $this->followed = 1;
            };
           
        };
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'followeds' => $this->followeds,
            'followers' => $this->followers,
            'followed' =>  $this->followed,
            'profile' => $this->profile,
           'postsCount' => $this->posts->count(),
           'photos' => [
            'small' => null,
            'large' => null
           ]
            // 'created_at' => $this->created_at,
            // 'updated_at' => $this->updated_at,
        ];
    }

    public function with($request)
    {
        return [
            'resultCode' => 0,

            'links' => [
                'self' => 'link-value',
            ]
        ];
    }
}
