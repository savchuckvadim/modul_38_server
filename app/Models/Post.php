<?php

namespace App\Models;

use App\Http\Resources\UserRecource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'body',
        'image',
        'author_id',
        'profile_id',
        // 'isAuthLikes',



    ];

    public function getAuthor()
    {
        $authorId = $this->author();
        return new UserRecource ($authorId);
    }
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function profile()
    {
        return $this->belongsTo(Profile::class, 'profile_id');
    }

    public function likes()
    {
        return $this->hasMany(Like::class, 'post_id');
    }
    public function isAuthLikes()
    {
        $isLike = false;
        $authUserId = Auth::user()->id;
        $likes = $this->likes;

        // foreach($this->likes as $like){
        //     $count = 0;
        //     $authUserId = Auth::user()->id;
        //     if($like->author_id == $authUserId){
        //         $isLike = true;
        //     }
        // };
        for ($i = 0; $i < $likes->count(); $i++) {

            if ($likes[$i]->author_id == $authUserId) {
                $isLike = true;
            }
        }

        return $isLike;
    }
}
