<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'name',
        'surname',
        'email',
        'password',
        'role_id',
        'photo'
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getAvatarUrl()
    {
        $hash = md5($this->email);
        $url = "https://www.gravatar.com/avatar/" . $hash . "?d=robohash";
        return $url;
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    public function offers()
    {
        return $this->belongsToMany(Offer::class, 'offer_masters', 'master_id', 'offer_id');
    }

    public function mastersLinks() //offers на которые подписался мастер
    {
        return $this->hasMany(Link::class, 'master_id');
    }

    public function advertisersLinks() //offers на которые подписался мастер
    {
        return $this->hasMany(Link::class, 'advertiser_id');
    }

    //followedOffers
    //createdOffers

    public function createdOffers()
    {
        return $this->hasMany(Offer::class, 'advertiser_id');
    }


    public static function mastersFinance()
    {
        $user = Auth::user();
        $offers = $user->offers;
        $offersCount = $offers->count();

        $totalLinks = $offersCount;
        $totalTransitions = 0;

        $totalProfit = 0;
        $profit = 0;

        $links = [];
        foreach ($offers as $offer) {
            $link = $offer->links->where('master_id', $user->id)->first();
            if ($link) {

                $transitions = $link->transitions;
                $totalTransitions += $transitions;

                $profitFromLinkTransitions = $transitions * $offer->mastersProfit;
                $profit += $offer->mastersProfit;
                $totalProfit += $profitFromLinkTransitions;

                $link = [
                    'name' => $offer->name,
                    'transitions' => $transitions,
                    'price' => round($offer->mastersProfit, 2),
                    'profit' => round($profitFromLinkTransitions, 2),
                    'created_at' => $link->created_at
                ];
                array_push($links, $link);
            }
        }
        $total = [
            'totalLinks' => $totalLinks,
            'transitions' => $totalTransitions,
            'profit' => $profit,
            'totalProfit' => $totalProfit,
            'created_at' => null

        ];
        $finance = [
            'items' => $links,
            'total' => $total

        ];
        return $finance;
    }

    protected static function booted()
    {
        static::created(function ($user) {
            $user->photo = $user->getAvatarUrl();
            $user->save();
            // $profile = new Profile;
            // $profile->user_id = $user->id;
            // $profile->name = $user->name;
            // $profile->surname = $user->surname;
            // $profile->email = $user->email;

            // $profile->save();
            // return $profile;
        });
    }
}
