@extends('admin.layouts.app')
@section('title')
    Order Reports
@endsection
@section('content')
    <!--begin::Content-->
    <div id="kt_app_content" class="app-content">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-fluid">
            <!--begin::Row-->
            <div class="row g-5 g-xl-10">
                <!--begin::Billing History-->
                <div class="card">
                    <!--begin::Card header-->
                    <div class="card-header card-header-stretch border-bottom border-gray-200">
                        <!--begin::Title-->
                        <div class="card-title">
                            <h3 class="fw-bold m-0">Order Reports</h3>
                        </div>
                        <!--end::Title-->
                        <!--begin::Toolbar-->
                        <div class="card-toolbar m-5">
                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-2 col-form-label required fw-semibold fs-6">Date</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8">
                                    <!--begin::Row-->
                                    <div class="row">
                                        <!--begin::Col-->
                                        <div class="col-lg-12 fv-row">
                                            <input type="text" name="date_picker" id="date_picker"
                                                class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                                placeholder="Datepicker" value="" />
                                        </div>
                                        <!--end::Col-->
                                    </div>
                                    <!--end::Row-->
                                </div>
                                <div class="col-lg-2">
                                    <button type="button" id="filter"
                                        class="btn btn-flex btn-sm fw-bold btn-success px-2 px-md-5 py-3">Filter</button>
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Tab nav-->
                        </div>
                        <!--end::Toolbar-->
                    </div>
                    <!--end::Card header-->
                    <!--begin::Tab Content-->
                    <div class="tab-content">
                        <!--begin::Tab panel-->
                        <div id="kt_billing_months" class="card-body p-0 tab-pane fade show active" role="tabpanel"
                            aria-labelledby="kt_billing_months">
                            <!--begin::Table container-->
                            <div class="table-responsive">
                                <table class="table table-row-bordered align-middle gy-4 gs-9">
                                    <thead
                                        class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bold bg-light bg-opacity-75">
                                        <tr>
                                            <td class="min-w-200px">Date</td>
                                            <td class="min-w-100px">Cash</td>
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
                            <!--end::Table container-->
                        </div>
                        <!--end::Tab panel-->
                    </div>
                    <!--end::Tab Content-->
                </div>
                <!--end::Billing Address-->
            </div>
            <!--end::Row-->
        </div>
        <!--end::Content container-->
    </div>
    <!--end::Content-->
    @push('script')
        <script>
            $(document).ready(function() {
                $("#date_picker").datepicker();

                $('#filter').click(function() {
                    var date_picker = $('#date_picker').val();
                    var shop = 'calisto-co.myshopify.com';

                    $.ajax({
                        url: '/admin/get_reports',
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            shop: shop,
                            date_picker: date_picker,
                        },
                        success: function(data) {
                            var orderdata = '';
                            var cash_total = parseFloat(0).toFixed(2);
                            var visa_total = parseFloat(0).toFixed(2);
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
            });
        </script>
    @endpush
@endsection
