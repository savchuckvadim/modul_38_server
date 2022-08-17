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
       
        // // for ($i = 0; $i < $this->followers->count(); $i++) {
        // //     if($this->followers[$i]->id == $id){
        // //         $this->followed = 1;
        // //     };
           
        // // };
        // $photo =  $this->getAvatarUrl();
        
        return [
            'id' => $this->id,
            'name' => $this->name,
            'surname' => $this->surname,
            'email' => $this->email,
            'role' => $this->role->name,
            '$currentUser' =>$currentUser,
            // 'followeds' => $this->followeds,
            // 'followers' => $this->followers,
            // 'followed' =>  $this->followed,
            // 'profile' => $this->profile,
        //    'postsCount' => $this->posts->count(),
           'photo' => $this->photo 
            // 'created_at' => $this->created_at,
            // 'updated_at' => $this->updated_at,
        ];
    }

    public function with($request)
    {
        return [
            'resultCode' => 1,

            'links' => [
                'self' => 'link-value',
            ]
        ];
    }
}
