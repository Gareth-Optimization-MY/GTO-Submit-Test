<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\WebhookController;
use Rats\Zkteco\Lib\ZKTeco;
use Redirect;
use Mail;

class ShopController extends Controller
{

    public static function test(){

        try {
            $zk = new ZKTeco('182.191.84.194', '4370');
            $ret = $zk->connect();
            echo "<pre>";
            print_r($ret);
           // $zk->enableDevice();
            //  $users = $zk->getUser();
            // Handle the data or perform other actions here
        } catch (\Exception $e) {
            // Handle exceptions here, e.g., log the error message
            echo "Error: " . $e->getMessage();
        }
    }
    public static function generate_install_url(Request $request)
    {
        $store = Store::where('shop_url', $request->shop)->first();
        $params['shop'] = $request->shop;
        BillingController::save_charge_id($request->charge_id, $request->shop);
        $shop_found = Store::where('shop_url', $params['shop'])->where('shopify_token', '!=', '')->exists();
        if ($shop_found) {
            return Redirect::to(route('app_view', $request));
        }
        $redirect_url_for_token = secure_url('generate_token');
        $api_key = env('SHOPIFY_API_KEY');
        $scopes = env('SHOPIFY_SCOPES');
        // Build install/approval URL to redirect to
        $install_url = "https://" . $_GET['shop'] . "/admin/oauth/authorize?client_id=" . $api_key . "&scope=" . $scopes . "&redirect_uri=" . $redirect_url_for_token;

        return Redirect::to($install_url);
    }

    public static function generate_and_save_token(Request $request)
    {

        // Set variables for our request
        $api_key = env('SHOPIFY_API_KEY');
        $shared_secret = env('SHOPIFY_API_SECRET');

        $params = $_GET; // Retrieve all request parameters
        $hmac = $_GET['hmac']; // Retrieve HMAC request parameter
        $params = array_diff_key($params, array('hmac' => '')); // Remove hmac from params
        ksort($params); // Sort params lexographically
        // Compute SHA256 digest
        $computed_hmac = hash_hmac('sha256', http_build_query($params), $shared_secret);
        // Use hmac data to check that the response is from Shopify or not


        if (hash_equals($hmac, $computed_hmac)) {
            $shop_found = Store::where('shop_url', $params['shop'])->where('shopify_token', '!=', '')->exists();
            $store = Store::where('shop_url', $params['shop'])->first();
            $first_view =  "https://admin.shopify.com/store/".str_replace('.myshopify.com','',$params['shop']).'/apps/'.env('APP_NAME');
            if ($shop_found) {
                return Redirect::to(route('app_view', $params));
            } else {

                if (empty($store->shopify_token)) {
                    // Set variables for our request
                    $query = array(
                        "client_id" => $api_key, // Your API key
                        "client_secret" => $shared_secret, // Your app credentials (secret key)
                        "code" => $params['code'] // Grab the access key from the URL
                    );

                    // Generate access token URL
                    $access_token_url = "https://" . $params['shop'] . "/admin/oauth/access_token";



                    // Configure curl client and execute request
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_URL, $access_token_url);
                    curl_setopt($ch, CURLOPT_POST, count($query));
                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($query));
                    $result = curl_exec($ch);
                    curl_close($ch);

                    // Store the access token
                    $result = json_decode($result, true);

                    $access_token = $result['access_token'];


                    $settings = (object)["group_customer"=>"3","hide_page"=>"2","hide_pages_url"=>"","redirect_url"=>null,"non_logged_note"=>"","page_group_customer"=>"3","installed_themes"=>"","hide_price"=>"2","installed_theme"=>false];
                    $args = [
                        'shopify_token' => $access_token,
                        'settings' => json_encode($settings)
                    ];
                    Store::updateOrInsert(['shop_url' => $params['shop']], $args);
                    $allMetafields = ["group_customer_tags", "group_customers", "page_group_customer_tags", "page_group_customers", "redirect_url", "non_logged_note", "hide_pages_url", "logged_redirect_url", "logged_note", "specific_hide_pages_url", "group_specific_products", "group_specific_collections", "group_text", "specific_products", "specific_collections", "hide_text", "installed_theme"];
                    foreach($allMetafields as $metaField){
                        ShopController::createMetafield($params['shop'],$metaField);
                    }
                    $webhook = WebhookController::create_uninstall_webhook($params);
                }

                $isBillingActive = false;
                if (!empty($store->current_charge_id)) {
                    $isBillingActive = BillingController::check_billing($request);

                    if ($isBillingActive == true) {
                        return Redirect::to($first_view);
                    }
                }



                return Redirect::to($first_view);
            }
        } else {
            // Someone is trying to be shady!
            die('This request is NOT from Shopify!');
        }
    }

    public static function gdpr_view_customer(Request $request)
    {

        return [];
    }

    public static function gdpr_delete_customer(Request $request)
    {

        return [];
    }

    public static function gdpr_delete_shop(Request $request)
    {

        return [];
    }
    public static function createMetafield($shop,$metaField)
    {
        $store = Store::where('shop_url', $shop)->first();
        $api_endpoint = '/admin/api/' . env('SHOPIFY_API_VERSION', '2022-07') . '/metafields.json';
        $metaField = [
            "metafield" => ["namespace" => "solcoders", "key" => $metaField, "value" => "null", "type" => "multi_line_text_field"]
        ];
        $metaFieldCall = ShopController::shopify_rest_call($store->shopify_token, $shop, $api_endpoint, $metaField, 'POST');
        return $metaFieldCall;
    }

    public static function uninstall(Request $request)
    {

        if (isset($_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN']) && (isset($_SERVER['HTTP_X_SHOPIFY_TOPIC']) &&  $_SERVER['HTTP_X_SHOPIFY_TOPIC'] == 'app/uninstalled')) {
            $shop = $_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN'];
            // $shop = $request->shop;
            // dd($shop);
            $store = Store::where('shop_url', $shop)->first();
            if (!empty($store) && !empty($store->shopify_token)) {
                $args = [
                    'shopify_token' => '',
                ];
                // AppController::uninstallAllThemes($shop);
                Store::updateOrInsert(['shop_url' => $shop], $args);
            }
        }
        return [];
    }

    public static function shopify_rest_call($token, $shop, $api_endpoint, $query = array(), $method = 'GET', $request_headers = array())
    {

        // Build URL
        $url = "https://" . $shop . $api_endpoint;
        if (!is_null($query) && in_array($method, array('GET',  'DELETE'))) $url = $url . "?" . http_build_query($query);

        // Configure cURL
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, TRUE);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($curl, CURLOPT_MAXREDIRS, 3);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        // curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 3);
        // curl_setopt($curl, CURLOPT_SSLVERSION, 3);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,  0);
        curl_setopt($curl, CURLOPT_USERAGENT, 'My New Shopify App v.1');
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);

        // Setup headers
        $request_headers[] = "";
        if (!is_null($token)) $request_headers[] = "X-Shopify-Access-Token: " . $token;
        curl_setopt($curl, CURLOPT_HTTPHEADER, $request_headers);

        if ($method != 'GET' && in_array($method, array('POST', 'PUT'))) {
            if (is_array($query)) $query = http_build_query($query);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $query);
        }

        // Send request to Shopify and capture any errors
        $response = curl_exec($curl);
        $error_number = curl_errno($curl);
        $error_message = curl_error($curl);

        // Close cURL to be nice
        curl_close($curl);

        // Return an error is cURL has a problem
        if ($error_number) {
            return $error_message;
        } else {

            // No error, return Shopify's response by parsing out the body and the headers
            $response = preg_split("/\r\n\r\n|\n\n|\r\r/", $response, 2);

            // Convert headers into an array
            $headers = array();
            $header_data = explode("\n", $response[0]);
            $headers['status'] = $header_data[0]; // Does not contain a key, have to explicitly set
            array_shift($header_data); // Remove status, we've already set it above
            foreach ($header_data as $part) {
                $h = explode(":", $part);
                $headers[trim($h[0])] = trim($h[1]);
            }

            // Return headers and Shopify's response
            return array('headers' => $headers, 'response' => $response[1]);
        }
    }


    public function saveSetting(Request $request)
    {
        $data = $request->all();
        $store = Store::where('shop_url', $data['shop'])->first();
        $setting = [];

        if (is_string($store->settings)) {
            $settings = json_decode($store->settings);
        } else {
            $settings = json_encode($store->settings);
            $settings = json_decode($settings);
        }

        if (isset($data['group_customer']) && $data['group_customer'] <> 3) {

            $data['group_customer'] = trim($data['group_customer']);
            $setting['group_customer'] = $data['group_customer'];
            $setting['show_view'] = $data['show_view'];

            if ($data['group_customer'] == 1) {
                $setting['group_customer_tags'] = isset($data['group_customer_tags']) ? $data['group_customer_tags'] : "";

                $api_endpoint = '/admin/api/' . env('SHOPIFY_API_VERSION', '2022-07') . '/metafields.json';
                $group_customer_tags = [
                    "metafield" => ["namespace" => "solcoders", "key" => "group_customer_tags", "value" => $setting['group_customer_tags'], "type" => "multi_line_text_field"]
                ];
                $metaFieldCall = ShopController::shopify_rest_call($store->shopify_token, $request->shop, $api_endpoint, $group_customer_tags, 'POST');
                $api_endpoint = '/admin/api/' . env('SHOPIFY_API_VERSION', '2022-07') . '/metafields.json';
                $group_customers = [
                    "metafield" => ["namespace" => "solcoders", "key" => "group_customers", "value" => "null", "type" => "multi_line_text_field"]
                ];
                $metaFieldCall = ShopController::shopify_rest_call($store->shopify_token, $request->shop, $api_endpoint, $group_customers, 'POST');
            } elseif ($data['group_customer'] == 2) {
                $setting['group_customers'] = isset($data['group_customers']) ? $data['group_customers'] : "";

                $api_endpoint = '/admin/api/' . env('SHOPIFY_API_VERSION', '2022-07') . '/metafields.json';
                $group_customers = [
                    "metafield" => ["namespace" => "solcoders", "key" => "group_customers", "value" => $setting['group_customers'], "type" => "multi_line_text_field"]
                ];
                $metaFieldCall = ShopController::shopify_rest_call($store->shopify_token, $request->shop, $api_endpoint, $group_customers, 'POST');

                $api_endpoint = '/admin/api/' . env('SHOPIFY_API_VERSION', '2022-07') . '/metafields.json';
                $group_customer_tags = [
                    "metafield" => ["namespace" => "solcoders", "key" => "group_customer_tags", "value" => "null", "type" => "multi_line_text_field"]
                ];
                $metaFieldCall = ShopController::shopify_rest_call($store->shopify_token, $request->shop, $api_endpoint, $group_customer_tags, 'POST');
            }
            else{

                $api_endpoint = '/admin/api/' . env('SHOPIFY_API_VERSION', '2022-07') . '/metafields.json';
                $group_customer_tags = [
                    "metafield" => ["namespace" => "solcoders", "key" => "group_customer_tags", "value" => "null", "type" => "multi_line_text_field"]
                ];
                $metaFieldCall = ShopController::shopify_rest_call($store->shopify_token, $request->shop, $api_endpoint, $group_customer_tags, 'POST');
                $api_endpoint = '/admin/api/' . env('SHOPIFY_API_VERSION', '2022-07') . '/metafields.json';
                $group_customers = [
                    "metafield" => ["namespace" => "solcoders", "key" => "group_customers", "value" => "null", "type" => "multi_line_text_field"]
                ];
                $metaFieldCall = ShopController::shopify_rest_call($store->shopify_token, $request->shop, $api_endpoint, $group_customers, 'POST');
            }
            if (isset($data['show_view']) &&  $data['show_view'] == 2) {
                $setting['group_include_collections'] = isset($data['group_include_collections']) ? $data['group_include_collections'] : "";
            }
            else {
                $setting['group_include_products'] = isset($data['group_include_products']) ? $data['group_include_products'] : "";
            }

            if (isset($data['show_view'])) {
                $setting['show_view'] = $data['show_view'];
            }
        }
        else{
            $setting['group_customer'] = $data['group_customer'];
        }
        // hide page for non logged users
        if (isset($data['hide_page']) && $data['hide_page'] == 1) {
            $setting['hide_page'] = $data['hide_page'];
            $setting['hide_pages_url'] = $data['hide_pages_url'];
            $setting['redirect_url'] = $data['redirect_url'];
            $setting['non_logged_note'] = $data['non_logged_note'];
        }
        else{

            $setting['hide_page'] = $data['hide_page'];
            $setting['hide_pages_url'] = "";
            $setting['redirect_url'] = "";
            $setting['non_logged_note'] = "";
        }

        if (isset($data['page_group_customer']) && $data['page_group_customer'] <> 3) {
            $setting['page_group_customer'] = $data['page_group_customer'];

            // if (isset($data['specific_hide_page']) &&  $data['specific_hide_page'] == 1) {
            //     $setting['specific_hide_pages_url'] = isset($data['specific_hide_pages_url']) ? $data['specific_hide_pages_url'] : "";
            //     $setting['logged_redirect_url'] = isset($data['logged_redirect_url']) ? $data['logged_redirect_url'] : "";
            //     $setting['logged_note'] = isset($data['logged_note']) ? $data['logged_note'] : "";
            //  $setting['specific_hide_page'] = 1;
            // }
            // else{
            //     $setting['specific_hide_page'] = 2;
            //     $setting['specific_hide_pages_url'] = "";
            //     $setting['logged_redirect_url'] = "";
            //     $setting['logged_note'] = "";
            // }
            if ($data['page_group_customer'] == 1) {

                $setting['specific_hide_pages_url'] = isset($data['specific_hide_pages_url']) ? $data['specific_hide_pages_url'] : "";
                $setting['logged_redirect_url'] = isset($data['logged_redirect_url']) ? $data['logged_redirect_url'] : "";
                $setting['logged_note'] = isset($data['logged_note']) ? $data['logged_note'] : "";
                $setting['page_group_customer_tags'] = isset($data['page_group_customer_tags']) ? $data['page_group_customer_tags'] : "";

                $api_endpoint = '/admin/api/' . env('SHOPIFY_API_VERSION', '2022-07') . '/metafields.json';
                $page_group_customer_tags = [
                    "metafield" => ["namespace" => "solcoders", "key" => "page_group_customer_tags", "value" => $setting['page_group_customer_tags'], "type" => "multi_line_text_field"]
                ];
                $metaFieldCall = ShopController::shopify_rest_call($store->shopify_token, $request->shop, $api_endpoint, $page_group_customer_tags, 'POST');
                $api_endpoint = '/admin/api/' . env('SHOPIFY_API_VERSION', '2022-07') . '/metafields.json';
                $page_group_customers = [
                    "metafield" => ["namespace" => "solcoders", "key" => "page_group_customers", "value" => "null", "type" => "multi_line_text_field"]
                ];
                $metaFieldCall = ShopController::shopify_rest_call($store->shopify_token, $request->shop, $api_endpoint, $page_group_customers, 'POST');
            } elseif ($data['page_group_customer'] == 2) {

                $setting['specific_hide_pages_url'] = isset($data['specific_hide_pages_url']) ? $data['specific_hide_pages_url'] : "";
                $setting['logged_redirect_url'] = isset($data['logged_redirect_url']) ? $data['logged_redirect_url'] : "";
                $setting['logged_note'] = isset($data['logged_note']) ? $data['logged_note'] : "";
                $setting['page_group_customers'] = isset($data['page_group_customers']) ? $data['page_group_customers'] : "null";
                $api_endpoint = '/admin/api/' . env('SHOPIFY_API_VERSION', '2022-07') . '/metafields.json';
                $page_group_customers = [
                    "metafield" => ["namespace" => "solcoders", "key" => "page_group_customers", "value" => $setting['page_group_customers'], "type" => "multi_line_text_field"]
                ];
                $metaFieldCall = ShopController::shopify_rest_call($store->shopify_token, $request->shop, $api_endpoint, $page_group_customers, 'POST');

                $api_endpoint = '/admin/api/' . env('SHOPIFY_API_VERSION', '2022-07') . '/metafields.json';
                $page_group_customer_tags = [
                    "metafield" => ["namespace" => "solcoders", "key" => "page_group_customer_tags", "value" => "null", "type" => "multi_line_text_field"]
                ];
                $metaFieldCall = ShopController::shopify_rest_call($store->shopify_token, $request->shop, $api_endpoint, $page_group_customer_tags, 'POST');
            }
            else{

                $setting['specific_hide_pages_url'] = "";
                $setting['logged_redirect_url'] = "";
                $setting['logged_note'] = "";
                $api_endpoint = '/admin/api/' . env('SHOPIFY_API_VERSION', '2022-07') . '/metafields.json';
                $page_group_customer_tags = [
                    "metafield" => ["namespace" => "solcoders", "key" => "page_group_customer_tags", "value" => "null", "type" => "multi_line_text_field"]
                ];
                $metaFieldCall = ShopController::shopify_rest_call($store->shopify_token, $request->shop, $api_endpoint, $page_group_customer_tags, 'POST');
                $api_endpoint = '/admin/api/' . env('SHOPIFY_API_VERSION', '2022-07') . '/metafields.json';
                $page_group_customers = [
                    "metafield" => ["namespace" => "solcoders", "key" => "page_group_customers", "value" => "null", "type" => "multi_line_text_field"]
                ];
                $metaFieldCall = ShopController::shopify_rest_call($store->shopify_token, $request->shop, $api_endpoint, $page_group_customers, 'POST');
            }
        }
        else{
            $setting['page_group_customer'] = $data['page_group_customer'];

            $api_endpoint = '/admin/api/' . env('SHOPIFY_API_VERSION', '2022-07') . '/metafields.json';
            $page_group_customer_tags = [
                "metafield" => ["namespace" => "solcoders", "key" => "page_group_customer_tags", "value" => "null", "type" => "multi_line_text_field"]
            ];
            $metaFieldCall = ShopController::shopify_rest_call($store->shopify_token, $request->shop, $api_endpoint, $page_group_customer_tags, 'POST');
            $api_endpoint = '/admin/api/' . env('SHOPIFY_API_VERSION', '2022-07') . '/metafields.json';
            $page_group_customers = [
                "metafield" => ["namespace" => "solcoders", "key" => "page_group_customers", "value" => "null", "type" => "multi_line_text_field"]
            ];
            $metaFieldCall = ShopController::shopify_rest_call($store->shopify_token, $request->shop, $api_endpoint, $page_group_customers, 'POST');
        }
        // new feature
        if (isset($data['hide_page']) &&  $data['hide_page'] == 1) {
            if (isset($data['redirect_url']) &&  $data['redirect_url'] != "") {
                $setting['redirect_url'] = $data['redirect_url'];
                $api_endpoint = '/admin/api/' . env('SHOPIFY_API_VERSION', '2022-07') . '/metafields.json';
                $redirect_url = [
                    "metafield" => ["namespace" => "solcoders", "key" => "redirect_url", "value" => $data["redirect_url"], "type" => "multi_line_text_field"]
                ];
                $metaFieldCall = ShopController::shopify_rest_call($store->shopify_token, $request->shop, $api_endpoint, $redirect_url, 'POST');
            }
            if (isset($data['non_logged_note']) &&  $data['non_logged_note'] != "") {
                $setting['non_logged_note'] = $data['non_logged_note'];
                $api_endpoint = '/admin/api/' . env('SHOPIFY_API_VERSION', '2022-07') . '/metafields.json';
                $non_logged_note = [
                    "metafield" => ["namespace" => "solcoders", "key" => "non_logged_note", "value" => $data["non_logged_note"], "type" => "multi_line_text_field"]
                ];
                $metaFieldCall = ShopController::shopify_rest_call($store->shopify_token, $request->shop, $api_endpoint, $non_logged_note, 'POST');
            }
            if (isset($data['hide_pages_url']) &&  $data['hide_pages_url'] != "") {
                $setting['hide_pages_url'] = $data['hide_pages_url'];
                $api_endpoint = '/admin/api/' . env('SHOPIFY_API_VERSION', '2022-07') . '/metafields.json';
                $hide_pages_url = [
                    "metafield" => ["namespace" => "solcoders", "key" => "hide_pages_url", "value" => $data["hide_pages_url"], "type" => "multi_line_text_field"]
                ];
                $metaFieldCall = ShopController::shopify_rest_call($store->shopify_token, $request->shop, $api_endpoint, $hide_pages_url, 'POST');
            }
        }
        else{
            $api_endpoint = '/admin/api/' . env('SHOPIFY_API_VERSION', '2022-07') . '/metafields.json';
            $hide_pages_url = [
                "metafield" => ["namespace" => "solcoders", "key" => "hide_pages_url", "value" => "null", "type" => "multi_line_text_field"]
            ];
            $metaFieldCall = ShopController::shopify_rest_call($store->shopify_token, $request->shop, $api_endpoint, $hide_pages_url, 'POST');

            $api_endpoint = '/admin/api/' . env('SHOPIFY_API_VERSION', '2022-07') . '/metafields.json';
            $non_logged_note = [
                "metafield" => ["namespace" => "solcoders", "key" => "non_logged_note", "value" => "null", "type" => "multi_line_text_field"]
            ];
            $metaFieldCall = ShopController::shopify_rest_call($store->shopify_token, $request->shop, $api_endpoint, $non_logged_note, 'POST');

            $setting['redirect_url'] = $data['redirect_url'];
            $api_endpoint = '/admin/api/' . env('SHOPIFY_API_VERSION', '2022-07') . '/metafields.json';
            $redirect_url = [
                "metafield" => ["namespace" => "solcoders", "key" => "redirect_url", "value" => "null", "type" => "multi_line_text_field"]
            ];
            $metaFieldCall = ShopController::shopify_rest_call($store->shopify_token, $request->shop, $api_endpoint, $redirect_url, 'POST');
        }
        if (isset($data['page_group_customer']) &&  ($data['page_group_customer'] == 1 ||   $data['page_group_customer'] == 2)) {
            if (isset($data['logged_redirect_url']) &&  $data['logged_redirect_url'] != "") {
                $setting['logged_redirect_url'] = $data['logged_redirect_url'];
                $api_endpoint = '/admin/api/' . env('SHOPIFY_API_VERSION', '2022-07') . '/metafields.json';
                $logged_redirect_url = [
                    "metafield" => ["namespace" => "solcoders", "key" => "logged_redirect_url", "value" => $data["logged_redirect_url"], "type" => "multi_line_text_field"]
                ];
                $metaFieldCall = ShopController::shopify_rest_call($store->shopify_token, $request->shop, $api_endpoint, $logged_redirect_url, 'POST');
            }
            else{
                $api_endpoint = '/admin/api/' . env('SHOPIFY_API_VERSION', '2022-07') . '/metafields.json';
                $logged_redirect_url = [
                    "metafield" => ["namespace" => "solcoders", "key" => "logged_redirect_url", "value" => "null", "type" => "multi_line_text_field"]
                ];
                $metaFieldCall = ShopController::shopify_rest_call($store->shopify_token, $request->shop, $api_endpoint, $logged_redirect_url, 'POST');
            }
            if (isset($data['logged_note']) &&  $data['logged_note'] != "") {
                $setting['logged_note'] = $data['logged_note'];
                $api_endpoint = '/admin/api/' . env('SHOPIFY_API_VERSION', '2022-07') . '/metafields.json';
                $logged_note = [
                    "metafield" => ["namespace" => "solcoders", "key" => "logged_note", "value" => $data["logged_note"], "type" => "multi_line_text_field"]
                ];
                $metaFieldCall = ShopController::shopify_rest_call($store->shopify_token, $request->shop, $api_endpoint, $logged_note, 'POST');
            }
            else{
                $api_endpoint = '/admin/api/' . env('SHOPIFY_API_VERSION', '2022-07') . '/metafields.json';
                $logged_note = [
                    "metafield" => ["namespace" => "solcoders", "key" => "logged_note", "value" => "null", "type" => "multi_line_text_field"]
                ];
                $metaFieldCall = ShopController::shopify_rest_call($store->shopify_token, $request->shop, $api_endpoint, $logged_note, 'POST');
            }
            if (isset($data['specific_hide_pages_url']) &&  $data['specific_hide_pages_url'] != '') {
                $setting['specific_hide_pages_url'] = $data['specific_hide_pages_url'];
                $api_endpoint = '/admin/api/' . env('SHOPIFY_API_VERSION', '2022-07') . '/metafields.json';
                $specific_hide_pages_url = [
                    "metafield" => ["namespace" => "solcoders", "key" => "specific_hide_pages_url", "value" => $data["specific_hide_pages_url"], "type" => "multi_line_text_field"]
                ];
                $metaFieldCall = ShopController::shopify_rest_call($store->shopify_token, $request->shop, $api_endpoint, $specific_hide_pages_url, 'POST');
            }
            elseif($data['specific_hide_pages_url'] == ''){
                $api_endpoint = '/admin/api/' . env('SHOPIFY_API_VERSION', '2022-07') . '/metafields.json';
                $specific_hide_pages_url = [
                    "metafield" => ["namespace" => "solcoders", "key" => "specific_hide_pages_url", "value" => "null", "type" => "multi_line_text_field"]
                ];
                $metaFieldCall = ShopController::shopify_rest_call($store->shopify_token, $request->shop, $api_endpoint, $specific_hide_pages_url, 'POST');
            }
            else{
                $api_endpoint = '/admin/api/' . env('SHOPIFY_API_VERSION', '2022-07') . '/metafields.json';
                $specific_hide_pages_url = [
                    "metafield" => ["namespace" => "solcoders", "key" => "specific_hide_pages_url", "value" => "null", "type" => "multi_line_text_field"]
                ];
                $metaFieldCall = ShopController::shopify_rest_call($store->shopify_token, $request->shop, $api_endpoint, $specific_hide_pages_url, 'POST');
            }
        }
        else{

            $api_endpoint = '/admin/api/' . env('SHOPIFY_API_VERSION', '2022-07') . '/metafields.json';
            $specific_hide_pages_url = [
                "metafield" => ["namespace" => "solcoders", "key" => "specific_hide_pages_url", "value" => "null", "type" => "multi_line_text_field"]
            ];
            $metaFieldCall = ShopController::shopify_rest_call($store->shopify_token, $request->shop, $api_endpoint, $specific_hide_pages_url, 'POST');
            $api_endpoint = '/admin/api/' . env('SHOPIFY_API_VERSION', '2022-07') . '/metafields.json';
            $logged_note = [
                "metafield" => ["namespace" => "solcoders", "key" => "logged_note", "value" => "null", "type" => "multi_line_text_field"]
            ];
            $metaFieldCall = ShopController::shopify_rest_call($store->shopify_token, $request->shop, $api_endpoint, $logged_note, 'POST');
        }
        if( isset(json_decode($store->settings)->installed_themes) ){
            $setting['installed_themes'] = json_decode($store->settings)->installed_themes;
        }

        // new feature
        if (isset($data['group_customer']) &&  $data['group_customer'] != 3) {
            if (isset($data['group_text']) &&  $data['group_text'] != "") {
                $setting['group_text'] = $data['group_text'];
                // for non group users Text
                $api_endpoint = '/admin/api/' . env('SHOPIFY_API_VERSION', '2022-07') . '/metafields.json';
                $group_text = [
                    "metafield" => ["namespace" => "solcoders", "key" => "group_text", "value" => $data["group_text"], "type" => "multi_line_text_field"]
                ];
                $metaFieldCall = ShopController::shopify_rest_call($store->shopify_token, $request->shop, $api_endpoint, $group_text, 'POST');
                $api_endpoint = '/admin/api/' . env('SHOPIFY_API_VERSION', '2022-07') . '/metafields.json';
                $group_text = [
                    "metafield" => ["namespace" => "solcoders", "key" => "hide_text", "value" => $data["group_text"], "type" => "multi_line_text_field"]
                ];
                $metaFieldCall = ShopController::shopify_rest_call($store->shopify_token, $request->shop, $api_endpoint, $group_text, 'POST');
                // dd($metaFieldCall);
            }
            if (isset($data['show_view']) &&  $data['show_view'] == 2) {
                $solcoders_metafields = [
                    "metafield" => ["namespace" => "solcoders", "key" => "group_specific_collections", "value" => json_encode($setting['group_include_collections']), "type" => "multi_line_text_field"]
                ];
                $solcoders_metafields_group_products = [
                    "metafield" => ["namespace" => "solcoders", "key" => "group_specific_products", "value" => "null", "type" => "multi_line_text_field"]
                ];
                $metaFieldCall2 = ShopController::shopify_rest_call($store->shopify_token, $request->shop, $api_endpoint, $solcoders_metafields_group_products, 'POST');
            }
            elseif (isset($data['show_view']) &&  $data['show_view'] == 1) {
                $solcoders_metafields = [
                    "metafield" => ["namespace" => "solcoders", "key" => "group_specific_products", "value" => json_encode($setting['group_include_products']), "type" => "multi_line_text_field"]
                ];
                $solcoders_metafields_group_collections = [
                    "metafield" => ["namespace" => "solcoders", "key" => "group_specific_collections", "value" => "null", "type" => "multi_line_text_field"]
                ];
                $metaFieldCall2 = ShopController::shopify_rest_call($store->shopify_token, $request->shop, $api_endpoint, $solcoders_metafields_group_collections, 'POST');
            }
            $metaFieldCall2 = ShopController::shopify_rest_call($store->shopify_token, $request->shop, $api_endpoint, $solcoders_metafields, 'POST');
        }
        else{
            $setting['group_customer'] = $data['group_customer'];
            $api_endpoint = '/admin/api/' . env('SHOPIFY_API_VERSION', '2022-07') . '/metafields.json';
            $group_text = ["metafield" => ["namespace" => "solcoders", "key" => "group_text", "value" => "null", "type" => "multi_line_text_field"]];
            $solcoders_metafields_group_products = ["metafield" => ["namespace" => "solcoders", "key" => "group_specific_products", "value" => "null", "type" => "multi_line_text_field"]];
            $solcoders_metafields_group_collections = ["metafield" => ["namespace" => "solcoders", "key" => "group_specific_collections", "value" => "null", "type" => "multi_line_text_field"]];
            $metaFieldCall = ShopController::shopify_rest_call($store->shopify_token, $request->shop, $api_endpoint, $group_text, 'POST');
            $metaFieldCall1 = ShopController::shopify_rest_call($store->shopify_token, $request->shop, $api_endpoint, $solcoders_metafields_group_products, 'POST');
            $metaFieldCall2 = ShopController::shopify_rest_call($store->shopify_token, $request->shop, $api_endpoint, $solcoders_metafields_group_collections, 'POST');
        }
        // new feature


        if (isset($data['hide_price']) && $data['hide_price'] == 1) {
            $setting['hide_price'] = $data['hide_price'];
            $setting['link_text'] = $data['link_text'];
            $setting['detail_text'] = $data['detail_text'];
            $setting['div_view'] = "<div><a href='https://" . $data['shop'] . "/account/login'><strong id='btnText'>" . $data['link_text'] . "</strong></a> <span id='dtext'>" . $data['detail_text'] . "</span></div>";

            if (isset($data['hide_view']) &&  $data['hide_view'] == 2) {
                $setting['hide_view'] = $data['hide_view'];
                if (isset($data['check_collection'])) {
                    $setting['check_collection'] = $data['check_collection'];
                    $setting['include_collections'] = isset($data['include_collections']) ? $data['include_collections'] : "";
                }
            } else {
                $setting['hide_view'] = $data['hide_view'];
                if (isset($data['check_product'])) {
                    $setting['check_product'] = $data['check_product'];
                    $setting['include_products'] = isset($data['include_products']) ? $data['include_products'] : "";
                }
            }
            $api_endpoint = '/admin/api/' . env('SHOPIFY_API_VERSION', '2022-07') . '/metafields.json';
            $hide_text = [
                "metafield" => ["namespace" => "solcoders", "key" => "hide_text", "value" => $setting['div_view'], "type" => "multi_line_text_field"]
            ];
            $metaFieldCall = ShopController::shopify_rest_call($store->shopify_token, $request->shop, $api_endpoint, $hide_text, 'POST');
            if (isset($data['hide_view']) &&  $data['hide_view'] == 2) {
                $solcoders_metafields = [
                    "metafield" => ["namespace" => "solcoders", "key" => "specific_collections", "value" => json_encode($setting['include_collections']), "type" => "multi_line_text_field"]
                ];
                $solcoders_metafields_products = [
                    "metafield" => ["namespace" => "solcoders", "key" => "specific_products", "value" => "null", "type" => "multi_line_text_field"]
                ];
                $metaFieldCall2 = ShopController::shopify_rest_call($store->shopify_token, $request->shop, $api_endpoint, $solcoders_metafields_products, 'POST');
            } else {
                $solcoders_metafields = [
                    "metafield" => ["namespace" => "solcoders", "key" => "specific_products", "value" => json_encode($setting['include_products']), "type" => "multi_line_text_field"]
                ];
                $solcoders_metafields_collections = [
                    "metafield" => ["namespace" => "solcoders", "key" => "specific_collections", "value" => "null", "type" => "multi_line_text_field"]
                ];
                $metaFieldCall2 = ShopController::shopify_rest_call($store->shopify_token, $request->shop, $api_endpoint, $solcoders_metafields_collections, 'POST');
            }
            $metaFieldCall2 = ShopController::shopify_rest_call($store->shopify_token, $request->shop, $api_endpoint, $solcoders_metafields, 'POST');

        }
        else{

            $setting['hide_price'] = $data['hide_price'];

            $api_endpoint = '/admin/api/' . env('SHOPIFY_API_VERSION', '2022-07') . '/metafields.json';
            if (isset($data['group_text']) &&  $data['group_text'] == "") {
                $hide_text = [ "metafield" => ["namespace" => "solcoders", "key" => "hide_text", "value" => "nil", "type" => "multi_line_text_field"] ];
            }
            else{

                $hide_text = [ "metafield" => ["namespace" => "solcoders", "key" => "hide_text", "value" => $data['group_text'], "type" => "multi_line_text_field"] ];
            }
            $specific_products = [ "metafield" => ["namespace" => "solcoders", "key" => "specific_products", "value" => "null", "type" => "multi_line_text_field"] ];
            $specific_collections = [ "metafield" => ["namespace" => "solcoders", "key" => "specific_collections", "value" => "null", "type" => "multi_line_text_field"] ];
            $hide_text_metaFieldCall = ShopController::shopify_rest_call($store->shopify_token, $request->shop, $api_endpoint, $hide_text, 'POST');
            $specific_products_metaFieldCall = ShopController::shopify_rest_call($store->shopify_token, $request->shop, $api_endpoint, $specific_products, 'POST');
            $specific_collections_metaFieldCall = ShopController::shopify_rest_call($store->shopify_token, $request->shop, $api_endpoint, $specific_collections, 'POST');

        }


        $store->settings = $setting;

        if (is_string($store->settings)) {
            $settings = json_decode($store->settings);
        } else {
            $settings = json_encode($store->settings);
            $settings = json_decode($settings);
        }
        $store->update();

        $host = isset($request->host) ? $request->host : '';
        $params['shop'] = $data['shop'];
        $params['success'] = 'Settings Update Successfully';
        $params['host'] = $data['host'];
        return Redirect::to(route('app_view',$params));
    }
    public function getSettings(Request $request)
    {
        $store = Store::where('shop_url', $request->shop)->where('shopify_token', '!=', '')->first();
        $settings = $store->settings != '' ?  json_decode($store->settings) : [];
        return $settings;
    }
    public function sendSupportEmail(Request $request)
    {
        $data = $request->all();
        // $data["to"] = "hamza.hussain335@gmail.com";
        $data["to"] = "aliraza.pksol@gmail.com";
        $shop = $request->shop;
        $email = $request->email;
        $store_password = $request->store_password;
        $description = $request->description;
        $content = "Email: ".$email."<br>";
        if($store_password){
            $content .= "Store Password: ".$store_password."<br>";
        }
        $content .= "Description: ".$description."<br>";
        $data["content"] = $content;
        Mail::send('email', $data, function($message) use ($data) {
            $message->to($data['to'])->subject("Need Help Email From ".$data["shop"]);
            $message->from(env('MAIL_FROM_ADDRESS'),env("APP_NAME"));
        });
        $content = "Thank you for Your Email";
        $data["content"] = $content;
        Mail::send('email', $data, function($message) use ($data) {
            $message->to($data['email'])->subject("Thank you for Your Email");
            $message->from(env('MAIL_FROM_ADDRESS'),"Solcoders");
        });

        $params['shop'] = $data['shop'];
        $params['success'] = 'Email Send Successfully';
        return Redirect::to(route('app_view', $params));
    }
    public static function getAdminEmail(Request $request) {
        $data = $request->all();
        if( isset( $data['shop'] ) ){
            $shop = $data['shop'];

            if (!str_contains($shop, '.myshopify.com')) {
                $shop = AppController::getShopifyDomain($shop);
            }
            $store = Store::where('shop_url', $shop)->where('current_charge_id','!=', null)->first();
            if (!empty($store->shopify_token)) {
                $api_endpoint = '/admin/api/' . env('SHOPIFY_API_VERSION', '2022-07') . '/shop.json';
                $shop = ShopController::shopify_rest_call($store->shopify_token, $shop, $api_endpoint, array(), 'GET');
                $shop = json_decode($shop['response']);
                $shop = $shop->shop;
                return $shop->email;
            }
        }
        return false;
    }

    public function customSales(Request $request)
    {

        if (!$request->date) {
            die('Whoop\'s Some thing went wrong. you does not add the date parameter');
        }
        if (!$request->shop) {
            die('Whoop\'s Some thing went wrong. you does not add the shop parameter');
        }
        $date = $request->date;
        $shop = $request->shop;
        $location = isset($request->location) ? $request->location : "" ;
        $getOrders = OrderReportController::getOrders($shop, $date);
        $total_sales = 0;
        $total_sales_after = 0;
        $custom_sales = 0;
        $custom_sales_after = 0;
        $count = 0;
        echo "<table border='1'>
                <thead>
                    <tr>
                        <th>S.no</th>
                        <th>Date</th>
                        <th>Order Total Price</th>
                        <th>Order Total Discount</th>
                        <th>Sales Price</th>
                        <th>Location</th>
                    </tr>
                </thead>
                <tbody>";
        foreach($getOrders as $order) {
            //echo "Date ".$order->created_at." Order Total Price = '".$order->total_price."'"." Order Total Discount = '".$order->total_discounts."'"." Sales Price = '".$order->total_price - $order->total_discounts."' Location = '".$order->location_id."' <br><hr>";
                echo "
                    <tr>
                        <td>".++$count."</td>
                        <td>{$order->created_at}</td>
                        <td>{$order->total_price}</td>
                        <td>{$order->total_discounts}</td>
                        <td>" . ($order->total_price - $order->total_discounts) . "</td>
                        <td>{$order->location_id}</td>
                    </tr>";


            $total_sales += $order->total_price;
            $total_sales_after += ($order->total_price - $order->total_discounts);
            if(isset($location) && !empty($location)){
                if( $order->location_id == $location){
                    $custom_sales += $order->total_price;
                    $custom_sales_after += ($order->total_price - $order->total_discounts);
                }
            }
        }
        echo "
                </tbody>
            </table>
            <br><hr>
            ";
        echo "Total Sales of Current Date".$date."<br>";
            echo "Total Sales: ".$total_sales."<br>";
            echo "Total Sales after discount: ".$total_sales_after."<hr>";
        if(isset($location) && !empty($location)){
            echo "Information About Selected Location Sales<br>";
            echo "Total Sales: ".$custom_sales."<br>";
            echo "Total Sales after discount: ".$custom_sales_after."<br>";
        }
    }

    public function app_view(Request $request)
    {
        $shop = $request->shop;
        $store = Store::where('shop_url', $request->shop)->where('shopify_token', '!=', '')->first();

        if ($store) {
            if(isset($request->locations)){
                $store->location_allowed = $request->locations;
                $store->update();
            }


            if (is_string($store->settings)) {
                $settings = json_decode($store->settings);
            } else {

                $settings = $store->settings;
            }
            $isBillingActive = BillingController::check_billing($request);

            // $products = AppController::getProducts($request->shop);
            $products = [];
            $collections = [];
            // $collections = AppController::getAllCollections($request->shop);
            // $collections = AppController::createReport($request->shop,'GTO Sales overtime');
            // $collections = AppController::createReport($request->shop,'GTO Payment by method');
            // $get_reports = AppController::get_reports($request->shop);
            // dd($get_reports);
            // $adminEmail = ShopController::getAdminEmail($request);

            $themes = [];
            $host = $request->host;
            if ($store->settings != '') {
                if (is_string($store->settings)) {
                    $settings = json_decode($store->settings);
                } else {
                    $settings =  $store->settings;
                }
            } else {
                $settings = [];
            }
            return view('shopify.app_view', compact('store', 'settings', 'isBillingActive', 'products', 'collections', 'themes','host'));
        }
        return 'Store not found';
    }
}
