<?php

namespace App\Http\Controllers;

use App\Models\Reports;
use App\Models\Mall;
use Illuminate\Http\Request;

class ReportController extends Controller
{

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    // public function index(Request $request)
    // {

    //     if(isset($request->type) && !empty($request->type) && $request->type != 'all'){
    //         $reports = Reports::where('report_type',$request->type)->get();
    //     }
    //     else{
    //         $reports = Reports::latest()->get();
    //     }

    //     return view('admin.reports.index', compact('reports'));
    // }


    public function index(Request $request)
    {
        if ($request->ajax()) {
            $draw = intval($request->input('draw'));
            $start = intval($request->input('start'));
            $length = intval($request->input('length'));

            $query = Reports::query();

            if ($request['search']['value']) {

                $query = Reports::where('shop', 'like', '%' . $request['search']['value'] . '%')->orWhere('report_type', 'like', '%' . $request['search']['value'] . '%')->orWhere('filename', 'like', '%' . $request['search']['value'] . '%');
            } else {

                $query = Reports::query();
            }
            if ($request->filled('type') && $request->type != 'all') {
                $query = $query->where('report_type', $request->type);
            } else {
                $query = $query->latest();
            }

            $total = $query->count();

            $reports = $query->offset($start)->limit($length)->get();

            $data = [];
            foreach ($reports as $report) {
                // Fetch location name and reports type
                $shop = $report->shop;
                $store = \App\Models\Store::where('shop_url', $shop)->first();

                $api_endpoint = '/admin/api/' . env('SHOPIFY_API_VERSION', '2023-04') . '/locations/' . $report->location . '.json';
                $location = \App\Http\Controllers\ShopController::shopify_rest_call($store->shopify_token, $shop, $api_endpoint, [], 'GET');
                $response = json_decode($location['response']);
                $locationName = isset($response->location) ? $response->location->name : $report->location;

                $reportsTypes = ['single_day_only' => 'Single Report', 'schedule' => 'Schedule', 'schedule_cron' => 'Schedule Report', 'date_range' => 'Bulk Report'];
                $reportType = $reportsTypes[$report->report_type];
                $reportDate = $report->report_date;

                $mall = Mall::find($report->mall_id);
                // Get the filenames
                $filenames = [];
                foreach (json_decode($report->filename) as $file) {
                    $filenames[] = ['name' => $file, 'url' => env('APP_URL').'reports/'.$file];
                }
                $allFiles = $report->filename;
                // if(isset($allFiles) && $allFiles == '[""]' ){
                //     $allFiles = "[]";
                // }
                $data[] = [
                    'id' => $report->id,
                    'mall' => $mall->title,
                    'shop' => $report->shop,
                    'location' => $locationName,
                    'report_type' => $reportType,
                    'report_date' => $reportDate,
                    'filenames' => $filenames,
                    'action' => [
                        'download_url' => '/download/'.$report->filename,
                        'delete_url' => route('reports.destroy', $report->id)
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
            $reports = Reports::latest()->paginate(10);
            return view('admin.reports.index', compact('reports'));
        }
    }


    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Models\Report  $report
    * @return \Illuminate\Http\Response
    */
    public function destroy(Request $request,$id)
    {
        $report = Reports::find($id);
        // dd($report);
        $report->delete();
        return redirect()->route('reports.index')->with('success','Report has been deleted successfully');
    }
    /**
    * Display the specified resource.
    *
    * @param  \App\report  $report
    * @return \Illuminate\Http\Response
    */
    public function show(Reports $report)
    {
        return view('admin.reports.show',compact('report'));
    }

}
