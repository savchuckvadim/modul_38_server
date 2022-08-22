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
        $items = [];
        $user = Auth::user();
        $offers = $user->offers;
        $offersCount = $offers->count();

        $totalLinks = $offersCount;
        $totalTransitions = 0;

        $totalProfit = 0;
        $profit = 0;


        foreach ($offers as $offer) {
            $link = $offer->links->where('master_id', $user->id)->first();
            if ($link) {

                $transitions = $link->transitions;
                $totalTransitions += $transitions;

                $profitFromLinkTransitions = $transitions * $offer->mastersProfit;
                $profit += $offer->mastersProfit;
                $totalProfit += $profitFromLinkTransitions;

                $financeItem = [
                    'name' => $offer->name,
                    'transitions' => $transitions,
                    'price' => round($offer->mastersProfit, 2),
                    'profit' => round($profitFromLinkTransitions, 2),
                    'created_at' => $link->created_at
                ];
                array_push($items, $financeItem);
            }
        }
        $total = [
            'totalLinks' => 'Total: ' . $totalLinks,
            'transitions' => $totalTransitions,
            'profit' => $profit,
            'totalProfit' => $totalProfit,
            'created_at' => null

        ];
        $finance = [
            'items' => $items,
            'total' => $total

        ];
        return $finance;
    }
    public static function advertFinance()
    {
        $items = [];
        $user = Auth::user();
        $offers = $user->createdOffers;
        //  Offer::where('advertiser_id', $user->id);
        $offersCount = $offers->count();
        $totalMasters = 0;
        $totalTransitions = 0;
        $totalExpenses = 0;

        foreach ($offers as $offer) {
            $transitions = 0;
            $transitions = 0;
            $expenses = 0;
            $masters = $offer->followers->count();
            if ($masters) {
                if ($offer->transitions()['transitions']) {
                    $transitions = $offer->transitions()['transitions'];
                    $expenses = $transitions * $offer->price;
                }
            }



            $totalMasters += $masters;
            $totalTransitions += $transitions;
            $totalExpenses += $expenses;
            $item = [
                'offer' => $offer->name,
                'followers' => $masters,
                'transitions' => $transitions,
                'price' => round($offer->price, 2),
                'expenses' => round($expenses, 2)
            ];
            array_push($items, $item);
        }
        $total = [
            'offers' => 'Total Offers: ' . $offersCount,
            'followers' => $totalMasters,
            'transitions' => $totalTransitions,
            'price' => null,
            'expenses' => $totalExpenses,
        ];
        $finance = [
            'items' => $items,
            'total' => $total

        ];
        return $finance;
    }
    public static function adminsFinance()
    {
        $items = [];
        $links = Link::all();
        $linksCount = $links->count();
        $offers = Offer::all();
        $offersCount = $offers->count();

        $totalPrice = 0;
        $totalTransitions = 0;
        $totalFailTransitions = 0;
        $totalProfit = 0;

        foreach ($links as $link) {

            $transitions = $link->transitions;
            $failTransitions = $link->fail_transitions;
            $price = $link->offer->appsProfit;
            $profit =  $price * $transitions;

            $totalTransitions +=  $transitions;
            $totalFailTransitions += $failTransitions;
            $totalPrice += $price;
            $totalProfit += $profit;

            $item = [
                'offerName' => $link->offer->name,
                'link' => $link->url,
                'price' => round($price, 2),
                'transitions' => $transitions,
                'fail_transitions' => $failTransitions,
                'profit' => round($profit, 2)
            ];

            array_push($items, $item);
        }

        $total = [
            'offerName' => $offersCount,
            'link' => $linksCount,
            'price' => round($totalPrice, 2),
            'transitions' => $totalTransitions,
            'fail_transitions' =>  $totalFailTransitions,
            'profit' => round($totalProfit, 2)
        ];
        $finance = [
            'items' => $items,
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
