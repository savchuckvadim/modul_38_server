<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;

class UserCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $this->except('updated_at', 'name', 'surname');
      
        $data = $this->collection->each(function ($item) {
            $currentUser = Auth::user();
            $id = $currentUser->id;

           

            for ($i = 0; $i < $item->followers->count(); $i++) {
                if($item->followers[$i]->id == $id){
                    $item->followed = 1;
                };
               
            };
            $photos = [
                'small'=> $item->getAvatarUrl(),
                'large' => null
            ];
            
            $item->photos = $photos;
            return [$item->followers, $item->followeds, $item->profile];
        });
        return [
            'resultCode' => 0,
            'totalCount' =>  $this->collection->count(),

            // 'data' => $result_collection,
            'data' => $data,


            // 'links' => [
            //     'self' => 'link-value',
            // ]
        ];
    }
}
