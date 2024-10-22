<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\ShopController;
use App\Models\Store;
use App\Models\Variable;
use App\Models\Mall;
use App\Models\Location;
use App\Models\Subscription;
use App\Models\Reports as ReportModel;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Storage;
use phpseclib\Net\SFTP;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class OrderReportController extends Controller
{
    public function index()
    {
        $current_time = date('y-m-d H:i:s');
        // \Log::info("Other ". $current_time);
        date_default_timezone_set("Asia/Kuala_Lumpur");
        $current_time = date('y-m-d H:i:s');
        // \Log::info("Asia/Kuala_Lumpur " . $current_time);

        // dd(timezone_identifiers_list());
        return view('admin.order-reports');
    }

    public function generateToken($apiInfo)
    {
        $apiInfo = json_decode($apiInfo, true);
        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => $apiInfo['base_url'],
        ]);

        $array = explode($apiInfo['base_url'], $apiInfo['token_url']);
        if (count($array) > 1) {
            $tokenUrl = $array[1];
        } else {
            $tokenUrl = $array[0];
        }
        $body = [
            'grant_type' => 'password',
            'username' => $apiInfo['username'],
            'password' => $apiInfo['password'],
        ];

        try {
            $response = $client->post($tokenUrl, [
                'form_params' => $body,
            ]);

            $responseBody = json_decode($response->getBody(), true);
            if ($response->getStatusCode() === 200) {
                return $responseBody['access_token'] ?? false;
            }
        } catch (RequestException $e) {
            // handle error
            echo $e->getMessage();
        }

        return false;
    }

    public function sendRequest($apiData, $accessToken, $apiInfo, $filename)
    {
        $apiInfo = json_decode($apiInfo, true);
        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => $apiInfo['base_url'],
        ]);

        $array = explode($apiInfo['base_url'], $apiInfo['api_url']);
        if (count($array) > 1) {
            $apiUrl = $array[1];
        } else {
            $apiUrl = $array[0];
        }
        try {
            $response = $client->post($apiUrl, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $accessToken,
                ],
                'body' => $apiData,
            ]);

            $responseBody = json_decode($response->getBody(), true);

            if (!empty($filename)) {
                $this->print_report($filename, $apiData, '');
            }
            // \Log::info($response->getBody());
            // $this->print_report($filename, $response->getBody(), '');
            $responseBody = json_decode($response->getBody(), true);
            if ($response->getStatusCode() === 200) {
                return $responseBody;
            }
        } catch (RequestException $e) {
            // handle error
            echo $e->getMessage();
        }

        return false;
    }
    public static function getOrders($shop, $date)
    {
        $store = Store::where('shop_url', $shop)->first();

        if (!empty($store->shopify_token)) {
            $datemin = $date . "T00:00:00";
            $datemax = $date . "T23:59:59";
            $datemin = new \DateTime($datemin);
            $datemax = new \DateTime($datemax);
            $formattedDatemin = $datemin->format('Y-m-d\TH:i:s');
            $formattedDatemax = $datemax->format('Y-m-d\TH:i:s');
            $api_endpoint = '/admin/api/' . env('SHOPIFY_API_VERSION', '2023-04') . '/orders.json?limit=250&status=any&created_at_min=' . $formattedDatemin . '&created_at_max=' . $formattedDatemax . '';
            $orders = ShopController::shopify_rest_call($store->shopify_token, $shop, $api_endpoint, array(), 'GET');
            $orders = json_decode($orders['response']);
            $orders = $orders->orders;
            return $orders;
        } else {
            return response(['Store not Found']);
        }
    }

    public function editReport($id)
    {

        $report = ReportModel::find($id);

        return response()->json($report);
    }
    public function  getSubscriptionsForPlan(Request $request)
    {

        $shop = $request->shop;

        $subscription = Subscription::where('shop', $shop)->get();

        return $subscription;
    }

    public function updateSubscription(Request $request)
    {

        $shop = $request->shop;
        if ($request->new_premium == 0) {
            foreach ($request->subscriptions as $subscription) {
                Subscription::where('shop', $shop)
                    ->where("location", $subscription["location"])
                    ->update(["subscribe" => $subscription["subscribe"][0] ?? "free"]);
            }
        } else {

            foreach ($request->subscriptions as $subscription) {
                Subscription::where('shop', $shop)
                    ->where("location", $subscription["location"])
                    ->update(["subscribe" => $subscription["subscribe"][0] ?? "free"]);
            }
        }
        $subscription = Subscription::where('shop', $shop)->get();
        return $subscription;
    }

    public function getLocationsForPlan(Request $request)
    {
        $shop = $request->shop;
        $store = Store::where('shop_url', $shop)->first();
        $allLocations = [];
        $api_endpoint = '/admin/api/' . env('SHOPIFY_API_VERSION', '2023-04') . '/locations.json';
        $locations = ShopController::shopify_rest_call($store->shopify_token, $shop, $api_endpoint, array(), 'GET');
        $response = json_decode($locations['response']);
        if (isset($response->locations)) {
            $allLocations = $response->locations;

            // foreach ($locations as $key => $location) {
            //     $allLocations[$key]['label'] = $location->name;
            //     $allLocations[$key]['value'] = $location->id;
            // }
        }

        return $allLocations;
    }
    public function getLocations(Request $request)
    {

        $shop = $request->shop;
        $store = Store::where('shop_url', $shop)->first();
        $all_locations_use = ReportModel::where('report_type', '!=', "single_day_only")->where('shop', $shop)->pluck('location')->toArray();
        $all_locations_use = array_unique($all_locations_use);

        $location_allowed = $store->location_allowed;

        $locationHTML = [];
        $api_endpoint = '/admin/api/' . env('SHOPIFY_API_VERSION', '2023-04') . '/locations.json';
        $orders = ShopController::shopify_rest_call($store->shopify_token, $shop, $api_endpoint, array(), 'GET');
        $response = json_decode($orders['response']);
        $disabledLocations = [];
        if (isset($response->locations)) {
            $locations = $response->locations;

            foreach ($locations as $key => $location) {
                if ($all_locations_use < $location_allowed) {

                    $locationHTML[$key]['label'] = $location->name;
                    $locationHTML[$key]['value'] = $location->id;
                } else {
                    $locationHTML[$key]['label'] = $location->name;
                    $locationHTML[$key]['value'] = $location->id;
                    if (!in_array($location->id, $all_locations_use))
                        $disabledLocations[] = $location->id;
                }
            }
        }
        return [$locationHTML, $disabledLocations];
    }
    public function getAllReports(Request $request)
    {
        // setting default pagination limit
        $limit = $request->input('limit', 10);

        if ($request->report_type == 1) {
            $reports = ReportModel::where('report_type', 'schedule')
                ->orderBy('reports.id', 'desc')
                ->paginate($limit);
        } else {
            $reports = ReportModel::where('report_type', "!=", 'schedule')
                ->orderBy('reports.id', 'desc')
                ->paginate($limit);
        }


        $shop = $request->shop;
        $store = Store::where('shop_url', $shop)->first();



        if (isset($reports) && !empty($reports) && count($reports) > 0) {

            foreach ($reports as $key => $report) {
                $mall = Mall::find($report->mall_id);

                $api_endpoint = '/admin/api/' . env('SHOPIFY_API_VERSION', '2023-04') . '/locations/' . $report->location . '.json';
                $location = ShopController::shopify_rest_call($store->shopify_token, $shop, $api_endpoint, array(), 'GET');
                $response = json_decode($location['response']);
                if (isset($response->location)) {
                    $location = $response->location;
                    $locationName = $location->name;
                } else {
                    $locationName = $report->location;
                }
                $reportsTypes = ['single_day_only' => 'Single Report', 'schedule' => 'Schedule', 'schedule_cron' => 'Scheduled report', 'date_range' => 'Bulk Report'];
                $reporthtml[$key]['id'] = $report->id;
                $reporthtml[$key]['name'] = '';
                $reporthtml[$key]['mall_name'] = $mall['title'];
                $reporthtml[$key]['location'] = $locationName;
                $reporthtml[$key]['report_type'] = $reportsTypes[$report->report_type];
                $reporthtml[$key]['report_date'] = $report->report_date;
                $reporthtml[$key]['template_id'] = $mall['template_id'];
                if ($report->filename) {
                    if ($mall['template_id'] != 5) {
                        $reporthtml[$key]['filenames'] = $report->filename;
                    } else {
                        if ($report->filename != '[""]') {
                            $reporthtml[$key]['filenames'] = $report->filename;
                        } else {
                            $reporthtml[$key]['filenames'] = "[]";
                        }
                    }
                } else {
                    $reporthtml[$key]['filenames'] = [];
                }
            }
        } else {
            $reporthtml = '';
        }
        return response()->json([
            'current_page' => $reports->currentPage(),
            'last_page' => $reports->lastPage(),
            'data' => $reporthtml
        ]);
    }
    public function deleteReport(Request $request)
    {
        if (!$request->id) {
            die('Whoop\'s Some thing went wrong');
        }
        $report = ReportModel::find($request->id);

        $filename = '/reports/' . $report->filename;
        File::delete(public_path($filename));

        $report->delete();
        return "success";
    }
    public function getReports(Request $request)
    {
        $date = $request->input('date_picker');
        $location_id = $request->input('location_id');
        $formattedDate = new \DateTime($date);
        $formattedDate = $formattedDate->format('Y-m-d');
        $getOrders = $this->getOrders($request->shop, $date);
        $filtered_orders = [];

        for ($i = 0; $i < 24; $i++) {
            $filtered_orders[$i]['Date'] = $formattedDate . " " . str_pad($i, 2, '0', STR_PAD_LEFT) . ":00";
            $filtered_orders[$i]['Cash'] = 0.00;
            $filtered_orders[$i]['Tng'] = 0.00;
            $filtered_orders[$i]['Visa'] = 0.00;
            $filtered_orders[$i]['MasterCard'] = 0.00;
            $filtered_orders[$i]['Amex'] = 0.00;
            $filtered_orders[$i]['Others'] = 0.00;
            $filtered_orders[$i]['Total'] = 0.00;
        }

        $cash = 0;
        $tng = 0;
        $visa = 0;
        $mastercard = 0;
        $amex = 0;
        $voucher = 0;
        $others = 0;

        foreach ($getOrders as $order) {
            if ($location_id == $order->location_id) {


                if ($order->source_name == 'pos') {

                    $datetime = new \DateTime($order->created_at);
                    $hour = $datetime->format('H');
                    $hour = ltrim($hour, "0");


                    $variables = Variable::where('mall_id', $request->mall_id)->where('shop', $request->shop)->first();

                    if (!$variables) {



                        $variables = Location::where('mall_id', $request->mall_id)->where('shop', $request->shop)->first();

                        if (!$variables) {

                            $cashVariables = ['cash'];
                            $tngVariables = ['tng'];
                            $visaVariables = ['CIMB Master Credit'];
                            $mastercardVariables = ['CIMB Amex Debit'];
                            $amexVariables = ['CIMB Visa Debit'];
                            $othersVariables = [''];
                        } else {

                            $cashVariables = explode(",", $variables->cash);
                            $tngVariables = explode(",", $variables->tng);
                            $visaVariables = explode(",", $variables->visa);
                            $mastercardVariables = explode(",", $variables->mastercard);
                            $amexVariables = explode(",", $variables->amex);
                            $othersVariables = explode(",", $variables->others);
                        }
                    } else {
                        $cashVariables = explode(",", strtolower($variables->cash));
                        $tngVariables = explode(",", strtolower($variables->tng));
                        $visaVariables = explode(",", strtolower($variables->visa));
                        $mastercardVariables = explode(",", strtolower($variables->mastercard));
                        $amexVariables = explode(",", strtolower($variables->amex));
                        $othersVariables = explode(",", strtolower($variables->others));
                    }

                    foreach ($order->payment_gateway_names as $paymentMethod) {
                        if (in_array(strtolower($paymentMethod), $cashVariables)) {
                            $cash = $order->total_price;
                        }
                        if (in_array(strtolower($paymentMethod), $visaVariables)) {
                            $visa = $order->total_price;
                        }
                        if (in_array(strtolower($paymentMethod), $tngVariables)) {
                            $tng = $order->total_price;
                        }
                        if (in_array(strtolower($paymentMethod), $mastercardVariables)) {
                            $mastercard = $order->total_price;
                        }
                        if (in_array(strtolower($paymentMethod), $amexVariables)) {
                            $amex = $order->total_price;
                        }

                        if (in_array(strtolower($paymentMethod), $othersVariables) && (!in_array(strtolower($paymentMethod), $cashVariables)  || !in_array(strtolower($paymentMethod), $visaVariables) || !in_array(strtolower($paymentMethod), $mastercardVariables) || !in_array(strtolower($paymentMethod), $amexVariables) || !in_array(strtolower($paymentMethod), $tngVariables) || !in_array(strtolower($paymentMethod), $amexVariables))) {
                            $others = $order->total_price;
                        }
                        $filtered_orders[$hour]['Cash'] = number_format((float) $filtered_orders[$hour]['Cash'], 2, '.', '') + number_format((float) $cash, 2, '.', '');
                        $filtered_orders[$hour]['Tng'] = number_format((float) $filtered_orders[$hour]['Tng'], 2, '.', '') + number_format((float) $tng, 2, '.', '');
                        $filtered_orders[$hour]['Visa'] = number_format((float) $filtered_orders[$hour]['Visa'], 2, '.', '') + number_format((float) $visa, 2, '.', '');
                        $filtered_orders[$hour]['MasterCard'] = number_format((float) $filtered_orders[$hour]['MasterCard'], 2, '.', '') + number_format((float) $mastercard, 2, '.', '');
                        $filtered_orders[$hour]['Amex'] = number_format((float) $filtered_orders[$hour]['Amex'], 2, '.', '') + number_format((float) $amex, 2, '.', '');
                        $filtered_orders[$hour]['Others'] = number_format((float) $filtered_orders[$hour]['Others'], 2, '.', '') + number_format((float) $others, 2, '.', '');
                        $filtered_orders[$hour]['Total'] = number_format((float) $filtered_orders[$hour]['MasterCard'], 2, '.', '') + number_format((float) $filtered_orders[$hour]['Amex'], 2, '.', '') + number_format((float) $filtered_orders[$hour]['Visa'], 2, '.', '') + number_format((float) $filtered_orders[$hour]['Cash'], 2, '.', '') + number_format((float) $filtered_orders[$hour]['Others'], 2, '.', '');
                    }
                }
            }
        }
        return response()->json(['data' => $filtered_orders]);
    }
    // muniza
    public function GenerateDailyReport($request)
    {
        $template_id = null;
        // Get template id
        if ($request instanceof Request) {
            if (!$request->template_id) {
                die('no template');
            }
        }

        $file        = [];
        if ($request instanceof Request) {

            $template_id = $request->template_id;
        } else {
            $template_id = $request->template_use;
        }
        $from_date   = null;
        $to_date     = null;
        $files = [];


        if ($request instanceof Request) {
            if (isset($request->report_from_date)) {
                $from_date = $request->report_from_date; // '06-05-2014';
                $to_date   = $request->report_from_date; // '07-05-2014';
            }
            if (isset($request->report_to_date)) {
                $to_date = $request->report_to_date; //'07-05-2014';
            }
        } else {
            $from_date = $request->report_date;
            $to_date = $request->report_date;
        }


        $date = new \DateTime($from_date);
        $to_date = new \DateTime($to_date);
        if ($request instanceof Request) {
            $date->modify('+1 day');

            $to_date->modify('+1 day');
            $originalFromDate = $date->format('Y-m-d');
            $originalToDate = $to_date->format('Y-m-d');
            if ($request->report_type == 'date_range') {
                $to_date->modify('+1 day');
            }
            // if($request->report_type == 'schedule'){
            //     $to_date = new \Datetime();
            // }
            $request->request->set('report_date', $date->format('d-m-Y'));
        } else {
            // $to_date->modify('+1 day');
            $originalFromDate = $date->format('Y-m-d');
            $originalToDate = $to_date->format('Y-m-d');
        }

        do {



            $report_date_formatted = $date->format('Y-m-d');
            $reportdata = [];
            if ($request instanceof Request) {
                $reportdata['template_use'] = $request->template_id;
                $reportdata['location'] = $request->pos_location;
                $reportdata['input_fields'] = json_encode($request->step2Fields);
                $reportdata['ftp_details'] = json_encode(["ftp_host" => $request->ftp_host, "ftp_protocol" => $request->ftp_protocol, "ftp_port" => $request->ftp_port, "ftp_user" => $request->ftp_user, "ftp_pass" => $request->ftp_pass, "ftp_path" => $request->ftp_path]);
            } else {
                $reportdata['template_use'] = $request->template_use;
                $reportdata['location'] = $request->location;
                $reportdata['ftp_details'] = $request->ftp_details;
                $reportdata['input_fields'] = $request->input_fields;
            }

            $reportdata['report_type'] = $request->report_type;
            if (!($request instanceof Request)) {
                $reportdata['report_type'] = 'schedule_cron';
            }
            $reportdata['shop'] = $request->shop;
            $reportdata['report_id'] = $request->report_id ?? NULL;
            $reportdata['reportId'] = $request->reportId ?? NULL;
            $reportdata['report_date'] = $report_date_formatted;
            $reportdata['report_to_date'] = $to_date;
            $reportdata['last_run'] = ($request->report_type == 'schedule') ? Carbon::parse($originalFromDate)->addDay() : Carbon::now();
            $reportdata['mall_id'] = $request->mall_id;
            $reportdata['schedule'] = $request->schedule;

            if ($request instanceof Request)
                $machine_id = isset($request->step2Fields['machine_id']) ? $request->step2Fields['machine_id'] : '0';
            else
                $machine_id = json_decode($request->input_fields)->machine_id;

            switch ($template_id) {
                case 1: // pos report
                    $serial_num = 0;

                    $store = Store::where('shop_url', $reportdata['shop'])->first();
                    $settings = $store->settings;
                    if (is_string($settings)) {
                        $settings = json_decode($store->settings);
                    }
                    $mall = Mall::find($reportdata['mall_id']);
                    $formatNumber = $mall->starting_number;
                    $dailyReport = ReportModel::where('report_id', $request->report_id)->count();
                    if ($formatNumber == '' || $formatNumber == NULL) {
                        $formatNumber = date('z') + 1;
                    }
                    // $formated_serial_num = str_pad($serial_num, 3, '0', STR_PAD_LEFT);
                    $formated_serial_num = (int)$formatNumber + $dailyReport;
                    if ($request->report_type != 'schedule') {
                        $filename = "D$machine_id.$formated_serial_num";
                    } else {
                        if (!($request instanceof Request)) {
                            $filename = "D$machine_id.$formated_serial_num";
                        } else {
                            $filename = '';
                        }
                    }

                    $files[] = $filename;
                    $file[] = $this->pos_report($request, $reportdata, $filename);
                    break;
                case 3: // cdl day end
                    $time_now = Carbon::now();
                    if ($request->report_type != 'schedule') {
                        $filename = $machine_id . "_" . $time_now->format('Ymd') . "_" . $time_now->format('Hms');
                    } else {
                        if (!($request instanceof Request)) {
                            $filename = $machine_id . "_" . $time_now->format('Ymd') . "_" . $time_now->format('Hms');
                        } else {
                            $filename = '';
                        }
                    }
                    $files[] = $filename;
                    $file[] = $this->cdl_day_end_report($request, $reportdata, $filename);
                    break;
            }
            $reportdata['files'] = $files;
            if ($request instanceof Request) {
                if ($request->report_type == 'date_range' || $request->report_type == 'schedule') {
                    $date->modify('+1 day');
                    $request->request->set('report_date', $date->format('d-m-Y'));
                }
                if ($request->report_type == 'single_day_only') {
                    $to_date->modify('-1 day');
                }
            }
        } while ($to_date  >  $date);


        $reportdata['report_date'] = $report_date_formatted;
        $reportdata['report_to_date'] = $originalToDate;
        $this->save_file_data($reportdata);

        return $files;
    }

    public function build_data($machine_id, $filename, $sales_amount, $date, $reportData, $request)
    {


        // Data
        // $formatted_date = date('Ymd', strtotime($date));
        $sales_amount = (float) $sales_amount;
        $sales_amount = str_pad(number_format($sales_amount, 2, '.', ''), 10, '0', STR_PAD_LEFT);
        $data = "D$machine_id" . "$date" . "$sales_amount";


        if ($reportData['report_type'] != "schedule" && $reportData['report_type'] != "schedule_cron") {
            $reportData['ftp_details'] = '';
        }

        if ($request instanceof Request) {
            if ($reportData['report_type'] != "schedule") {
                $this->print_report($filename, $data, $reportData['ftp_details']);
            }
        } else {

            $this->print_report($filename, $data, $reportData['ftp_details']);
        }
    }

    function print_report($filename, $content, $ftp_details)
    {
        if (!empty($ftp_details)) {

            $ftp_details = json_decode($ftp_details);
        }

        $path = public_path() . '/reports/';

        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0777, true, true);
        }
        $filepath = public_path() . '/reports/' . $filename;
        try {
            $handle = fopen($filepath, 'w');
            fwrite($handle, $content);

            if (!empty($ftp_details)) {
                $uploadFile = $this->upload_file($filename, $ftp_details->ftp_host, $ftp_details->ftp_protocol, $ftp_details->ftp_port, $ftp_details->ftp_user, $ftp_details->ftp_pass, $ftp_details->ftp_path);
            }
            fclose($handle);
        } catch (\Exception $e) {
            echo "Error {File: " . $e->getFile() . ", line " . $e->getLine() . "): " . $e->getMessage();
            return false;
        }
        // return true;
        // return url('/reports/' . $filename);


    }

    public function pos_report($request, $reportData, $filename)
    {

        //  Need to pass these variables from the frontend form
        // if (!$request->machine_id) {
        //     die('no machine id');
        // }
        // if (!$request->report_date) {
        //     die('no report date');
        // }
        // $machine_id = $request->machine_id; // '1321000';
        $serial_num = 0;
        // $date = $request->report_date; //'04-04-2023';

        if ($request instanceof Request) {

            $location_id = $request->pos_location;
            $machine_id = isset($request->step2Fields['machine_id']) ? $request->step2Fields['machine_id'] : '0';
        } else {
            $location_id = $request->location;
            $machine_id = json_decode($request->input_fields)->machine_id;
        }

        if (isset($request->report_date)) {
            $reportDate = new \DateTime($request->report_date);
        } else {
            $reportDate = new \DateTime();
        }



        $report_date_formatted = $reportDate->format('Y-m-d');



        $report_date = $reportDate->format('Ymd');
        $formattedDatemin = $reportDate->format('Y-m-d') . "T00:00:00";
        $formattedDatemax = $reportDate->format('Y-m-d') . "T23:59:59";
        $total_sales = 0;

        $shop = $request->shop;
        $store = Store::where('shop_url', $shop)->first();


        $api_endpoint = '/admin/api/' . env('SHOPIFY_API_VERSION', '2023-04') . '/orders.json?limit=250&limit=250&status=any&created_at_min=' . $formattedDatemin . '&created_at_max=' . $formattedDatemax . '';
        $orders = ShopController::shopify_rest_call($store->shopify_token, $shop, $api_endpoint, array(), 'GET');
        $response = json_decode($orders['response']);


        if (isset($response->orders)) {
            foreach ($response->orders as $i => $order) {
                if ($reportData['location'] == $order->location_id) {
                    $total_sales += $order->total_price;
                }
            }
        }
        $this->build_data($machine_id, $filename, $total_sales, $report_date, $reportData, $request);
    }
    // muniza


    function build_hourly_18_data($machine_id, $batch_id, $hourly_order_data, $date, $reportData, $filename, $request)
    {


        $data = '';


        for ($i = 0; $i < 24; $i++) {
            // Filename
            $hour = str_pad($i, 2, '0', STR_PAD_LEFT);
            // Data
            $formatted_date = date('dmY', strtotime($date));
            $receipt = $hourly_order_data[$i]['receipt'];
            $service = $hourly_order_data[$i]['service'];
            $no_of_pax = $hourly_order_data[$i]['no_of_pax'];
            $voucher = $hourly_order_data[$i]['voucher'];
            $sst_registered = $hourly_order_data[$i]['sst_registered'];


            $gto_sales = $hourly_order_data[$i]['gto_sales'];
            $gto_sales = number_format($gto_sales, 2, '.', '');

            $sst = $hourly_order_data[$i]['sst'];
            $sst = number_format($sst, 2, '.', '');

            $discount = $hourly_order_data[$i]['discount'];
            $discount = number_format($discount, 2, '.', '');

            $cash = $hourly_order_data[$i]['cash'];
            $cash = number_format($cash, 2, '.', '');

            $tng = $hourly_order_data[$i]['tng'];
            $tng = number_format($tng, 2, '.', '');

            $visa = $hourly_order_data[$i]['visa'];
            $visa = number_format($visa, 2, '.', '');

            $mastercard = $hourly_order_data[$i]['mastercard'];
            $mastercard = number_format($mastercard, 2, '.', '');

            $amex = $hourly_order_data[$i]['amex'];
            $amex = number_format($amex, 2, '.', '');

            $others = $hourly_order_data[$i]['others'];
            $others = number_format($others, 2, '.', '');

            $data .= "$machine_id|$batch_id|$formatted_date|$hour|$receipt|$gto_sales|$sst|$discount|$service|$no_of_pax|$cash|$tng|$visa|$mastercard|$amex|$voucher|$others|$sst_registered\n";
        }
        if ($reportData['report_type'] != "schedule") {
            $reportData['ftp_details'] = '';
        }
        if ($request instanceof Request) {
            if ($reportData['report_type'] != "schedule") {
                $this->print_report($filename, $data, $reportData['ftp_details']);
            }
        } else {

            $this->print_report($filename, $data, $reportData['ftp_details']);
        }
    }


    function build_hourly_data($machine_id, $batch_id, $hourly_order_data, $formatted_date, $reportData, $filename, $request)
    {

        $data = '';



        for ($i = 0; $i < 24; $i++) {
            // Filename
            $hour = str_pad($i, 2, '0', STR_PAD_LEFT) . "59";
            // Data
            $no_of_pax = 0;
            $gto_sales = $hourly_order_data[$i]['gto_sales'];
            $gst = $hourly_order_data[$i]['gst'];
            $discount = $hourly_order_data[$i]['discount'];
            $receipt = $hourly_order_data[$i]['receipt'];
            $gto_sales = number_format($gto_sales, 2, '.', '');
            $gst = number_format($gst, 2, '.', '');
            $discount = number_format($discount, 2, '.', '');
            $data .= "$machine_id|$formatted_date|$batch_id|$hour|$receipt|$gto_sales|$gst|$discount|$no_of_pax\n";
        }
        if ($reportData['report_type'] != "schedule") {
            $reportData['ftp_details'] = '';
        }
        if ($request instanceof Request) {
            if ($reportData['report_type'] != "schedule") {
                $this->print_report($filename, $data, $reportData['ftp_details']);
            }
        } else {

            $this->print_report($filename, $data, $reportData['ftp_details']);
        }
    }
    public function save_file_data($reportData)
    {
        $report = ($reportData['reportId'] !== NULL ? ReportModel::find($reportData['reportId']) : new ReportModel);
        $report->template_use = $reportData['template_use'];
        $report->location = $reportData['location'];
        $report->input_fields = $reportData['input_fields'];
        $report->ftp_details = $reportData['ftp_details'];
        $report->report_type = $reportData['report_type'];
        $report->report_date = $reportData['report_date'];
        $report->report_to_date = $reportData['report_to_date'];
        $report->last_run = $reportData['last_run'];
        $report->mall_id = $reportData['mall_id'];
        $report->filename = json_encode($reportData['files']);
        $report->shop = $reportData['shop'];
        $report->report_id = $reportData['report_id'];

        if ($reportData['report_id'] && $reportData['report_type']) {
            $reportModel = ReportModel::where('report_id', $reportData['report_id'])->where('report_date', $reportData['report_date'])->first();
            if (isset($reportModel)) {
                // $reportModel->filename = json_encode($reportData['files']);
                // $reportModel->update();
                $reportExists = true;
            } else {
                $reportExists = false;
            }
        } else {
            $reportExists = false;
        }
        $report->schedule_cron = $reportData['schedule'] ?? '';
        if (!$reportExists) {
            $report->save();
        }
    }

    public function checkFTPConnection(Request $request)
    {
        try {
            $host = $request->ftpHost;
            $port = $request->ftpPort;
            $user = $request->ftpUser;
            $pass = $request->ftpPass;
            return  $this->check_ftp($host, $port, $user, $pass);
        } catch (\Throwable $th) {
            return $th->getMessage();
            // return $th->getTraceAsString();
        }
    }

    public function generateReportDecide(Request $request)
    {
        // try {
        if ($request->template_id == 2) {
            $this->GenerateHourlyReport($request);
            return "success";
        } elseif ($request->template_id == 4 || $request->template_id == 5) {
            $response = $this->GenerateHourly18Report($request);

            if ($response) {
                if (isset($response['message'])) {
                    return $response['message'];
                } else {
                    if (is_string($response) && $response = "success") {
                        return 'success';
                    } else {
                        $errors = $response['errors'];
                        $errorMessage = "";
                        if (is_string($errors)) {
                            return $errors;
                        }
                        foreach ($errors as $error) {
                            $errorMessage .= $error['message'] . "\n";
                        }

                        // remove the last line break
                        $errorMessage = rtrim($errorMessage, "\n");

                        return $errorMessage;
                    }
                }
            } else {
                return 'error';
            }
        } elseif ($request->template_id == 3 || $request->template_id == 1) {
            $this->GenerateDailyReport($request);
            return "success";
        }
        // } catch (\Throwable $th) {
        //         // return $th->getMessage();
        //     return $th->getTraceAsString();
        // }
    }

    public function postEditReport(Request $request)
    {
        // try {
        if ($request->template_id == 2) {
            $this->GenerateHourlyReport($request);
            return "success";
        } elseif ($request->template_id == 4 || $request->template_id == 5) {
            $response = $this->GenerateHourly18Report($request);
            if ($response) {
                if (isset($response['message'])) {
                    return $response['message'];
                } else {
                    if (is_string($response) && $response = "success") {
                        return 'success';
                    } else {
                        $errors = $response['errors'];
                        $errorMessage = "";
                        if (is_string($errors)) {
                            return $errors;
                        }
                        foreach ($errors as $error) {
                            $errorMessage .= $error['message'] . "\n";
                        }

                        // remove the last line break
                        $errorMessage = rtrim($errorMessage, "\n");

                        return $errorMessage;
                    }
                }
            } else {
                return 'error';
            }
        } elseif ($request->template_id == 3 || $request->template_id == 1) {
            $this->GenerateDailyReport($request);
            return "success";
        }
        // } catch (\Throwable $th) {
        //         // return $th->getMessage();
        //     return $th->getTraceAsString();
        // }
    }


    public function GenerateHourlyReport($request)
    {
        $from_date   = null;
        $to_date     = null;
        $reportdata = [];
        $files = [];

        if ($request instanceof Request) {
            if (isset($request->report_from_date)) {
                $from_date = $request->report_from_date; // '06-05-2014';
                $to_date   = $request->report_from_date; // '07-05-2014';
            }
            if (isset($request->report_to_date)) {
                $to_date = $request->report_to_date; //'07-05-2014';
            }

            $location_id = $request->pos_location;
        } else {

            $location_id = $request->location;
            $from_date = $request->report_date;
            $to_date = $request->report_date;
        }

        // Single Date

        // if( isset($request->report_from_date) && $request->report_type == 'single_day_only' ){
        //     $from_date = $request->report_from_date;
        //     $to_date   = $request->report_from_date;
        // }

        // $date = \DateTime::createFromFormat('d-m-Y', $from_date);
        $date = new \DateTime($from_date);
        $to_date = new \DateTime($to_date);

        if ($request instanceof Request) {
            $date->modify('+1 day');

            $to_date->modify('+1 day');
            $originalFromDate = $date->format('Y-m-d');
            $originalToDate = $to_date->format('Y-m-d');
            if ($request->report_type == 'date_range') {
                $to_date->modify('+1 day');
            }
            // if($request->report_type == 'schedule'){
            //     $to_date = new \Datetime();
            // }
        } else {
            $originalFromDate = $date->format('Y-m-d');

            $originalToDate = $to_date->format('Y-m-d');
        }

        if ($request instanceof Request)
            $request->request->set('report_date', $date->format('d-m-Y'));

        do {


            if ($request instanceof Request)

                $machine_id = isset($request->step2Fields['machine_id']) ? $request->step2Fields['machine_id'] : '0';
            else
                $machine_id = json_decode($request->input_fields)->machine_id;
            $batchDate = new \DateTime();
            $batch_id = $batchDate->format('Ymd');
            $gto_sales = 0;
            $gst = 0;
            $discount = 0;

            $formatteddate = $date->format('Ymd');
            $formatteddate_ali = $date->format('Y-m-d');
            // $date = $date->format('Y-m-d');
            $datemin = $formatteddate_ali . "T00:00:00";

            $datemax = $formatteddate_ali . "T23:59:59";
            $datemin = new \DateTime($datemin);
            $datemax = new \DateTime($datemax);
            $formattedDatemin = $datemin->format('Y-m-d\TH:i:s');
            $formattedDatemax = $datemax->format('Y-m-d\TH:i:s');
            $shop = $request->shop;
            $store = Store::where('shop_url', $shop)->first();



            $api_endpoint = '/admin/api/' . env('SHOPIFY_API_VERSION', '2023-04') . '/orders.json?limit=250&status=any&created_at_min=' . $formattedDatemin . '&created_at_max=' . $formattedDatemax . '';
            $orders = ShopController::shopify_rest_call($store->shopify_token, $shop, $api_endpoint, array(), 'GET');

            $response = json_decode($orders['response']);



            for ($i = 0; $i < 24; $i++) {
                $hourly_order_data[$i]['gto_sales'] = 0.00;
                $hourly_order_data[$i]['gst'] = 0.00;
                $hourly_order_data[$i]['discount'] = 0.00;
                $hourly_order_data[$i]['receipt'] = 0;
            }
            if (isset($response->orders)) {
                foreach ($response->orders as $i => $order) {

                    if ($location_id == $order->location_id) {
                        if ($order->source_name == 'pos') {
                            $datetime = new \DateTime($order->created_at);
                            $hour = $datetime->format('H');
                            $hour = ltrim($hour, "0");
                            $gst = $order->total_tax;
                            $receipt = 1;
                            $discount = $order->total_discounts;
                            $gto_sales = $order->total_price - ($gst + $discount);
                            $hourly_order_data[$hour]['gto_sales'] = number_format((float) $hourly_order_data[$hour]['gto_sales'], 2, '.', '') + number_format((float) $gto_sales, 2, '.', '');
                            $hourly_order_data[$hour]['gst'] = number_format((float) $hourly_order_data[$hour]['gst'], 2, '.', '') + number_format((float) $gst, 2, '.', '');
                            $hourly_order_data[$hour]['receipt'] = $hourly_order_data[$hour]['receipt'] + $receipt;
                        }
                    }
                }
            }
            if ($request->report_type != 'schedule') {
                $filename = $machine_id . "_" . $formatteddate . "_" . date('His');
            } else {
                if (!($request instanceof Request)) {
                    $filename = $machine_id . "_" . $formatteddate . "_" . date('His');
                } else {
                    $filename = '';
                }
            }
            $files[] = $filename;
            $report_date_formatted = $date->format('Y-m-d');
            if ($request instanceof Request) {


                $reportdata['template_use'] = $request->template_id;
                $reportdata['location'] = $request->pos_location;
            } else {

                $reportdata['template_use'] = $request->template_use;
                $reportdata['location'] = $request->location;
            }

            if ($request instanceof Request) {
                $reportdata['input_fields'] = json_encode($request->step2Fields);
                $reportdata['ftp_details'] = json_encode(["ftp_host" => $request->ftp_host, "ftp_port" => $request->ftp_port, "ftp_user" => $request->ftp_user, "ftp_pass" => $request->ftp_pass, "ftp_path" => $request->ftp_path]);
            } else {
                $reportdata['ftp_details'] = $request->ftp_details;
                $reportdata['input_fields'] = $request->input_fields;
            }

            $reportdata['report_type'] = $request->report_type;
            if (!($request instanceof Request)) {
                $reportdata['report_type'] = 'schedule_cron';
            }


            $reportdata['shop'] = $request->shop;
            $reportdata['report_id'] = $request->report_id ?? NULL;
            $reportdata['reportId'] = $request->reportId ?? NULL;
            $reportdata['files'] = $files;
            $reportdata['report_date'] = $report_date_formatted;
            $reportdata['report_to_date'] = $to_date;
            $reportdata['last_run'] = ($request->report_type == 'schedule') ? Carbon::parse($originalFromDate)->addDay() : Carbon::now();
            $reportdata['schedule'] = $request->schedule;
            $reportdata['mall_id'] = $request->mall_id;
            $this->build_hourly_data($machine_id, $batch_id, $hourly_order_data, $formatteddate, $reportdata, $filename, $request);
            if ($request instanceof Request) {
                if ($request->report_type == 'date_range') {
                    $date->modify('+1 day');
                    $request->request->set('report_date', $date->format('d-m-Y'));
                }
            }
        } while ($to_date >  $date);

        $reportdata['report_date'] = $report_date_formatted;
        $reportdata['report_to_date'] = $originalToDate;

        $this->save_file_data($reportdata);
        return $files;
    }
    function GenerateHourly18Report($request)
    {

        $from_date   = null;
        $to_date     = null;
        $reportdata = [];

        // Date Range
        if ($request instanceof Request) {
            $location_id = $request->pos_location;
            if (isset($request->report_from_date)) {
                $from_date = $request->report_from_date; // '06-05-2014';
                $to_date   = $request->report_from_date; // '07-05-2014';
            }
            if (isset($request->report_to_date)) {
                $to_date = $request->report_to_date; //'07-05-2014';
            }
        } else {
            $from_date = $request->report_date;
            $location_id = $request->location;
            $to_date = $request->report_to_date;
        }

        // Single Date

        // if( isset($request->report_from_date) && $request->report_type == 'single_day_only' ){
        //     $from_date = $request->report_from_date;
        //     $to_date   = $request->report_from_date;
        // }

        // $date = \DateTime::createFromFormat('d-m-Y', $from_date);
        $date = new \DateTime($from_date);
        $to_date = new \DateTime($to_date);

        if ($request instanceof Request) {
            $date->modify('+1 day');

            $to_date->modify('+1 day');
            $originalFromDate = $date->format('Y-m-d');
            $originalToDate = $to_date->format('Y-m-d');
            if ($request->report_type == 'date_range') {
                $to_date->modify('+1 day');
            }
            // if($request->report_type == 'schedule'){
            //     $to_date = new \Datetime();

            // }
        } else {
            // $to_date->modify('+1 day');
            $originalFromDate = $date->format('Y-m-d');
            $originalToDate = $to_date->frmat('Y-m-d');
        }
        if ($request instanceof Request)
            $request->request->set('report_date', $date->format('d-m-Y'));

        $files = [];

        do {


            $report_date_formatted = $date->format('Y-m-d');
            $report_to_date_formatted = $to_date->format('Y-m-d');

            if ($request instanceof Request)

                $machine_id = isset($request->step2Fields['machine_id']) ? $request->step2Fields['machine_id'] : '0';
            else
                $machine_id = json_decode($request->input_fields)->machine_id;

            $formatted_date = $date->format('Ymd');

            if ($request->report_type != 'schedule') {
                $filename = "H" . $machine_id . "_" . $formatted_date;
            } else {
                if (!($request instanceof Request)) {
                    $filename = "H" . $machine_id . "_" . $formatted_date;
                } else {
                    $filename = "";
                }
            }


            $files[] = $filename;
            $batchDate = new \DateTime();
            $batch_id = $batchDate->format('Ymd');
            $shop = $request->shop;
            $store = Store::where('shop_url', $shop)->first();



            $formatteddate = $date->format('Ymd');
            $dateFormat = $date->format('Y-m-d');
            $datemin = $dateFormat . "T00:00:00";
            $datemax = $dateFormat . "T23:59:59";
            $datemin = new \DateTime($datemin);
            $datemax = new \DateTime($datemax);
            $formattedDatemin = $datemin->format('Y-m-d\TH:i:s');
            $formattedDatemax = $datemax->format('Y-m-d\TH:i:s');

            $api_endpoint = '/admin/api/' . env('SHOPIFY_API_VERSION', '2023-04') . '/orders.json?limit=250&status=any&created_at_min=' . $formattedDatemin . '&created_at_max=' . $formattedDatemax . '';
            $orders = ShopController::shopify_rest_call($store->shopify_token, $shop, $api_endpoint, array(), 'GET');
            $response = json_decode($orders['response']);
            $apiData = array('sales' => array());

            $template_id = $request->template_id ?? $request->template_use;


            for ($i = 0; $i < 24; $i++) {
                $hourly_order_data[$i]['receipt'] = 0;
                $hourly_order_data[$i]['gto_sales'] = 0.00;
                $hourly_order_data[$i]['sst'] = 0.00;
                $hourly_order_data[$i]['discount'] = 0.00;
                $hourly_order_data[$i]['tng'] = 0.00;
                $hourly_order_data[$i]['cash'] = 0.00;
                $hourly_order_data[$i]['visa'] = 0.00;
                $hourly_order_data[$i]['mastercard'] = 0.00;
                $hourly_order_data[$i]['amex'] = 0.00;
                $hourly_order_data[$i]['others'] = 0.00;
                $hourly_order_data[$i]['no_of_pax'] = 0;
                $hourly_order_data[$i]['service'] = 0;
                $hourly_order_data[$i]['voucher'] = 0;
                $hourly_order_data[$i]['sst_registered'] = "Y";

                $apiData['sales'][$i] = ['sale' =>
                array(
                    'machineid' => $machine_id,
                    'batchid' => $batch_id,
                    'date' => $formatteddate,
                    'hour' => str_pad($i, 2, '0', STR_PAD_LEFT), // This will give you 24 different hours
                    'receiptcount' => '0',
                    'gto' => '0.00',
                    'gst' => '0.00',
                    'discount' => '0.00',
                    'servicecharge' => '0',
                    'noofpax' => '0',
                    'cash' => '0.00',
                    'tng' => '0.00',
                    'visa' => "0.00",
                    'mastercard' => '0.00',
                    'amex' => '0.00',
                    'voucher' => '0.00',
                    'othersamount' => '0.00',
                    'gstregistered' => 'Y'
                )];
            }
            if (isset($response->orders)) {
                foreach ($response->orders as $i => $order) {
                    if ($location_id == $order->location_id) {
                        if ($order->source_name == 'pos') {
                            $datetime = new \DateTime($order->created_at);
                            $hour = $datetime->format('H');
                            $hour = ltrim($hour, "0");

                            $receipt = 1;
                            $gto_sales = 0;
                            $discount = 0;
                            $service = 0;
                            $no_of_pax = 0;
                            $cash = 0;
                            $tng = 0;
                            $visa = 0;
                            $mastercard = 0;
                            $amex = 0;
                            $voucher = 0;
                            $others = 0;
                            $sst_registered = "Y";
                            $gto_sales = $order->total_price;
                            $sst = $order->total_tax;
                            $discount = $order->total_discounts;
                            $service = 0;
                            $no_of_pax = 0;
                            $voucher = 0;

                            $variables = Variable::where('mall_id', $request->mall_id)->where('shop', $request->shop)->first();

                            if (!$variables) {

                                $variables = Location::where('mall_id', $request->mall_id)->where('shop', $request->shop)->first();


                                if (!$variables) {

                                    $cashVariables = ['cash'];
                                    $tngVariables = ['tng'];
                                    $visaVariables = ['visa'];
                                    $mastercardVariables = ['master'];
                                    $amexVariables = ['amex'];
                                    $othersVariables = [''];
                                } else {

                                    $cashVariables = explode(",", strtolower($variables->cash));
                                    $tngVariables = explode(",", strtolower($variables->tng));
                                    $visaVariables = explode(",", strtolower($variables->visa));
                                    $mastercardVariables = explode(",", strtolower($variables->mastercard));
                                    $amexVariables = explode(",", strtolower($variables->amex));
                                    $othersVariables = explode(",", strtolower($variables->others));
                                }
                            } else {
                                $cashVariables = explode(",", strtolower($variables->cash));
                                $tngVariables = explode(",", strtolower($variables->tng));
                                $visaVariables = explode(",", strtolower($variables->visa));
                                $mastercardVariables = explode(",", strtolower($variables->mastercard));
                                $amexVariables = explode(",", strtolower($variables->amex));
                                $othersVariables = explode(",", strtolower($variables->others));
                            }


                            foreach ($order->payment_gateway_names as $paymentMethod) {
                                if (in_array(strtolower($paymentMethod), $cashVariables)) {
                                    $cash = $order->total_price;
                                }
                                if (in_array(strtolower($paymentMethod), $visaVariables)) {
                                    $visa = $order->total_price;
                                }
                                if (in_array(strtolower($paymentMethod), $tngVariables)) {
                                    $tng = $order->total_price;
                                }
                                if (in_array(strtolower($paymentMethod), $mastercardVariables)) {
                                    $mastercard = $order->total_price;
                                }
                                if (in_array(strtolower($paymentMethod), $amexVariables)) {
                                    $amex = $order->total_price;
                                }
                                if (in_array(strtolower($paymentMethod), $othersVariables) && (!in_array(strtolower($paymentMethod), $cashVariables) || !in_array(strtolower($paymentMethod), $tngVariables) || !in_array(strtolower($paymentMethod), $visaVariables) || !in_array(strtolower($paymentMethod), $mastercardVariables) || !in_array(strtolower($paymentMethod), $amexVariables) || !in_array(strtolower($paymentMethod), $amexVariables))) {
                                    $others = $order->total_price;
                                }
                                $hourly_order_data[$hour]['cash'] = number_format((float) $hourly_order_data[$hour]['cash'], 2, '.', '') + number_format((float) $cash, 2, '.', '');
                                $hourly_order_data[$hour]['tng'] = number_format((float) $hourly_order_data[$hour]['tng'], 2, '.', '') + number_format((float) $tng, 2, '.', '');
                                $hourly_order_data[$hour]['visa'] = number_format((float) $hourly_order_data[$hour]['visa'], 2, '.', '') + number_format((float) $visa, 2, '.', '');
                                $hourly_order_data[$hour]['mastercard'] = number_format((float) $hourly_order_data[$hour]['mastercard'], 2, '.', '') + number_format((float) $mastercard, 2, '.', '');
                                $hourly_order_data[$hour]['amex'] = number_format((float) $hourly_order_data[$hour]['amex'], 2, '.', '') + number_format((float) $amex, 2, '.', '');
                                $hourly_order_data[$hour]['others'] = number_format((float) $hourly_order_data[$hour]['others'], 2, '.', '') + number_format((float) $others, 2, '.', '');
                            }
                            $gto_sales = number_format((float) $hourly_order_data[$hour]['cash'], 2, '.', '') + number_format((float) $hourly_order_data[$hour]['tng'], 2, '.', '') + number_format((float) $hourly_order_data[$hour]['visa'], 2, '.', '') + number_format((float) $hourly_order_data[$hour]['mastercard'], 2, '.', '') + number_format((float) $hourly_order_data[$hour]['amex'], 2, '.', '') + number_format((float) $hourly_order_data[$hour]['others'], 2, '.', '');
                            $hourly_order_data[$hour]['receipt'] = $hourly_order_data[$hour]['receipt'] + $receipt;
                            $hourly_order_data[$hour]['gto_sales'] = number_format((float) $hourly_order_data[$hour]['gto_sales'], 2, '.', '') + number_format((float) $gto_sales, 2, '.', '');
                            $hourly_order_data[$hour]['sst'] = number_format((float) $hourly_order_data[$hour]['sst'], 2, '.', '') + number_format((float) $sst, 2, '.', '');
                            $hourly_order_data[$hour]['discount'] = number_format((float) $hourly_order_data[$hour]['discount'], 2, '.', '') + number_format((float) $discount, 2, '.', '');
                            $hourly_order_data[$hour]['no_of_pax'] = $no_of_pax;
                            $hourly_order_data[$hour]['service'] = $service;
                            $hourly_order_data[$hour]['voucher'] = $voucher;
                            $hourly_order_data[$hour]['sst_registered'] = $sst_registered;

                            if ($template_id == 5) {
                                $sale = array(
                                    'sale' => array(
                                        'machineid' => $machine_id,
                                        'batchid' => $batch_id,
                                        'date' => $formatteddate,
                                        'hour' => str_pad($hour, 2, '0', STR_PAD_LEFT), // This will give you 24 different hours
                                        'receiptcount' => $hourly_order_data[$hour]['receipt'],
                                        'gto' => $hourly_order_data[$hour]['gto_sales'],
                                        'gst' => $hourly_order_data[$hour]['sst'],
                                        'discount' => $hourly_order_data[$hour]['discount'],
                                        'servicecharge' => $service,
                                        'noofpax' => $no_of_pax,
                                        'cash' => $hourly_order_data[$hour]['cash'],
                                        'tng' => $hourly_order_data[$hour]['tng'],
                                        'visa' => $hourly_order_data[$hour]['visa'],
                                        'mastercard' => $hourly_order_data[$hour]['mastercard'],
                                        'amex' => $hourly_order_data[$hour]['amex'],
                                        'voucher' => $hourly_order_data[$hour]['voucher'],
                                        'othersamount' => $hourly_order_data[$hour]['others'],
                                        'gstregistered' => $hourly_order_data[$hour]['sst_registered']
                                    )
                                );
                                $apiData['sales'][$hour] = $sale;
                                // array_push($apiData['sales'], $sale);

                            }
                        }
                    }
                }
            }
            $reportdata['template_use'] = $request->template_id;
            $reportdata['location'] = $request->pos_location;
            if ($request instanceof Request) {
                $reportdata['input_fields'] = json_encode($request->step2Fields);
                $reportdata['ftp_details'] = json_encode(["ftp_host" => $request->ftp_host, "ftp_port" => $request->ftp_port, "ftp_user" => $request->ftp_user, "ftp_pass" => $request->ftp_pass, "ftp_path" => $request->ftp_path]);
            } else {
                $reportdata['ftp_details'] = $request->ftp_details;
                $reportdata['input_fields'] = $request->input_fields;
            }
            $reportdata['report_type'] = $request->report_type;
            if (!($request instanceof Request)) {
                $reportdata['report_type'] = 'schedule_cron';
            }
            $reportdata['shop'] = $request->shop;
            $reportdata['report_id'] = $request->report_id ?? NULL;
            $reportdata['reportId'] = $request->reportId ?? NULL;
            $reportdata['files'] = $files;
            $reportdata['report_date'] = $report_date_formatted;
            $reportdata['report_to_date'] = $report_to_date_formatted;
            $reportdata['last_run'] = $request->report_type == 'schedule' ? Carbon::parse($originalFromDate)->addDay() : Carbon::now();
            $reportdata['schedule'] = $request->schedule;
            $reportdata['mall_id'] = $request->mall_id;

            if ($template_id !== 5) {
                $this->build_hourly_18_data($machine_id, $batch_id, $hourly_order_data, $formatteddate, $reportdata, $filename, $request);
            } else {
                $newSalesArray = array_map(function ($item) {
                    return array('sale' => $item['sale']);
                }, $apiData['sales']);

                $apiData['sales'] = $newSalesArray;
                $apiData = json_encode($apiData);
                $apiInfo = [];

                if ($request instanceof Request) {

                    $apiInfo['base_url'] = $request->base_url;
                    $apiInfo['token_url'] = $request->token_url;
                    $apiInfo['username'] = $request->username;
                    $apiInfo['password'] = $request->password;
                    $apiInfo['api_url'] = $request->api_url;
                    $reportdata['ftp_details'] = json_encode(["base_url" => $apiInfo['base_url'], "token_url" => $apiInfo['token_url'], "username" => $apiInfo['username'], "password" => $apiInfo['password'], "api_url" => $apiInfo['api_url']]);
                    $access_token = $this->generateToken($reportdata['ftp_details']);

                    if ($access_token) {

                        $apiResponse = $this->sendRequest($apiData, $access_token, $reportdata['ftp_details'], $filename);
                    }
                } else {
                    $reportdata['ftp_details'] = $request->ftp_details;
                    $reportdata['input_fields'] = $request->input_fields;
                    $access_token = $this->generateToken($reportdata['ftp_details']);
                    if ($access_token) {

                        $apiResponse = $this->sendRequest($apiData, $access_token, $reportdata['ftp_details'], $filename);
                    }
                }

                if ($request instanceof Request) {


                    $reportdata['template_use'] = $request->template_id;
                    $reportdata['location'] = $request->pos_location;
                } else {

                    $reportdata['template_use'] = $request->template_use;
                    $reportdata['location'] = $request->location;
                }
            }
            if ($request instanceof Request) {
                if ($request->report_type == 'date_range' || $request->report_type == 'schedule') {
                    $date->modify('+1 day');
                    $request->request->set('report_date', $date->format('d-m-Y'));
                }
            }
        } while ($to_date >  $date);



        $reportdata['report_date'] = $report_date_formatted;
        $reportdata['report_to_date'] = $originalToDate;
        $this->save_file_data($reportdata);
        if ($template_id == 5) {
            return $apiResponse['status'];
        }
        return false;
    }

    function cdl_day_end_report($request, $reportData, $filename)
    {

        if ($request instanceof Request)

            $machine_id = isset($request->step2Fields['machine_id']) ? $request->step2Fields['machine_id'] : '0';
        else
            $machine_id = json_decode($request->input_fields)->machine_id;

        $batchDate = new \DateTime();
        $batch_id = $batchDate->format('Ymd');


        if (isset($reportData['report_date'])) {
            $reportDate = new \DateTime($reportData['report_date']);
        } else {
            $reportDate = new \DateTime();
        }
        $report_date = $reportDate->format('dmY');
        $formattedDatemin = $reportDate->format('Y-m-d') . "T00:00:00";
        $formattedDatemax = $reportDate->format('Y-m-d') . "T23:59:59";


        $shop = $request->shop;
        $ftp_details = json_encode(["ftp_host" => $request->ftp_host, "ftp_port" => $request->ftp_port, "ftp_user" => $request->ftp_user, "ftp_pass" => $request->ftp_pass, "ftp_path" => $request->ftp_path]);
        $report_date_formatted = $reportDate->format('Y-m-d');



        $store = Store::where('shop_url', $shop)->first();
        $api_endpoint = '/admin/api/' . env('SHOPIFY_API_VERSION', '2023-04') . '/orders.json?limit=250&status=any&created_at_min=' . $formattedDatemin . '&created_at_max=' . $formattedDatemax . '';
        $orders = ShopController::shopify_rest_call($store->shopify_token, $shop, $api_endpoint, array(), 'GET');
        $response = json_decode($orders['response']);
        $gto_sales = 0;
        $gst = 0;
        $discount = 0;
        $receipt = 0;
        if (isset($response->orders)) {
            foreach ($response->orders as $order) {

                if ($location_id == $order->location_id) {

                    if ($order->source_name == 'pos') {
                        $gto_sales = number_format((float) $gto_sales, 2, '.', '') + (number_format($order->total_price, 2, '.', '') - number_format($order->total_tax, 2, '.', ''));
                        $gst = number_format((float) $gst, 2, '.', '') + number_format($order->total_tax, 2, '.', '');
                        $discount = number_format((float) $discount, 2, '.', '') + number_format($order->total_discounts, 2, '.', '');

                        $receipt = $receipt + 1;
                    }
                }
            }
        }
        $hour = "0000";
        // Data
        $no_of_pax = 0;
        $gto_sales = number_format($gto_sales, 2, '.', '');
        $gst = number_format($gst, 2, '.', '');
        $discount = number_format($discount, 2, '.', '');
        $data = "$machine_id|$report_date|$batch_id|$hour|$receipt|$gto_sales|$gst|$discount|$no_of_pax\n";
        if ($reportData['report_type'] != "schedule") {
            $ftp_details = '';
        }
        return $this->print_report($filename, $data, $ftp_details);
    }

    /**
     *
     */
    function check_ftp($host, $port, $username, $password)
    {

        // $host_error = false;
        // try {
        //     $ftp =  ftp_connect($host, $port, 30);
        //     if (false === $ftp) {
        //         $host_error = true;
        //         throw new \Exception('Unable to connect to host.');
        //     }

        //     $login_result = @ftp_login($ftp, $username, $password);
        //     if (false === $login_result) {
        //         $host_error = true;
        //         throw new \Exception('The provided FTP details are incorrect. Please check');
        //     }
        //     ftp_close($ftp);
        // } catch (\Exception $e) {

        //     $message = $e->getMessage();
        //     $ftp_error = response([
        //         'success' => false,
        //         'message' => $message
        //     ], 400);
        //     return $ftp_error;
        // }
        return  response([
            'success' => true,
            'message' => 'Connected successfully.'
        ], 200);
    }

    function upload_file($filename, $host, $protocol, $port, $username, $password, $path)
    {
        if ($protocol != 'sftp') {

            $path = ltrim($path, "/");
            $dest_file = './' . $path . '/' . $filename;
            $source_file = public_path('reports/' . $filename);
            $ftp =  ftp_connect($host, $port, 30);
            $login_result = ftp_login($ftp, $username, $password);
            if (!$login_result) {
                return false;
            }

            ftp_pasv($ftp, true);
            $fileSize = ftp_size($ftp, $dest_file);

            if ($fileSize !== -1) {
                ftp_delete($ftp, $dest_file);
            }
            $ret = ftp_nb_put($ftp, $dest_file, $source_file, FTP_BINARY, FTP_AUTORESUME);

            while (FTP_MOREDATA == $ret) {
                $ret = ftp_nb_continue($ftp);
            }

            ftp_close($ftp);
        } else {

            $path = ltrim($path, "/");
            $dest_file = './' . $path . '/' . $filename;
            $source_file = public_path('reports/' . $filename);
            $config = [
                'host' => $host,
                'username' => $username,
                'password' => $password,
                'root' => $path,
                'port' => $port,
                'timeout' => 30,
            ];

            $sftp = new SFTP($host, $port);

            if (!$sftp->login($username, $password)) {
                throw new \Exception('Login failed');
            }


            // Read the local file
            $contents = file_get_contents($source_file);
            // To write a file
            $sftp->put($dest_file, $contents);
        }

        return true;
    }
}
