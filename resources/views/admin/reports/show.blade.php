@extends('admin.layouts.app')
@section('title')
    Single Report View
@endsection
@section('content')
@php
$shop = $report->shop;
$store = \App\Models\Store::where('shop_url', $shop)->first();

$api_endpoint = '/admin/api/' . env('SHOPIFY_API_VERSION', '2023-04') . '/locations/' . $report->location . '.json';
$location = \App\Http\Controllers\ShopController::shopify_rest_call($store->shopify_token, $shop, $api_endpoint, [], 'GET');
$response = json_decode($location['response']);
if (isset($response->location)) {
    $location = $response->location;
    $locationName = $location->name;
} else {
    $locationName = $report->location;
}
@endphp
    <!--begin::Content-->
    <div id="kt_app_content" class="app-content">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-fluid">
            <!--begin::Row-->

            <div class="row g-5 g-xl-10">

                <!--begin::Basic info-->
                <div class="card mb-5 mb-xl-10">
                    <!--begin::Card header-->
                    <div class="card-header border-0 cursor-pointer">

                        <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                            <!--begin::Title-->
                            <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">
                                {{-- {{ $store->shop_url }}</h1> --}}
                            <!--end::Title-->
                            <!--begin::Breadcrumb-->
                            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                                <!--begin::Item-->
                                <li class="breadcrumb-item text-muted">
                                    <a href="/admin/reports" class="text-muted text-hover-primary">Reports</a>
                                </li>
                                @if($report->filename != '')
                                <li class="breadcrumb-item">
                                    <span class="bullet bg-gray-400 w-5px h-2px"></span>
                                </li>
                                <!--end::Item-->
                                <!--begin::Item-->
                                <li class="breadcrumb-item text-muted">{{ json_decode($report->filename)[0] }}</li>
                                @endif
                                <!--end::Item-->
                            </ul>
                            <!--end::Breadcrumb-->
                        </div>
                        <!--end::Page title-->
                        <!--begin::Actions-->
                        <div class="d-flex align-items-center gap-2 gap-lg-3">

                    <a href="/download/{{ $report->filename }}"
                        class="btn btn-flex btn-sm fw-bold btn-success px-2 px-md-5 py-3">Download</a>
                    <a href="{{ route('reports.index') }}"
                        class="btn btn-flex btn-sm fw-bold btn-primary px-2 px-md-5 py-3">Back</a>
                        </div>
                    </div>
                    <!--begin::Card header-->
                </div>
                <!--end::Basic info-->

            </div>
            <!--end::Row-->
            <div class="row g-5 g-xl-10">

                <!--begin::Basic info-->
                <div class="card mb-5 mb-xl-10">
                    <!--begin::Content-->
                    <div id="kt_account_settings_profile_details" class="collapse show">
                        <!--begin::Form-->

                        <div class="card-body border-top p-9">
                            <!--begin::Input group-->
                            <div class="row mb-1">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label fw-semibold fs-6">Report ID</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8">
                                    <!--begin::Row-->
                                    <div class="row">
                                        <!--begin::Col-->
                                        <div class="col-lg-12 fv-row">{{ $report->id }}</div>
                                        <!--end::Col-->
                                    </div>
                                    <!--end::Row-->
                                </div>
                                <!--end::Col-->
                            </div>
                            <div class="row mb-1">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label fw-semibold fs-6">Store Name</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8 fv-row">{{ $report->shop }}</div>
                                <!--end::Col-->
                            </div>
                            <div class="row mb-2">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label fw-semibold fs-6">POS Location</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8 fv-row">
                                    @php
                                        $reportsTypes = ['single_day_only' => 'Single Day Only', 'schedule' => 'Schedule', 'date_range' => 'Date Range'];
                                    @endphp
                                    {{ $locationName }}
                                </div>
                                <!--end::Col-->
                            </div>
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label fw-semibold fs-6">Mall Name</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8 fv-row">
                                    @php
                                        $mall = \App\Models\Mall::where('id', $report->mall_id)->first();
                                    @endphp
                                    {{ $mall->title }}
                                    <br>
                                    @php
                                    if($report->template_use == 5){
                                        $api = json_decode($report->ftp_details);
                                        $ftp = [];
                                    }
                                    else{
                                        $ftp = json_decode($report->ftp_details);
                                        $api = [];
                                    }
                                    @endphp
                                    @if($report->template_use == 5)
                                     <strong>Base Url:</strong> {{ $api->base_url }}<br>
                                     <strong>Token Url:</strong> {{ $api->token_url }}<br>
                                     <strong>Username:</strong> {{ $api->username }}<br>
                                     <strong>Password:</strong> {{ $api->password }}<br>
                                     <strong>Api Url:</strong> {{ $api->api_url }}<br>
                                    @else
                                     <strong>Host: </strong> {{ $ftp->ftp_host }}<br>
                                     <strong>FTP/SFTP: </strong> {{ $ftp->ftp_protocol }}<br>
                                     <strong>Port: </strong> {{ $ftp->ftp_port }}<br>
                                     <strong>Username: </strong> {{ $ftp->ftp_user }}<br>
                                     <strong>Password: </strong> {{ $ftp->ftp_pass }}<br>
                                    <strong>Path:</strong> {{ $ftp->ftp_path }}<br>
                                    @endif

                                </div>
                                <!--end::Col-->
                            </div>
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label fw-semibold fs-6">Report Date</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8 fv-row">

                                    {{ $report->report_date }}
                                    <input type="hidden" name="date_picker" id="date_picker"
                                        value="{{ $report->report_date }}">
                                    <input type="hidden" name="shop" id="shop" value="{{ $report->shop }}">
                                    <input type="hidden" name="mall_id" id="mall_id" value="{{ $report->mall_id }}">
                                    <input type="hidden" name="location_id" id="location_id"
                                        value="{{ $report->location }}">
                                </div>
                                <!--end::Col-->
                            </div>
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label fw-semibold fs-6">Date Last Generated</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8 fv-row">

                                    {{ $report->updated_at }}
                                </div>
                                <!--end::Col-->
                            </div>
                            @if($report->template_use == 1  || $report->template_use == 3)
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label fw-semibold fs-6">Report</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8 fv-row">
                                    @php
                                    $machine_id = json_decode($report->input_fields)->machine_id;
                                    if (isset($report->report_date)) {
                                        $reportDate = new \DateTime($report->report_date);
                                    } else {
                                        $reportDate = new \DateTime();
                                    }

                                    $report_date = $reportDate->format('Ymd');
                                    $formattedDatemin = $reportDate->format('Y-m-d') . "T00:00:00";
                                    $formattedDatemax = $reportDate->format('Y-m-d') . "T23:59:59";
                                    $total_sales = 0;

                                    $store = \App\Models\Store::where('shop_url', $report->shop)->first();
                                    $api_endpoint = '/admin/api/' . env('SHOPIFY_API_VERSION', '2023-04') . '/orders.json?status=any&created_at_min=' . $formattedDatemin . '&created_at_max=' . $formattedDatemax . '';
                                    $orders = \App\Http\Controllers\ShopController::shopify_rest_call($store->shopify_token, $shop, $api_endpoint, array(), 'GET');
                                    $response = json_decode($orders['response']);
                                    if (isset($response->orders)) {
                                        foreach ($response->orders as $i => $order) {
											if($report->location == $order->location_id){
												$total_sales += $order->total_price;
											}
                                        }
                                    }
                                    $sales_amount = (float) $total_sales;
                                    $sales_amount = str_pad(number_format($sales_amount, 2, '.', ''), 10, '0', STR_PAD_LEFT);
                                    $data = "D$machine_id" . "$report_date" . "$sales_amount";
                                    @endphp
                                    {{ $data }}
                                </div>
                                <!--end::Col-->
                            </div>
                            @endif
                        </div>
                        <!--end::Card body-->

                    </div>
                    <!--end::Content-->
                </div>
                <!--end::Basic info-->

            </div>
            <!--end::Row-->
            @if($report->template_use == 2 || $report->template_use == 4 || $report->template_use == 5)
            <div class="table-responsive">
                <table class="table table-row-bordered align-middle gy-4 gs-9">
                    <thead class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bold bg-light bg-opacity-75">
                        <tr>
                            <td class="min-w-200px">Date</td>
                            <td class="min-w-100px">Cash</td>
                            <td class="min-w-100px">TNG</td>
                            <td class="min-w-100px">Visa</td>
                            <td class="min-w-200px">MasterCard</td>
                            <td class="min-w-100px">Amex</td>
                            <td class="min-w-150px">Others</td>
                            <td class="min-w-150px">Total</td>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600" id="filter_order">
                        <!--end::Table row-->
                    </tbody>
                </table>
                <!--end::Table-->
            </div>
            @endif
        </div>
        <!--end::Content container-->
    </div>
    <!--end::Content-->
    @push('script')
        <script>
            $(document).ready(function() {
                // $('#filter').click(function() {
                var date_picker = $('#date_picker').val();
                var shop = $('#shop').val();
                var mall_id = $('#mall_id').val();
                var location_id = $('#location_id').val();

                $.ajax({
                    url: '/admin/get_reports',
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        date_picker: date_picker,
                        mall_id: mall_id,
                        location_id: location_id,
                        shop: shop,
                    },
                    success: function(data) {
                        var orderdata = '';
                        var cash_total = parseFloat(0).toFixed(2);
                        var visa_total = parseFloat(0).toFixed(2);
                        var tng_total = parseFloat(0).toFixed(2);
                        var mastercard_total = parseFloat(0).toFixed(2);
                        var amex_total = parseFloat(0).toFixed(2);
                        var others_total = parseFloat(0).toFixed(2);
                        var total_total = parseFloat(0).toFixed(2);
                        data.data.forEach(order => {
                            console.log(cash_total);
                            cash_total = parseFloat(cash_total) + parseFloat(order
                                .Cash);
                            visa_total = parseFloat(visa_total) + parseFloat(order
                                .Visa);
                            tng_total = parseFloat(tng_total) + parseFloat(order
                                .Visa);
                            mastercard_total = parseFloat(mastercard_total) +
                                parseFloat(order.MasterCard);
                            amex_total = parseFloat(amex_total) + parseFloat(order
                                .Amex);
                            others_total = parseFloat(others_total) + parseFloat(order
                                .Others);
                            total_total = parseFloat(total_total) + parseFloat(order
                                .Total);
                            orderdata += '<tr>';
                            orderdata += '    <td>' + order.Date + '</td>';
                            orderdata += '    <td>' + parseFloat(order.Cash).toFixed(
                                2) + '</td>';
                            orderdata += '    <td>' + parseFloat(order.Tng).toFixed(
                                2) + '</td>';
                            orderdata += '    <td>' + parseFloat(order.Visa).toFixed(
                                2) + '</td>';
                            orderdata += '    <td>' + parseFloat(order.MasterCard)
                                .toFixed(2) + '</td>';
                            orderdata += '    <td>' + parseFloat(order.Amex).toFixed(
                                2) + '</td>';
                            orderdata += '    <td>' + parseFloat(order.Others).toFixed(
                                2) + '</td>';
                            orderdata += '    <td>' + parseFloat(order.Total).toFixed(
                                2) + '</td>';
                            orderdata += '</tr>';
                        });
                        orderdata += '<tr>';
                        orderdata += '    <td>Total</td>';
                        orderdata += '    <td>' + parseFloat(cash_total).toFixed(2) + '</td>';
                        orderdata += '    <td>' + parseFloat(tng_total).toFixed(2) + '</td>';
                        orderdata += '    <td>' + parseFloat(visa_total).toFixed(2) + '</td>';
                        orderdata += '    <td>' + parseFloat(mastercard_total).toFixed(2) +
                            '</td>';
                        orderdata += '    <td>' + parseFloat(amex_total).toFixed(2) + '</td>';
                        orderdata += '    <td>' + parseFloat(others_total).toFixed(2) + '</td>';
                        orderdata += '    <td>' + parseFloat(total_total).toFixed(2) + '</td>';
                        orderdata += '</tr>';
                        $('#filter_order').html(orderdata);
                    },
                    error: function() {
                        alert('Error occurred while filtering data.');
                    }
                });
            });
        </script>
    @endpush
@endsection
