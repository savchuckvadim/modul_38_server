<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class PostResource extends JsonResource
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
        return [
            'id' => $this->id,
            // 'author_id' => $this->author_id,
            'body' => $this->body,
            'profile_id' => $this->profile->id,
            'likesCount' => $this->likes->count(),
            'isAuthLikes' => $this->isAuthLikes(),
            'likes' => $this->likes,
            'img' => null,
            'author' => new UserRecource (User::findOrFail($this->author_id))
            // 'created_at' => $this->created_at,
            // 'updated_at' => $this->updated_at,
        ];
    }
}
