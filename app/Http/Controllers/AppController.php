<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ShopController;
use App\Models\Store;
use App\Models\Variable;
use App\Models\Mall;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Response;

class AppController extends Controller
{

    public static function getShopifyDomain($url)
    {
        $response = Http::get("https://".$url);
        $string = $response->body();
        $explode = explode('Shopify.shop = "',$string);
        $explode = explode('Shopify.locale = "',$explode[1]);
        $shop=str_replace("\";\n","",$explode[0]);
        return $shop;
    }

    public function downloadFiles($filenames)
    {
        foreach (json_decode($filenames) as $filename) {
            $file_path = public_path('reports/' . $filename);
            if (file_exists($file_path)) {
                return Response::download($file_path, $filename, [
                    'Content-Length: ' . filesize($file_path)
                ]);
            }
        }

    }
    public static function saveSettings(Request $request)
    {
        $shop = $request->shop;
        $data = $request->all();
        $store = Store::where('shop_url', $shop)->first();
        $setting = [];

        if (is_string($store->settings)) {
            $settings = json_decode($store->settings);
        } else {
            $settings = json_encode($store->settings);
            $settings = json_decode($settings);
        }

        $setting['setting_number'] = $data['setting_number'];

        $store->settings = $setting;

        if (is_string($store->settings)) {
            $settings = json_decode($store->settings);
        } else {
            $settings = json_encode($store->settings);
            $settings = json_decode($settings);
        }
        $store->update();
        return response()->json('Success');

    }
    public static function saveForm(Request $request)
    {
        $variable = Variable::where('shop',$request->shop)->where('mall_id',$request->mall_id)->first();
        $mall = Mall::find($request->mall_id);
        $mall->starting_number = $request->StartingNumber ?? '';
        $mall->save();

        if(empty($variable)){
            $variable = new Variable;
        }
        if($request->Cash){
            $variable->cash = $request->Cash;
            $variable->tng = $request->TNG;
            $variable->visa = $request->Visa;
            $variable->master_card = $request->MasterCard;
            $variable->amex = $request->Amex;
            $variable->vouchers = $request->Voucher;
            $variable->others = $request->Others;
            $variable->shop = $request->shop;
            $variable->mall_id = $request->mall_id;
            $variable->save();
            return $variable;
        }
        else{
            return $mall;
        }


    }
    public static function getThemes($shop)
    {
        $store = Store::where('shop_url', $shop)->first();

        if (!empty($store->shopify_token)) {
            $api_endpoint = '/admin/api/' . env('SHOPIFY_API_VERSION', '2022-07') . '/themes.json';
            $themes = ShopController::shopify_rest_call($store->shopify_token, $shop, $api_endpoint, array(), 'GET');
            $themes = json_decode($themes['response']);
            $themes = $themes->themes;
            return $themes;
        } else {
            return response(['Store not Found']);
        }
    }
    public static function detectEmbededApp($shop, $value)
    {
        $store = Store::where('shop_url', $shop)->first();
        $settings = $store->settings;
        if (is_string($store->settings)) {
            $settings = json_decode($store->settings);
        }
        $settings->installed_theme = $value;
        $store->update();
    }
    public static function removeAssetData($shop, $theme_id, $filename)
    {
        $store = Store::where('shop_url', $shop)->first();
        $settings = $store->settings;
        if (is_string($store->settings)) {
            $settings = json_decode($store->settings);
        }
        AppController::detectEmbededApp($shop, false);
        if (!empty($store->shopify_token)) {
            $file = AppController::getAsset($shop, $theme_id, $filename);

            if(!empty($file)){
                $updatedValue = $file->value;
                if ($filename == 'sections/main-product.liquid') {
                    if (str_contains($updatedValue, "{%comment%} solcoders_aliraza_start {%- endcomment -%}")) {

                        // for price hide start
                        $updatedValue = str_replace("{%- when 'price' -%}\n            {%comment%} sltvp_start {%- endcomment -%}\n            {% capture sltvp_specific_products %} {% if shop.metafields.solcoders.specific_products and  shop.metafields.solcoders.specific_products != 'null' %} {{ shop.metafields.solcoders.specific_products }} {% else %} \"\" {% endif %} {% endcapture %}\n            {% capture sltvp_specific_collections %} {% if shop.metafields.solcoders.specific_collections and  shop.metafields.solcoders.specific_collections != 'null' %} {{ shop.metafields.solcoders.specific_collections }} {% else %} \"\" {% endif %} {% endcapture %}\n            {% capture group_specific_products %} {% if shop.metafields.solcoders.group_specific_products and  shop.metafields.solcoders.group_specific_products != 'null' %} {{ shop.metafields.solcoders.group_specific_products }} {% else %} \"\" {% endif %} {% endcapture %}\n            {% capture group_specific_collections %} {% if shop.metafields.solcoders.group_specific_collections and  shop.metafields.solcoders.group_specific_collections != 'null' %} {{ shop.metafields.solcoders.group_specific_collections }} {% else %} \"\" {% endif %} {% endcapture %}\n            {% capture product_type %}\"{{ product.collections[0].id }}\"{% endcapture %}\n            {%- assign sltvp_condition = false  -%}\n              {% if customer == nil %}\n                {%- assign sltvp_condition = false  -%}\n                {%- assign logged_customer = false  -%}\n                {%- if shop.metafields.solcoders.hide_text == '' -%}\n                    {%- assign sltvp_condition = true  -%}\n                {% else %}\n                    {%- assign sltvp_condition = false  -%}\n                {% endif %}\n                {%- if sltvp_specific_collections contains '\"\"' or sltvp_specific_products contains '\"\"'  -%}\n                    {%- if shop.metafields.solcoders.hide_text == '' -%}\n                        {%- assign sltvp_condition = true  -%}\n                    {% endif %} \n                {% else %}\n                {% unless sltvp_specific_products contains product.id or sltvp_specific_collections contains product_type or group_specific_products contains product.id or group_specific_collections contains product_type %}\n                        {%- assign sltvp_condition = true  -%}\n                    {%- endunless  -%}\n                {% endif %}\n              {% else %}\n                {% capture sltvp_customer_tags %}{% if shop.metafields.solcoders.group_customer_tags and  shop.metafields.solcoders.group_customer_tags != 'null' %}{{ shop.metafields.solcoders.group_customer_tags }}{% else %}null{% endif %}{% endcapture %}\n                {% assign sltvp_customer_tags = sltvp_customer_tags | split: ',' %}\n                {% capture sltvp_customers %}{% if shop.metafields.solcoders.group_customers and  shop.metafields.solcoders.group_customers != 'null' %}{{ shop.metafields.solcoders.group_customers }}{% else %}null{% endif %}{% endcapture %}\n                    {% if sltvp_customer_tags contains 'null' %}\n                      {% if sltvp_customers contains 'null' %}\n                          {%- assign sltvp_condition = true  -%}\n                      {% else %}\n                        {% if  sltvp_customers contains customer.email %}\n                          {%- assign sltvp_condition = true  -%}\n                        {% else %}\n                          {%- assign sltvp_condition = false  -%}\n                          {%- assign logged_customer = true  -%}\n                        {% endif %}\n                      {% endif %}  \n                    {% else %}\n                        {% assign tagTrue = false %}\n                        {% for sltvp_tag in sltvp_customer_tags %}\n                            {% for customer_tag in customer.tags %}\n                                {% if customer_tag == sltvp_tag %}\n                                    {% assign tagTrue = true %}\n                                    {% break %}\n                                {% endif %}\n                            {% endfor %}\n                        {% endfor %}\n                        {% if tagTrue  %}\n                            {%- assign sltvp_condition = true  -%}\n                        {% else %}\n                            {%- assign sltvp_condition = false  -%}\n                            {%- assign logged_customer = true  -%}\n                        {% endif %}\n                    {% endif %}\n              {% endif %}\n            {%- if sltvp_condition -%}\n", "{%- when 'price' -%}", $updatedValue);
                        // for price hide end
                        $updatedValue = str_replace("{%- else -%}\n              {% if logged_customer %}\n                  {{ shop.metafields.solcoders.group_text }}\n              {%- else -%}\n                  {{ shop.metafields.solcoders.hide_text }}\n              {%- endif -%}\n          {%- endif -%}\n          {%- when 'inventory' -%}\n", "{%- when 'inventory' -%}", $updatedValue);
                        // for quantity hide start
                        $updatedValue = str_replace("{%- when 'quantity_selector' -%}\n          {%comment%} solcoders_aliraza_start {%- endcomment -%}\n          {%- if sltvp_condition -%}\n", "{%- when 'quantity_selector' -%}", $updatedValue);
                        // for quantity hide end
                        $updatedValue = str_replace("{%- endif -%}\n              {%comment%} solcoders_aliraza_end {%- endcomment -%}\n          {%- when 'popup' -%}", "{%- when 'popup' -%}", $updatedValue);
                        // for add to cart button hide start
                        $updatedValue = str_replace("{%- when 'buy_buttons' -%}\n          {%comment%} solcoders_aliraza_start {%- endcomment -%}\n          {%- if sltvp_condition -%}\n", "{%- when 'buy_buttons' -%}", $updatedValue);
                        // for add to cart button hide end
                        $updatedValue = str_replace("{%- endif -%}\n              {%comment%} solcoders_aliraza_end {%- endcomment -%}\n          {%- when 'rating' -%}", "{%- when 'rating' -%}", $updatedValue);
                        $api_endpoint = '/admin/api/' . env('SHOPIFY_API_VERSION', '2022-07') . '/themes/' . $theme_id . '/assets.json';
                        $asset = ['asset' => [
                            "key" => $filename,
                            "value" => $updatedValue
                        ]];
                        $update = ShopController::shopify_rest_call($store->shopify_token, $shop, $api_endpoint, $asset, 'PUT');

                        $update = json_decode($update['response']);
                    }

                }
                if ($filename == 'snippets/card-product.liquid') {
                    if (str_contains($updatedValue, "{%comment%} solcoders_aliraza_start {%- endcomment -%}")) {
                        $updatedValue = str_replace("{%comment%} solcoders_aliraza_start {%- endcomment -%}\n            {% capture specific_products %} {% if shop.metafields.solcoders.specific_products and  shop.metafields.solcoders.specific_products != 'null' %} {{ shop.metafields.solcoders.specific_products }} {% else %} \"\" {% endif %} {% endcapture %}\n            {% capture specific_collections %} {% if shop.metafields.solcoders.specific_collections and  shop.metafields.solcoders.specific_collections != 'null' %} {{ shop.metafields.solcoders.specific_collections }} {% else %} \"\" {% endif %} {% endcapture %}\n            {% capture group_specific_products %} {% if shop.metafields.solcoders.group_specific_products and  shop.metafields.solcoders.group_specific_products != 'null' %} {{ shop.metafields.solcoders.group_specific_products }} {% else %} \"\" {% endif %} {% endcapture %}\n            {% capture group_specific_collections %} {% if shop.metafields.solcoders.group_specific_collections and  shop.metafields.solcoders.group_specific_collections != 'null' %} {{ shop.metafields.solcoders.group_specific_collections }} {% else %} \"\" {% endif %} {% endcapture %}  \n            {% capture product_type %}\"{{ card_product.collections[0].id }}\"{% endcapture %}\n            {%- capture solcoders_price -%}\n              {% unless customer %}\n                {%- if specific_collections contains '\"\"' or specific_products contains '\"\"'  -%}\n                  {%- if shop.metafields.solcoders.hide_text == '' -%}\n                    {% render 'price', product: card_product, price_class: '' %}\n                  {%- else -%}\n                    {% assign show_quick_add = false %}             \n                    {{ shop.metafields.solcoders.hide_text }}\n                  {%- endif -%}\n                {%- else -%}\n                  {%- if specific_products contains card_product.id or specific_collections contains product_type or group_specific_products contains card_product.id or group_specific_collections contains product_type -%}\n                    {% assign show_quick_add = false %}             \n                    {{ shop.metafields.solcoders.hide_text }}\n                  {%- else -%}\n                    {% render 'price', product: card_product, price_class: '' %}\n                  {%- endif -%}\n                {%- endif -%}\n              {% endunless %}\n              {% if customer %}\n                {% capture sltvp_customer_tags %}{% if shop.metafields.solcoders.group_customer_tags and  shop.metafields.solcoders.group_customer_tags != 'null' %}{{ shop.metafields.solcoders.group_customer_tags }}{% else %}null{% endif %}{% endcapture %}\n                {% assign sltvp_customer_tags = sltvp_customer_tags | split: \",\" %}\n                {% capture sltvp_customers %}{% if shop.metafields.solcoders.group_customers and  shop.metafields.solcoders.group_customers != 'null' %}{{ shop.metafields.solcoders.group_customers }}{% else %}null{% endif %}{% endcapture %}\n                {% capture logged_in_customers %}\n                  {%- if group_specific_products contains card_product.id or group_specific_collections contains product_type -%}\n                  {% if sltvp_customer_tags contains 'null' %}\n                    {% if sltvp_customers contains 'null' %}\n                        {% render 'price', product: card_product, price_class: '' %}\n                    {% else %}\n                      {% if  sltvp_customers contains customer.email %}\n                        {% render 'price', product: card_product, price_class: '' %}\n                      {% else %}\n                        {% assign show_quick_add = false %}\n                      {% endif %}\n                    {% endif %}  \n                  {% else %}\n                    {% assign tagTrue = false %}\n                    {% for sltvp_tag in sltvp_customer_tags %}\n                      {% for customer_tag in customer.tags %}\n                        {% if customer_tag == sltvp_tag %}\n                          {% assign tagTrue = true %}\n                          {% break %}\n                        {% endif %}\n                      {% endfor %}\n                    {% endfor %}\n                    {% if tagTrue %}\n                      {% render 'price', product: card_product, price_class: '' %}\n                    {% else %}\n                      {% assign show_quick_add = false %}\n                      {{ shop.metafields.solcoders.group_text }}\n                    {% endif %}\n                  {% endif %}\n                  {% else %}\n                    {% render 'price', product: card_product, price_class: '' %}\n                  {% endif %}\n                {%- endcapture -%}\n                {{ logged_in_customers }}\n              {% endif %}\n            {%- endcapture -%}\n            {{ solcoders_price }}\n            {%comment%} solcoders_aliraza_end {%- endcomment -%}\n","{% render 'price', product: card_product, price_class: '' %}",  $updatedValue);
                    }
                }
                if ($filename == 'layout/theme.liquid') {
                    if (str_contains($updatedValue, "{%comment%} solcoders_aliraza_start {%- endcomment -%}")) {
                        $updatedValue = str_replace("{%comment%} solcoders_aliraza_start {%- endcomment -%}\n        {% if customer %}\n            {% capture sltvp_page_customer_tags %}{% if shop.metafields.solcoders.page_group_customer_tags and  shop.metafields.solcoders.page_group_customer_tags != 'null' %}{{ shop.metafields.solcoders.page_group_customer_tags }}{% else %}null{% endif %}{% endcapture %}\n            {% assign sltvp_page_customer_tags = sltvp_page_customer_tags | split: \",\" %}\n            {% capture sltvp_page_customers %}{% if shop.metafields.solcoders.page_group_customers and  shop.metafields.solcoders.page_group_customers != 'null' %}{{ shop.metafields.solcoders.page_group_customers }}{% else %}null{% endif %}{% endcapture %}\n            {% assign allSpecificRedirectPages = shop.metafields.solcoders.specific_hide_pages_url | split: \",\"    %}\n            {% assign logged_condition = false %}\n            {% capture logged_in_customers %}\n                {% if  allSpecificRedirectPages contains canonical_url  %}\n                    {% if sltvp_page_customer_tags contains 'null' %}\n                        {% if sltvp_page_customers contains 'null' %}\n                            {% assign logged_condition = true %}\n                        {% else %}\n                            {% if  sltvp_page_customers contains customer.email %}\n                                {% assign logged_condition = false %}\n                            {% else %}\n                                {% assign logged_condition = true %}\n                            {% endif %}\n                        {% endif %}  \n                    {% else %}\n                        {% assign tagTrue = false %}\n                        {% for sltvp_tag in sltvp_page_customer_tags %}\n                            {% for customer_tag in customer.tags %}\n                                {% if customer_tag == sltvp_tag %}\n                                    {% assign tagTrue = true %}\n                                    {% break %}\n                                {% endif %}\n                            {% endfor %}\n                        {% endfor %}\n                        {% if tagTrue %}\n                            {% assign logged_condition = false %}\n                        {% else %}\n                            {% assign logged_condition = true %}\n                        {% endif %}\n                    {% endif %}\n                {% endif %}\n            {%- endcapture -%}\n            {% if  allSpecificRedirectPages contains canonical_url  %}\n                {% if logged_condition   %}\n                    {% assign logged_note = shop.metafields.solcoders.logged_note %}\n                    {% assign logged_redirect_url = shop.metafields.solcoders.logged_redirect_url %}\n                    <script>          var logged_note = \"{{ logged_note }}\";            alert(logged_note);            window.location.href = \"{{ logged_redirect_url }}\";        </script>\n                {% endif %}\n            {% endif %}\n        {% else %}\n            {% assign allRedirectPages = shop.metafields.solcoders.hide_pages_url | split: \",\"    %}\n            {% assign allSpecificRedirectPages = shop.metafields.solcoders.specific_hide_pages_url | split: \",\"    %}\n            {% if  allSpecificRedirectPages contains canonical_url  %}\n              {% assign logged_note = shop.metafields.solcoders.logged_note %}\n              {% assign logged_redirect_url = shop.metafields.solcoders.logged_redirect_url %}\n              <script>          var logged_note = \"{{ logged_note }}\";            alert(logged_note);            window.location.href = \"{{ logged_redirect_url }}\";        </script>\n            {% endif %}\n            {% if  allRedirectPages contains canonical_url  %}\n                {% assign non_logged_note = shop.metafields.solcoders.non_logged_note %}\n                {% assign redirect_url = shop.metafields.solcoders.redirect_url %}\n                <script>          var non_logged_note = \"{{ non_logged_note }}\";            alert(non_logged_note);            window.location.href = \"{{ redirect_url }}\";        </script>\n            {% endif %}\n        {% endif %}\n        {%comment%} solcoders_aliraza_start {%- endcomment -%}\n    </head>\n","</head>",  $updatedValue);
                        $api_endpoint = '/admin/api/' . env('SHOPIFY_API_VERSION', '2022-07') . '/themes/' . $theme_id . '/assets.json';
                        $asset = ['asset' => [
                            "key" => $filename,
                            "value" => $updatedValue
                        ]];

                        $update = ShopController::shopify_rest_call($store->shopify_token, $shop, $api_endpoint, $asset, 'PUT');
                        $update = json_decode($update['response']);
                    }

                }

                $api_endpoint = '/admin/api/' . env('SHOPIFY_API_VERSION', '2022-07') . '/themes/' . $theme_id . '/assets.json';
                $asset = ['asset' => [
                    "key" => $filename,
                    "value" => $updatedValue
                ]];
                $update = ShopController::shopify_rest_call($store->shopify_token, $shop, $api_endpoint, $asset, 'PUT');
                $update = json_decode($update['response']);
            }
            else {
                $update = false;
            }
            if ($update) {
                $update = true;
            } else {
                $update = false;
            }
        } else {
            $update = false;
        }
        return $update;
    }
    public static function getAsset($shop, $theme_id, $filename)
    {
        $store = Store::where('shop_url', $shop)->first();

        if (!empty($store->shopify_token)) {
            $api_endpoint = '/admin/api/' . env('SHOPIFY_API_VERSION', '2022-07') . '/themes/' . $theme_id . '/assets.json?asset[key]=' . $filename . '&theme_id=' . $theme_id;


            $assets = ShopController::shopify_rest_call($store->shopify_token, $shop, $api_endpoint, array(), 'GET');
            $assets = json_decode($assets['response']);
            if(isset($assets)){
                if(!empty($assets->asset)){
                    $assets = $assets->asset;
                }
                else{
                    $assets = "";
                }
            }
            else{
                $assets = "";
            }
            return $assets;
        } else {
            return response(['Store not Found']);
        }
    }
    public function create(Request $request)
    {
        $shop = $request->shop;
        $store = Store::where('shop_url', $request->shop)->first();
        if ($store){
            return view('shopify.create', compact('store','shop'));
        }
    }
    public function installThemeWithEmbedded(Request $request)
    {
        $shop = $request->shop;
        $store = Store::where('shop_url', $request->shop)->first();
        if (is_string($store->settings)) {
            $settings = json_decode($store->settings);
        } else {
            $settings = $store->settings;
        }
        $themes = AppController::getThemes($request->shop);
        foreach ($themes as  $theme) {
            if($theme->role == 'main'){
                $theme_id =  $theme->id;
            }
        }
        if($request->type == 'install'){

            AppController::detectEmbededApp($request->shop, true);

            $mainProductInstall = AppController::updateAssetData($request->shop, $theme_id, 'sections/main-product.liquid');
            $cardProductInstall = AppController::updateAssetData($request->shop, $theme_id, 'snippets/card-product.liquid');
            $cardProductInstall = AppController::updateAssetData($request->shop, $theme_id, 'snippets/product-grid-layout.liquid');
            $themeInstall = AppController::updateAssetData($request->shop, $theme_id, 'layout/theme.liquid');

            AppController::installOnAllFiles($shop,$theme_id);
            if (isset($settings->installed_themes) && $settings->installed_themes <> '') {
                if (str_contains($settings->installed_themes, $theme_id)) {
                } else {
                    $settings->installed_themes = $settings->installed_themes . $theme_id . ' , ';
                }
            } else {
                $settings->installed_themes =  $theme_id . ' , ';

            }
            $store->settings = (array)$settings;
            $store->update();
            return ['return'=>'Installed Successfully'];
        }
        else{
            AppController::detectEmbededApp($request->shop, false);
            $cardProductInstall = AppController::removeAssetData($request->shop, $theme_id, 'snippets/card-product.liquid');
            $productProductInstall = AppController::removeAssetData($request->shop, $theme_id, 'snippets/product-grid-layout.liquid');
            $mainProductInstall = AppController::removeAssetData($request->shop, $theme_id, 'sections/main-product.liquid');
            $themeInstall = AppController::removeAssetData($request->shop, $theme_id, 'layout/theme.liquid');
            AppController::uninstallOnAllFiles($shop,$theme_id);
            if ( $mainProductInstall && ($cardProductInstall || $productProductInstall)) {
                $settings = $store->settings;
                $replace = str_replace($theme_id . ' , ', '', $settings);
                $store->settings = $replace;
                $store->update();
                return ['return'=>'Uninstalled Successfully'];
            }
        }
    }
    public function installTheme(Request $request)
    {
        $shop = $request->shop;
        $store = Store::where('shop_url', $request->shop)->first();
        AppController::detectEmbededApp($request->shop, true);
        if (is_string($store->settings)) {
            $settings = json_decode($store->settings);
        } else {

            $settings = $store->settings;
        }
        if(isset($settings->div_view)){

            $api_endpoint = '/admin/api/' . env('SHOPIFY_API_VERSION', '2022-07') . '/metafields.json';
            $metaField = [
                "metafield" => ["namespace" => "solcoders", "key" => "hide_text", "value" => $settings->div_view, "type" => "multi_line_text_field"]
            ];
            $metaFieldCall = ShopController::shopify_rest_call($store->shopify_token, $request->shop, $api_endpoint, $metaField, 'POST');
        }
        $theme_id = $request->theme_id;
        $mainProductInstall = AppController::updateAssetData($request->shop, $request->theme_id, 'sections/main-product.liquid');
        $cardProductInstall = AppController::updateAssetData($request->shop, $request->theme_id, 'snippets/card-product.liquid');
        $productGridInstall = AppController::updateAssetData($request->shop, $request->theme_id, 'snippets/product-grid-layout.liquid');
        $themeInstall = AppController::updateAssetData($request->shop, $request->theme_id, 'layout/theme.liquid');
        AppController::installOnAllFiles($shop,$theme_id);
        if (isset($settings->installed_themes) && $settings->installed_themes <> '') {
            if (str_contains($settings->installed_themes, $request->theme_id)) {
            } else {
                $settings->installed_themes = $settings->installed_themes . $request->theme_id . ' , ';
            }
        } else {
            $settings->installed_themes =  $request->theme_id . ' , ';

        }
        $store->settings = (array)$settings;
        $store->update();

        // Store::where('shop_url', $request->shop)->update((array)$settings);
        return 'Installed Successfully';
    }
    public static function uninstallTheme(Request $request)
    {
        $shop = $request->shop;
        $store = Store::where('shop_url', $request->shop)->first();
        AppController::detectEmbededApp($request->shop, false);
        $cardProductInstall = AppController::removeAssetData($request->shop, $request->theme_id, 'snippets/card-product.liquid');
        $productProductInstall = AppController::removeAssetData($request->shop, $request->theme_id, 'snippets/product-grid-layout.liquid');
        $mainProductInstall = AppController::removeAssetData($request->shop, $request->theme_id, 'sections/main-product.liquid');
        $themeInstall = AppController::removeAssetData($request->shop, $request->theme_id, 'layout/theme.liquid');
        AppController::uninstallOnAllFiles($shop,$request->theme_id);
        if ($cardProductInstall ||  $mainProductInstall || $productProductInstall) {
            $settings = $store->settings;
            $replace = str_replace($request->theme_id . ' , ', '', $settings);
            $store->settings = $replace;
            $store->update();
            // Store::where('shop_url', $request->shop)->update($data);
            return 'Uninstalled Successfully';
        }
    }

    public static function uninstallAllThemes($shop)
    {
        $store = Store::where('shop_url', $shop)->first();
        $themes = AppController::getThemes($shop);
        $settings = $store->settings;
        if (is_string($store->settings)) {
            $settings = json_decode($store->settings);
        }

        $settings->installed_themes =  '';
        $store->update();

        $api_endpoint = '/admin/api/' . env('SHOPIFY_API_VERSION', '2022-07') . '/metafields.json';
        $allMetaFields = ShopController::shopify_rest_call($store->shopify_token, $shop, $api_endpoint, array(), 'GET');
        $allMetaFields = json_decode($allMetaFields['response'])->metafields;
        foreach($allMetaFields as $metaField){
            if($metaField->namespace == 'solcoders'){
                $api_endpoint = '/admin/api/' . env('SHOPIFY_API_VERSION', '2022-07') . '/metafields/'.$metaField->id.'.json';
                $allMetaFields = ShopController::shopify_rest_call($store->shopify_token, $shop, $api_endpoint, array(), 'DELETE');
            }
        }
        foreach($themes as $theme){

            $cardProductInstall = AppController::removeAssetData($shop, $theme->id, 'snippets/card-product.liquid');
            $mainProductInstall = AppController::removeAssetData($shop, $theme->id, 'sections/main-product.liquid');
        }
    }
    public static function getProducts($shop)
    {
        $store = Store::where('shop_url', $shop)->first();

        if (!empty($store->shopify_token)) {
            $api_endpoint = '/admin/api/' . env('SHOPIFY_API_VERSION', '2022-07') . '/products.json';
            $products = ShopController::shopify_rest_call($store->shopify_token, $shop, $api_endpoint, array(), 'GET');
            $products = json_decode($products['response']);
            if(isset($products->products)){

                $products = $products->products;
            }
            else{
                $products = [];
            }
            if (empty($products)) {
                $products = [];
            }
            return $products;
        } else {
            return response(['Store not Found']);
        }
    }
    public static function currentCountry(Request $request)
    {
        $shop = $request->shop;
        $countryCode = $request->current_country;
        $store = Store::where('shop_url', $shop)->first();
        $settings = $store->settings;
        if (is_string($store->settings)) {
            $settings = json_decode($store->settings);
        }
        $settings->current_country = $countryCode;
        $store->settings = json_encode($settings);
        $store->update();
        return true;
    }
    public static function get_reports($shop)
    {
        $store = Store::where('shop_url', $shop)->first();

        if (!empty($store->shopify_token)) {
            $api_endpoint = '/admin/api/' . env('SHOPIFY_API_VERSION', '2022-07') . '/reports.json';
            $reports = ShopController::shopify_rest_call($store->shopify_token, $shop, $api_endpoint, array(), 'GET');
            $reports = json_decode($reports['response']);
            $reports = $reports->reports;
            return $reports;
        } else {
            return response(['Store not Found']);
        }
    }
    public static function createReport($shop, $name)
    {

        $report = 'SHOW total_sales BY order_id FROM sales SINCE -1m UNTIL today ORDER BY total_sales';
        $store = Store::where('shop_url', $shop)->first();
        $api_endpoint = '/admin/api/' . env('SHOPIFY_API_VERSION', '2022-07') . '/reports.json';
        $report = [
            "report" => ["name" => $name, "shopify_ql" => $report]
        ];
        $reportCall = ShopController::shopify_rest_call($store->shopify_token, $shop, $api_endpoint, $report, 'POST');
        return $reportCall;
    }
    public static function getAllCollections($shop)
    {
        if (!str_contains($shop, '.myshopify.com')) {
           $shop = AppController::getShopifyDomain($shop);
        }
        $store = Store::where('shop_url', $shop)->first();
        $allCollections = array();


        if( !empty( $store->shopify_token ) ){
            if(is_string($store->settings)){
                $settings = json_decode($store->settings);
            }
            else{
                $settings = $store->settings;
            }
            $api_endpoint = '/admin/api/'.env('SHOPIFY_API_VERSION','2023-01').'/smart_collections.json';
            $collections = ShopController::shopify_rest_call($store->shopify_token, $shop, $api_endpoint , array() ,'GET');
            $collections = json_decode($collections['response']);
            if(isset($collections->custom_collections)){

                $collections = $collections->smart_collections;
            }
            else{
                $collections = [];
            }
            $smart_collections = array();
            if( !empty( $collections ) ){
                foreach($collections as $collection ){
                    $allCollections[$collection->id] = $collection->title;
                }
            }
            $api_endpoint = '/admin/api/'.env('SHOPIFY_API_VERSION','2023-01').'/custom_collections.json';
            $collections = ShopController::shopify_rest_call($store->shopify_token, $shop, $api_endpoint , array() ,'GET');
            $collections = json_decode($collections['response']);
            if(isset($collections->custom_collections)){

                $collections = $collections->custom_collections;
            }
            else{
                $collections = [];
            }
            $custom_collections = array();
            if( !empty( $collections ) ){
                foreach($collections as $collection ){
                    $allCollections[$collection->id] = $collection->title;
                }
            }
        }
        return  $allCollections;
    }
}
