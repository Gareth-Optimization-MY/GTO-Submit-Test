<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\ShopController;


class Store extends Model
{
    use HasFactory;
    protected $table = 'shopify_stores_data';
    protected $fillable = [
        'shop_url',
        'shopify_token',
        'is_trial_expired',
        'current_charge_id',
        'location_allowed',
    ];

    /**
     * Create 3-days trial for app.
     * @param string $shop_url
     * @return string|boolean
     */
    static function create_trial( $shop_url ){

        $store = Store::where('shop_url', $shop_url )->first();
        if( $store ){
            $first_view =  "https://".$shop_url.'/admin/apps/'.env('APP_NAME');
            $array = [
                "recurring_application_charge" => [
                    "name" => env('PLAN_NAME'),
                    "price" => env('PLAN_PRICE'),
                    "return_url" => $first_view,
                    "test" => env('PAYMENT_MODE',false),
                    "trial_days" => 14
                ]
            ];

            $charge = ShopController::shopify_rest_call($store->shopify_token, $shop_url, '/admin/api/'.env('SHOPIFY_API_VERSION','2022-07').'/recurring_application_charges.json', $array, 'POST');

            $result = json_decode($charge['response'], JSON_PRETTY_PRINT);
            $confirmation_url = $result['recurring_application_charge']['confirmation_url'];


            return $confirmation_url;

        }
    }

    /**
     * Create charge for app.
     * @param string $shop_url
     * @return string|boolean
     */
    static function create_charge_without_trail( $shop_url ){

        $store = Store::where('shop_url', $shop_url )->first();
        if( $store ){
            $first_view =  "https://".$shop_url.'/admin/apps/'.env('APP_NAME');
            $array = [
                "recurring_application_charge" => [
                    "name" => env('PLAN_NAME'),
                    "price" => env('PLAN_PRICE'),
                    "return_url" => $first_view,
                    "test" => env('PAYMENT_MODE',false)
                ]
            ];

            $charge = ShopController::shopify_rest_call($store->shopify_token, $shop_url, '/admin/api/'.env('SHOPIFY_API_VERSION','2022-07').'/recurring_application_charges.json', $array, 'POST');
            $result = json_decode($charge['response']);
            $confirmation_url = $result->recurring_application_charge->confirmation_url;


            return $confirmation_url;

        }
    }

    /**
     * Cancel charge for app.
     * @param string $shop_url
     * @return string|boolean
     */
    static function cancel_charge( $shop_url ){

        $store = Store::where('shop_url', $shop_url )->first();
        if( $store ){
            $first_view =  "https://".$shop_url.'/admin/apps/'.env('APP_NAME');

            $charge = ShopController::shopify_rest_call($store->shopify_token, $shop_url, '/admin/api/'.env('SHOPIFY_API_VERSION','2022-07').'/recurring_application_charges/'.$store->current_charge_id.'.json', [], 'DELETE');
            $first_view =  "https://".$shop_url.'/admin/apps/'.env('APP_NAME');
            return $first_view;

        }
    }
}
