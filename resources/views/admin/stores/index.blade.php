@extends('admin.layouts.app')
@section('title')
    Stores
@endsection
@section('content')
    @push('style')
        <style>
            div.dataTables_wrapper div.dataTables_processing {
                top: 88%;
            }
        </style>
    @endpush
    <!--begin::Content-->
    <div id="kt_app_content" class="app-content">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-fluid">

            <!--begin::Row-->
            <div class="row g-5 g-xl-10 mt-1">
                <!--begin::Billing History-->
                <div class="card">
                    <!--begin::Card header-->
                    <!--end::Card header-->
                    <div class="card-header card-header-stretch border-bottom border-gray-200">
                        <!--begin::Title-->
                        <!--begin::Page title-->
                        <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                            <!--begin::Title-->
                            <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">
                                Stores</h1>
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
                                <li class="breadcrumb-item text-muted">Stores</li>
                                <!--end::Item-->
                            </ul>
                            <!--end::Breadcrumb-->
                        </div>
                        <!--end::Page title-->
                        <div class="card-toolbar flex-row-fluid justify-content-end gap-5 mt-3">
                            <div class="w-100 mw-150px">
                                <!--begin::Select2-->
                                <select class="form-select form-select-solid " name="status" id="status">
                                    <option @if (isset($_GET['status']) && $_GET['status'] == 'all') selected @endif value="all">All</option>
                                    <option @if (isset($_GET['status']) && $_GET['status'] == 'installed') selected @endif value="installed">Installed
                                    </option>
                                    <option @if (isset($_GET['status']) && $_GET['status'] == 'deleted') selected @endif value="deleted">Deleted
                                    </option>
                                </select>
                                <!--end::Select2-->
                            </div>
                        </div>
                        <!--end::Title-->
                        <!--end::Toolbar-->
                    </div>
                    <!--end::Card header-->
                    <!--begin::Tab Content-->
                    <div class="tab-content">
                        <!--begin::Tab panel-->
                        <div class="card-body p-0 tab-pane fade show active" role="tabpanel">
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
                                            {{-- <td class="min-w-250px">Pricing plan</td> --}}
                                            <td class="min-w-150px">Status</td>
                                            {{-- <td class="min-w-150px"></td> --}}
                                        </tr>
                                    </thead>
                                    <tbody class="fw-semibold text-gray-600">
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
                    "dom": 'frtip<l>',
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('stores.index') }}",
                        type: 'GET'
                    },
                    columns: [{
                            data: 'id',
                            name: 'id',
                            render: function(data, type, row) {
                                return  `<a href="/admin/stores/${data}/edit">${data}</a>`;
                            }
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        // {
                        //     data: 'plan',
                        //     name: 'plan'
                        // },
                        {
                            data: 'status',
                            name: 'status',
                            orderable: false,
                            searchable: false,
                            render: function(data, type, row) {
                                if(data == 'disabled'){
                                    var status = `<span style="color:red;background-color:#ffcccb;padding:10px;border-radius:5px">Disabled</span>`;
                                }
                                else{
                                    var status = `<span style="color:green;background-color:#90ee90;padding:10px;border-radius:5px">Active</span>`;
                                }
                                return status;
                            }
                        }
                    ]
                });
            });
            $('#status').change(function() {
                var status = $('select[name="status"] option:selected').val();
                var redirectURL = window.location.origin + window.location.pathname + "?status=" + status;
                window.location = redirectURL;
            });
        </script>
    @endpush
@endsection
