@extends('admin.layouts.app')
@section('title')
    Show
@endsection
@section('content')
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
                                {{ $store->shop_url }}</h1>
                            <!--end::Title-->
                            <!--begin::Breadcrumb-->
                            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                                <!--begin::Item-->
                                <li class="breadcrumb-item text-muted">
                                    <a href="/admin" class="text-muted text-hover-primary">Stores</a>
                                </li>
                                <!--end::Item-->
                                <!--begin::Item-->
                                <li class="breadcrumb-item">
                                    <span class="bullet bg-gray-400 w-5px h-2px"></span>
                                </li>
                                <!--end::Item-->
                                <!--begin::Item-->
                                <li class="breadcrumb-item text-muted">{{ $store->shop_url }}</li>
                                <!--end::Item-->
                            </ul>
                            <!--end::Breadcrumb-->
                        </div>
                        <!--end::Page title-->
                        <!--begin::Actions-->
                        <div class="d-flex align-items-center gap-2 gap-lg-3">

                            <a href="/admin/stores" class="btn btn-flex btn-primary h-40px fs-7 fw-bold">Back</a>
                        </div>
                    </div>
                    <!--begin::Card header-->
                </div>
                <!--end::Basic info-->

            </div>
            <!--end::Row-->
            <div class="row g-5 g-xl-10">
                <!--begin::Billing History-->
                <div class="card">
                    <!--begin::Card header-->
                    <div class="card-header card-header-stretch border-bottom border-gray-200">

                        <!--begin::Title-->
                        <div class="card-title">
                            <h3 class="fw-bold m-0">POS Locations</h3>
                        </div>
                        <!--end::Title-->
                        <!--begin::Toolbar-->
                        <div class="card-toolbar m-5">
                            {{-- <a href="{{ route('reports.create') }}" class="btn btn-flex btn-sm fw-bold btn-success px-2 px-md-5 py-3">Create Report</a> --}}
                            <!--end::Tab nav-->
                        </div>
                        <!--end::Toolbar-->
                    </div>
                    <!--begin::Tab Content-->
                    <div class="tab-content">
                        <!--begin::Tab panel-->
                        <div id="kt_billing_months" class="card-body p-0 tab-pane fade show active" role="tabpanel"
                            aria-labelledby="kt_billing_months">
                            <!--begin::Table container-->
                            <div class="table-responsive">
                                <table class="table table-row-bordered align-middle gy-4 gs-9">
                                    {{-- <thead
                                        class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bold bg-light bg-opacity-75">
                                        <tr>
                                            <td class="min-w-150px">POS Location</td>
                                        </tr>
                                    </thead> --}}
                                    <tbody class="fw-semibold text-gray-600">

                                        @foreach ($locations as $location)
                                            @php
                                                $plan = \App\Models\Subscription::where("shop",$store->shop_url)->where("location",$location->id)->first();

                                            @endphp
                                            <tr onclick="location.href='/admin/locations/{{ $store->shop_url }}/{{ $location->id }}';">
                                                <td><a href="/admin/locations/{{ $store->shop_url }}/{{ $location->id }}">{{ $location->name }}</a>
                                                </td>
                                                <td>
                                                    @if ($plan?->subscribe == 'premium' && $plan?->status == 1)
                                                    <span class="badge text-white bg-success">Premium</span>
                                                    @else
                                                    <span class="badge text-white bg-primary">Free</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <!--end::Table row-->
                                        @endforeach
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
@endsection
