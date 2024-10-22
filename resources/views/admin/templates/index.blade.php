@extends('admin.layouts.app')
@section('title')
    Templates
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
                        <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                            <!--begin::Title-->
                            <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">
                                Templates</h1>
                            <!--end::Title-->
                            <!--begin::Breadcrumb-->
                            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                                <!--begin::Item-->
                                <li class="breadcrumb-item text-muted">
                                    <a href="/admin" class="text-muted text-hover-primary">Dashboard</a>
                                </li>
                                <!--end::Item-->
                                <!--begin::Item-->
                                <li class="breadcrumb-item">
                                    <span class="bullet bg-gray-400 w-5px h-2px"></span>
                                </li>
                                <!--end::Item-->
                                <!--begin::Item-->
                                <li class="breadcrumb-item text-muted">Templates</li>
                                <!--end::Item-->
                            </ul>
                            <!--end::Breadcrumb-->
                        </div>
                    </div>
                    <!--end::Card header-->
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
                                <table class="table table-row-bordered align-middle gy-4 gs-9 datatable">
                                    <thead
                                        class="border-bottom border-gray-200 fs-6 text-gray-600 fw-bold bg-light bg-opacity-75">
                                        <tr>
                                            <td class="min-w-150px">ID</td>
                                            <td class="min-w-250px">Name</td>
                                            <td class="min-w-150px">No of Malls</td>
                                        </tr>
                                    </thead>
                                    <tbody class="fw-semibold text-gray-600">

                                        @foreach ($templates as $template)
                                            <!--begin::Table row-->
                                            <tr>
                                                <td>{{ $template->id }}</td>
                                                <td>{{ $template->name }}</td>
                                                <td>
                                                    @php
                                                        $mallCount = \App\Models\Mall::where('template_id', $template->id)->count();
                                                    @endphp
                                                    {{ $mallCount }}
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
    @push('script')
        <script>
            $(document).ready(function() {
                $('.datatable').DataTable({
                    "dom": 'frtip<l>'
                });
            });
        </script>
    @endpush
@endsection
