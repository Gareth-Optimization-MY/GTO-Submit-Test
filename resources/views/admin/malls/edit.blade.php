@extends('admin.layouts.app')
@section('title')
    Edit {{ $mall->title }}
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
                    <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
                        data-bs-target="#kt_account_profile_details" aria-expanded="true"
                        aria-controls="kt_account_profile_details">
                        <!--begin::Card title-->
                        <div class="card-title m-0">
                            <h3 class="fw-bold m-0">Edit mall</h3>
                        </div>
                        <!--end::Card title-->
                    </div>
                    <!--begin::Card header-->
                    <!--begin::Content-->
                    <div id="kt_account_settings_profile_details" class="collapse show">
                        <!--begin::Form-->
                        <form id="kt_account_profile_details_form" action="{{ route('malls.update', $mall->id) }}"
                            method="POST" class="form">
                            <!--begin::Card body-->
                            @csrf
                            @method('PUT')
                            <div class="card-body border-top p-9">
                                <!--begin::Input group-->
                                <div class="row mb-6">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 col-form-label required fw-semibold fs-6">Title</label>
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <div class="col-lg-8">
                                        <!--begin::Row-->
                                        <div class="row">
                                            <!--begin::Col-->
                                            <div class="col-lg-12 fv-row">
                                                <input type="text" name="title"
                                                    class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                                    placeholder="Title" value="{{ $mall->title }}" />
                                            </div>
                                            <!--end::Col-->
                                        </div>
                                        <!--end::Row-->
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <div class="row mb-6">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 col-form-label fw-semibold fs-6">Country</label>
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <div class="col-lg-8 fv-row">
                                        <select name="country" aria-label="Select Country" data-control="select2"
                                            data-placeholder="Select Country.."
                                            class="form-select form-select-solid form-select-lg">
                                            <option value="">Select Country..</option>
                                            <option @if ($mall->country == 'MY') selected @endif value="MY">Malaysia</option>
                                            {{-- <option @if ($mall->country == 'SG') selected @endif value="SG">Singapore</option> --}}

                                        </select>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <div class="row mb-6">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 col-form-label fw-semibold fs-6">Template</label>
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <div class="col-lg-8 fv-row">
                                        <select name="template_id" aria-label="Select Template" data-control="select2"
                                            data-placeholder="Select Template.."
                                            class="form-select form-select-solid form-select-lg">
                                            <option value="">Select Template..</option>
                                            @foreach ($templates as $template)
                                                <option @if ($mall->template_id == $template->id) selected @endif
                                                    value="{{ $template->id }}">{{ $template->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <!--end::Col-->
                                </div>
                            </div>
                            <!--end::Card body-->
                            <!--begin::Actions-->
                            <div class="card-footer d-flex justify-content-end py-6 px-9">
                                {{-- <button type="reset" class="btn btn-light btn-active-light-primary me-2">Discard</button> --}}
                                <button type="submit" class="btn btn-primary"
                                    id="kt_account_profile_details_submit">Save</button>
                            </div>
                            <!--end::Actions-->
                        </form>
                        <!--end::Form-->
                    </div>
                    <!--end::Content-->
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
                                <!--begin::Table-->
                                @if ($message = Session::get('success'))
                                    <div class="alert alert-success">
                                        <p>{{ $message }}</p>
                                    </div>
                                @endif
                                <table class="table table-row-bordered align-middle gy-4 gs-9">
                                    <thead
                                        class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bold bg-light bg-opacity-75">
                                        <tr>
                                            <td class="min-w-150px">POS Location</td>
                                            <td class="min-w-250px">Store</td>
                                        </tr>
                                    </thead>
                                    <tbody class="fw-semibold text-gray-600">

                                        @foreach ($mallLocations as $key => $location)
                                            <!--begin::Table row-->
                                            <tr>
                                                <td><a href="/admin/locations/{{ $store->shop_url }}/{{ $key }}">{{ $location->name }}</a></td>
                                                <td>{{ $store->shop_url }}</td>

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
