<?php

namespace App\Models;

use App\Http\Resources\UserRecource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    

    public function followers()
    {
        return $this->hasMany(OfferMaster::class, 'offer_id');
    }

    public function advertiser()
    {
        return $this->belongsTo(User::class, 'advertiser_id');
    }
    public function links() //offers на которые подписался мастер
    {
        return $this->hasMany(Link::class, 'offer_id');
    }

}
