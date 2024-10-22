<?php

namespace App\Http\Controllers;

use App\Models\Variable;
use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\Subscription;
use App\Models\Mall;
use App\Models\Plans;
use App\Models\Location;
use App\Models\Reports;

class StoreController extends Controller
{

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {


        if ($request->ajax()) {
            $draw = intval($request->input('draw'));
            $start = intval($request->input('start'));
            $length = intval($request->input('length'));

            if ($request['search']['value']) {

                $query = Store::where('shop_url', 'like', '%' . $request['search']['value'] . '%');
            } else {

                $query = Store::query();
            }
        // if(isset($request->status) && !empty($request->status) && $request->status != 'all'){
        //     if($request->status == 'installed')
        //         $stores = Store::whereNotNull('shopify_token')->get();
        //     elseif($request->status == 'deleted')
        //         $stores = Store::whereNull('shopify_token')->orWhere('shopify_token', '')->get();
            if ($request->filled('status') && $request->status != 'all') {
                if($request->status == 'installed')
                    $query = $query->whereNotNull('shopify_token');
                else
                    $query = $query->whereNull('shopify_token')->orWhere('shopify_token', '');
            } else {
                $query = $query->latest();
            }

            $total = $query->count();

            $stores = $query->offset($start)->limit($length)->get();

            $data = [];
            foreach ($stores as $store) {
                $plan = Plans::find($store->plan_id);

                $data[] = [
                    'id' => $store->id,
                    'name' => $store->shop_url,
                    // 'plan' => $plan->name,
                    'status' => ($store->shopify_token != '' && $store->shopify_token != NULL) ? 'active' : 'disabled',
                ];
            }
            return response()->json([
                'draw' => $draw,
                'recordsTotal' => $total,
                'recordsFiltered' => $total,
                'data' => $data,
            ]);
        } else {
            $stores = Store::latest()->paginate(10);
            return view('admin.stores.index', compact('stores'));
        }






        if(isset($request->status) && !empty($request->status) && $request->status != 'all'){
            if($request->status == 'installed')
                $stores = Store::whereNotNull('shopify_token')->get();
            elseif($request->status == 'deleted')
                $stores = Store::whereNull('shopify_token')->orWhere('shopify_token', '')->get();
        }
        else{

            $stores = Store::all();
        }
        return view('admin.stores.index', compact('stores'));
    }
    public function locationStore(Request $request)
    {
        $store = Store::where('shop_url',$request->shop)->first();

        $ftp_details= isset($request->ftp_host) ?  json_encode(["ftp_host" => $request->ftp_host,"ftp_port" => $request->ftp_port,"ftp_user" => $request->ftp_user, "ftp_pass" => $request->ftp_pass, "ftp_path" => $request->ftp_path]) : json_encode(["base_url" => $request->base_url,"api_url" => $request->api_url,"token_url" => $request->token_url, "username" => $request->username, "password" => $request->password]);
        $machine_id = isset($request->machine_id) ? $request->machine_id : '';

        $fields = json_encode(['machine_id'=>$machine_id]);

        $location = Location::where('location',$request->location)->first();
        if(!$location){
            $location = new Location;
        }
        $location->location = isset($request->location) ? $request->location : '';
        $location->mall_id = isset($request->mall_id) ? $request->mall_id : '';
        $location->fields = isset($fields) ? $fields : '';
        $location->cash = isset($request->cash) ? $request->cash : '';
        $location->tng = isset($request->tng) ? $request->tng : '';
        $location->visa = isset($request->visa) ? $request->visa : '';
        $location->master_card = isset($request->mastercard) ? $request->mastercard : '';
        $location->amex = isset($request->amex) ? $request->amex : '';
        $location->vouchers = isset($request->voucher) ? $request->voucher : '';
        $location->others = isset($request->others) ? $request->others : '';
        $location->shop = isset($request->shop) ? $request->shop : '';
        $location->ftp_details = isset($ftp_details) ? $ftp_details : '';
        $location->save();
        return redirect('/admin/stores/'.$store->id.'/edit');
    }
    public function edit(Store $store)
    {
        $locations = $this->getLocations($store->shop_url);
        return view('admin.stores.show', compact('store', 'locations'));
    }
    public function getLocationByMall(Request $request)
    {
        $mall_id =  $request->mall_id;
        $location_id =  $request->location_id;
        $getLocation = Location::where('location',$location_id)->where('mall_id',$mall_id)->first();
        return response()->json($getLocation);
    }
    public function locationShow($shop,$id)
    {
        $store = Store::where('shop_url', $shop)->first();
        $malls = Mall::where('country', 'MY')->get();
        $location = $this->getSingleLocation($shop,$id);
        $locationModel = Location::where('location',$location->id)->first();
        $reportLocation = Reports::where('location',$location->id)->orderBy('id','desc')->first();

        if($locationModel){
            $mall = Mall::find($locationModel->mall_id);
            $locationModel->template_id = $mall->template_id;
            $locationModel->mall_name = $mall->title;
            $locationData = $locationModel;
        }else{
            if(!empty($reportLocation)){
                $variables = Variable::where('mall_id',$reportLocation->mall_id)->first();
                $reportLocation->variables = $variables;

                $mall = Mall::find($reportLocation->mall_id);
                $reportLocation->template_id = $mall->template_id;
                $reportLocation->mall_name = $mall->title;
            }
            $locationData = $reportLocation;
        }
        return view('admin.stores.location-show', compact('store','malls' , 'location','locationData'));
    }
    public function getSingleLocation($shop,$id)
    {
        $store = Store::where('shop_url', $shop)->first();
        $api_endpoint = '/admin/api/' . env('SHOPIFY_API_VERSION', '2023-04') . '/locations/'.$id.'.json';
        $location = ShopController::shopify_rest_call($store->shopify_token, $shop, $api_endpoint, array(), 'GET');
        $response = json_decode($location['response']);
        if (isset($response->location)) {
            $location = $response->location;
            return $location;
        }
        return [];
    }

    public function getLocationCheck(Request $request)
    {
        $subscription = Subscription::where("shop", $request->shop)
        ->where("location", $request->location_id)
        ->first();

        if($subscription->subscribe ?? false){

            if($subscription->subscribe == "premium" && $subscription->status == 1){
            return response()->json([
                'premium' => true,
            ]);
        }}

        return response()->json([
            'premium' => false,
        ]);
    }

    public function getLocations($shop)
    {

        $store = Store::where('shop_url', $shop)->first();
        $api_endpoint = '/admin/api/' . env('SHOPIFY_API_VERSION', '2023-04') . '/locations.json';
        $orders = ShopController::shopify_rest_call($store->shopify_token, $shop, $api_endpoint, array(), 'GET');
        $response = json_decode($orders['response']);
        if (isset($response->locations)) {
            $locations = $response->locations;
            return $locations;
        }
        return [];
    }
}
