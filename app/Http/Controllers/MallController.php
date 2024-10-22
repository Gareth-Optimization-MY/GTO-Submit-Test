<?php

namespace App\Http\Controllers;

use App\Models\Mall;
use App\Models\Reports;
use App\Models\Variable;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MallController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    // {
    //     $malls = Mall::join('templates', 'templates.id', '=', 'malls.template_id')->select('malls.*', 'templates.name')->orderBy('malls.title', 'asc')->get();
    //     return view('admin.malls.index', compact('malls'));
    // }


    public function index(Request $request)
    {
        if ($request->ajax()) {
            $draw = intval($request->input('draw'));
            $start = intval($request->input('start'));
            $length = intval($request->input('length'));
            if($request['search']['value']){

                $query = Mall::where('malls.title', 'like', '%' . $request['search']['value'] . '%')
                    ->join('templates', 'templates.id', '=', 'malls.template_id')
                    ->select('malls.*', 'templates.name as template_name');

            }
            else{

                $query = DB::table('malls')
                    ->join('templates', 'templates.id', '=', 'malls.template_id')
                    ->select('malls.*', 'templates.name as template_name');
            }

            $total = $query->count();

            $malls = $query->offset($start)->limit($length)->get();

            $data = [];
            foreach ($malls as $mall) {
                $data[] = [
                    'id' => $mall->id,
                    'title' => $mall->title,
                    'country' => $mall->country, // You may want to map country code to full name here
                    'template_name' => $mall->template_name,
                    'action' => [
                        'edit_url' => route('malls.edit', $mall->id),
                        'delete_url' => route('malls.destroy', $mall->id)
                    ]
                ];
            }

            return response()->json([
                'draw' => $draw,
                'recordsTotal' => $total,
                'recordsFiltered' => $total,
                'data' => $data,
            ]);
        } else {
            $malls = Mall::latest()->paginate(10);
            return view('admin.malls.index', compact('malls'));
        }
    }

    public function getMalls(Request $request)
    {
        if ($request->country_id) {
            $malls = Mall::join('templates', 'templates.id', '=', 'malls.template_id')->select('malls.*', 'templates.name')->where('malls.country', $request->country_id)->orderBy('malls.title', 'asc')->get();
        } else {

            $malls = Mall::join('templates', 'templates.id', '=', 'malls.template_id')->select('malls.*', 'templates.name')->orderBy('malls.title', 'asc')->get();
        }
        foreach ($malls as $key => $mall) {
            $mallhtml[$key]['label'] = $mall->title;
            $mallhtml[$key]['value'] = $mall->id;
        }
        return $mallhtml;
    }

    public function getMall($id)
    {
       $mall = Mall::find($id);
        return $mall;
    }
    public function getVariable(Request $request)
    {
        $variables = Variable::where('shop', $request->shop)->where('mall_id', $request->mallId)->first();
        $mall = Mall::find($request->mallId);
        $data = $variables;
        $data['is_template'] = $mall->template_id;
        $data['starting_number'] = $mall->starting_number;
        return $data;
    }
    public function getTemplateByMallId(Request $request)
    {
        $mall = Mall::find($request->id);
        $template = Template::find($mall->template_id);
        return $template;
    }
    public function getCountry(Request $request)
    {
        $countries = [
            ["label" => "Malaysia", "value" =>  "MY"]
            // ["label" => "Singapore", "value" =>  "SG"]
            // ["label" => "Pakistan", "value" =>  "PK"],
            // ["label" => "India", "value" =>  "IN"],
            // ["label" => "United States", "value" =>  "US"]
        ];
        // $countries= Mall::join('templates', 'templates.id', '=', 'malls.template_id')->select('malls.*','templates.name')->orderBy('malls.id','desc')->get();
        // foreach ($countries as $key => $country) {
        //     $countryhtml[$key]['label'] = $country->country;
        //     $countryhtml[$key]['value'] = $country->id;
        // }
        return $countries;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $templates = Template::All();
        return view('admin.malls.create', compact('templates'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'country' => 'required',
            'template_id' => 'required',
        ]);

        Mall::create($request->post());

        return redirect()->route('malls.index')->with('success', 'Mall has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\mall  $mall
     * @return \Illuminate\Http\Response
     */
    public function show(Mall $mall)
    {
        $templates = Template::All();
        return view('admin.malls.show', compact('mall', 'templates'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Mall  $mall
     * @return \Illuminate\Http\Response
     */
    public function edit(Mall $mall)
    {
        // $mallModel = $mall;
        $mallUsed = Reports::where('mall_id', $mall->id)->get();
        $mallLocations = [];
        $store = [];
        foreach ($mallUsed as $singleMall) {
            $store = \App\Models\Store::where('shop_url', $singleMall->shop)->first();

            $api_endpoint = '/admin/api/' . env('SHOPIFY_API_VERSION', '2023-04') . '/locations/' . $singleMall->location . '.json';
            $location = \App\Http\Controllers\ShopController::shopify_rest_call($store->shopify_token, $singleMall->shop, $api_endpoint, array(), 'GET');
            $response = json_decode($location['response']);
            if (isset($response->location)) {
                $location = $response->location;
                $mallLocations[$location->id] = $location->name;
            } else {
                $mallLocations[$singleMall->location] = $singleMall->location;
            }
        }

        $templates = Template::All();
        return view('admin.malls.edit', compact('mall', 'templates', 'store', 'mallLocations'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\mall  $mall
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Mall $mall)
    {
        $request->validate([
            'title' => 'required',
            'country' => 'required',
            'template_id' => 'required',
        ]);

        $mall->fill($request->post())->save();

        return redirect()->route('malls.index')->with('success', 'Mall Has Been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Mall  $mall
     * @return \Illuminate\Http\Response
     */
    public function destroy(Mall $mall)
    {
        $mall->delete();
        return redirect()->route('malls.index')->with('success', 'Mall has been deleted successfully');
    }
}
