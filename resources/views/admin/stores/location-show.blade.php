@extends('admin.layouts.app')
@section('title')
    Show {{ $location->name }}
@endsection
@section('content')
{{-- @if(!empty($singleMall) && ($singleMall->id == 4 || $singleMall->id == 5))
<style>
    .showDiv{
        display: none;
    }
</style>
@endif --}}
@php
    function formatString($string) {
        // Replace underscores with spaces
        $string = str_replace('_', ' ', $string);

        // Capitalize the first letter of each word
        $string = ucwords($string);

        return $string;
    }



@endphp
    <!--begin::Content-->
    <div id="kt_app_content" class="app-content">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-fluid">

            <!--begin::Toolbar-->
            <div id="kt_app_toolbar" class="app-toolbar pt-7 pt-lg-10">
                <!--begin::Toolbar container-->
                <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
                    <!--begin::Toolbar wrapper-->
                    <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                        <!--begin::Page title-->

                        <!--end::Actions-->
                    </div>
                    <!--end::Toolbar wrapper-->
                </div>
                <!--end::Toolbar container-->
            </div>
            <!--begin::Row-->
            <div class="row g-5 g-xl-10">

                <!--begin::Basic info-->
                <div class="card mb-5 mb-xl-10">
                    <!--begin::Card header-->
                    <div class="card-header border-0 cursor-pointer">
                        <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                            <!--begin::Title-->
                            <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">
                                {{ $location->name }}</h1>
                            <!--end::Title-->
                            <!--begin::Breadcrumb-->
                            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                                <!--begin::Item-->
                                <li class="breadcrumb-item text-muted">
                                    <a href="/admin" class="text-muted text-hover-primary">Stores</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <span class="bullet bg-gray-400 w-5px h-2px"></span>
                                </li>
                                <li class="breadcrumb-item text-muted">
                                    <a href="/admin/stores/{{ $store->id }}/edit" class="text-muted text-hover-primary">{{$store->shop_url}}</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <span class="bullet bg-gray-400 w-5px h-2px"></span>
                                </li>
                                <li class="breadcrumb-item text-muted">{{ $location->name }}</li>
                                <!--end::Item-->
                            </ul>
                            <!--end::Breadcrumb-->
                        </div>
                        <!--end::Page title-->
                        <!--begin::Actions-->
                        <div class="d-flex align-items-center gap-2 gap-lg-3">
                            <a href="/admin/stores/{{ $store->id }}/edit"
                                class="btn btn-flex btn-primary h-40px fs-7 fw-bold">Back</a>
                        </div>
                    </div>
                    <!--begin::Card header-->
                    <!--begin::Content-->
                    <div id="kt_account_settings_profile_details" class="collapse show">
                        <!--begin::Form-->
                        <form id="kt_account_profile_details_form" action="{{ route('locations.store') }}" method="POST"
                            class="form">
                            <!--begin::Card body-->
                            @csrf
                            <input type="hidden" name="shop" value="{{ $store->shop_url }}">
                            <input type="hidden" name="template_id" id="template_id" value="">
                            <input type="hidden" name="location" value="{{ $location->id }}">
                            <div class="card-body border-top p-9">
                                <!--begin::Input group-->
                                <div class="row mb-6">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 col-form-label fw-semibold fs-6">Mall</label>
                                    <!--end::Label-->
                                    <!--begin::Col-->

                                    <div class="col-lg-8 fv-row">
                                        <input type="hidden" name="mall_id" value="{{ isset($locationData) ? $locationData->mall_id : '' }}">
                                        <input type="text" name="mall" disabled
                                                    class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                                    value="{{ isset($locationData) ? $locationData->mall_name : '' }}" />
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <div class="row mb-6">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 col-form-label fw-semibold fs-6">Connected Via</label>
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <div class="col-lg-8 fv-row">
                                        <img src="/assets/img/shopify-logo.png" alt="shopify-logo" class="img-fluid">
                                        {{-- <img src="/assets/img/eats.png" alt="eats" class="img-fluid"> --}}
                                    </div>
                                    <!--end::Col-->
                                </div>
                                {{-- <hr class="" />
                                <div class="row mb-6">
                                    <h1>Options</h1>
                                </div>
                                <div class="row mb-6 dynamicFields">
                                    @if($locationData)
                                        @php
                                            $fields = json_decode($locationData->ftp_details);
                                        @endphp
                                        @foreach ($fields as $label => $name)

                                            <label class="col-lg-4 col-form-label required fw-semibold fs-6">{{ $label }}</label>
                                            <div class="col-lg-8">
                                                <div class="row">
                                                    <div class="col-lg-12 fv-row">
                                                        <input type="text" name="{{ $name }}"
                                                            class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="{{ $label }}"
                                                            value="{{ $name }}" />
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif

                                </div> --}}
                                @if (isset($locationData))
                                <hr class="showDiv" />
                                <div class="row mb-6 showDiv">
                                    <h1>Variables</h1>
                                </div>
                                <div class="row mb-6 showDiv">
                                    <label class="col-lg-4 col-form-label required fw-semibold fs-6">Cash</label>
                                    <div class="col-lg-8">
                                        <div class="row">
                                            <div class="col-lg-12 fv-row">
                                                <input type="text" name="cash"
                                                    class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                                    value="{{ isset($locationData->variables) ? $locationData->variables->cash : $locationData->cash }}" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-6 showDiv">
                                    <label class="col-lg-4 col-form-label required fw-semibold fs-6">TNG</label>
                                    <div class="col-lg-8">
                                        <div class="row">
                                            <div class="col-lg-12 fv-row">
                                                <input type="text" name="tng"
                                                    class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                                    value="{{ isset($locationData->variables) ? $locationData->variables->tng : $locationData->tng }}" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-6 showDiv">
                                    <label class="col-lg-4 col-form-label required fw-semibold fs-6">Visa</label>
                                    <div class="col-lg-8">
                                        <div class="row">
                                            <div class="col-lg-12 fv-row">
                                                <input type="text" name="visa"
                                                    class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                                    value="{{ isset($locationData->variables) ? $locationData->variables->visa : $locationData->visa }}" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-6 showDiv">
                                    <label class="col-lg-4 col-form-label required fw-semibold fs-6">MasterCard</label>
                                    <div class="col-lg-8">
                                        <div class="row">
                                            <div class="col-lg-12 fv-row">
                                                <input type="text" name="mastercard"
                                                    class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                                    value="{{ isset($locationData->variables) ? $locationData->variables->master_card : $locationData->master_card }}" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-6 showDiv">
                                    <label class="col-lg-4 col-form-label required fw-semibold fs-6">Amex</label>
                                    <div class="col-lg-8">
                                        <div class="row">
                                            <div class="col-lg-12 fv-row">
                                                <input type="text" name="amex"
                                                    class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                                    value="{{ isset($locationData->variables) ? $locationData->variables->amex : $locationData->amex }}" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-6 showDiv">
                                    <label class="col-lg-4 col-form-label required fw-semibold fs-6">Voucher</label>
                                    <div class="col-lg-8">
                                        <div class="row">
                                            <div class="col-lg-12 fv-row">
                                                <input type="text" name="voucher"
                                                    class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                                    value="{{ isset($locationData->variables) ? $locationData->variables->vouchers : $locationData->vouchers }}" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-6 showDiv">
                                    <label class="col-lg-4 col-form-label required fw-semibold fs-6">Others</label>
                                    <div class="col-lg-8">
                                        <div class="row">
                                            <div class="col-lg-12 fv-row">
                                                <input type="text" name="others"
                                                    class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                                    value="{{ isset($locationData->variables) ? $locationData->variables->others : $locationData->others }}" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <hr class="" />
                                <div class="row mb-6">
                                    <h1>Transfer</h1>
                                </div>
                                @php
                                    if(isset($locationData)){
                                        $ftpDetails = json_decode($locationData->ftp_details);
                                    }else{
                                        $ftpDetails = [];
                                    }
                                @endphp

                                @foreach ($ftpDetails as $label => $value)


                                <div class="row mb-6">
                                    <label class="col-lg-4 col-form-label required fw-semibold fs-6">{{formatString($label)}}</label>
                                    <div class="col-lg-8">
                                        <div class="row">
                                            <div class="col-lg-12 fv-row">
                                                <input type="text" name="{{$label}}"
                                                    class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                                    value="{{ $value }}" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach

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
        </div>
        <!--end::Content container-->
    </div>
    <!--end::Content-->
    @push('script')
        <script>
            $('.showDiv').hide();

            @if(!empty($locationData))
                @if($locationData->template_id == 4 || $locationData->template_id == 5)
                    $('.showDiv').show();
                //     $('#kt_account_profile_details_submit').attr('disabled',true);
                @endif
            @endif
            $('#mall_id').change(function() {
                var mall_id = $(this).val();
                if (mall_id != '') {
                    $.ajax({
                        url: '/get_template_by_mall?id=' + mall_id,
                        type: 'get',
                        success: function(result) {
                            var fields = JSON.parse(result.fields);
                            $('#template_id').val(result.id);
                            console.log(result.id);
                            var data = '';
                            if (result.id == 4 || result.id == 5) {

                                fields.map((field, index) => {
                                    if (field.type === "number") {
                                        data += "<div class='row mb-6'>";
                                        data +=
                                            "<label class='col-lg-4 col-form-label required fw-semibold fs-6'>" +
                                            field.label + "</label>";
                                        data += "<div class='col-lg-8'>";
                                        data += "<div class='row'>";
                                        data += "<div class='col-lg-12 fv-row'>";
                                        data +=
                                            "<input type='number' name='" + field.name +
                                            "' class='form-control form-control-lg form-control-solid mb-3 mb-lg-0' placeholder='" +
                                            field.label + "' value='' />";
                                        data += "</div>";
                                        data += "</div>";
                                        data += "</div>";
                                        data += "</div>";
                                    }
                                })
                                $('.dynamicFields').html(data);
                                $('#kt_account_profile_details_submit').removeAttr('disabled');
                                $('.showDiv').show();

                            }
                            else
                            {
                                // $('#kt_account_profile_details_submit').attr('disabled',true);
                                $('.showDiv').hide();
                            }
                        },
                        error: function() {
                            alert('Error occurred while filtering data.');
                        }
                    });
                } else {

                    $('.showDiv').hide();
                    $('.dynamicFields').html('data');
                }
            });
        </script>
    @endpush
@endsection
