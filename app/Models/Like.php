<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'post_id',
        'author_id'

    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
