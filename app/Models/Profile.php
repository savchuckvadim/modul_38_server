<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'surname',
        'email',
        
    ];
    

    public function user(){
       return $this->hasOne(User::class);
    }
    public function posts()
    {
        return $this->hasMany(Post::class, 'profile_id');
    }

}
