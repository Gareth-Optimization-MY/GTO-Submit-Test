@extends('admin.layouts.app')
@section('title')
    Reports
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
            <div class="row g-5 g-xl-10">
                <!--begin::Billing History-->
                <div class="card">
                    <!--end::Card header-->
                    <div class="card-header card-header-stretch border-bottom border-gray-200">

                        <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                            <!--begin::Title-->
                            <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">
                                Reports</h1>
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
                                <li class="breadcrumb-item text-muted">Reports</li>
                                <!--end::Item-->
                            </ul>
                            <!--end::Breadcrumb-->
                        </div>
                        <!--begin::Title-->
                        <div class="card-toolbar flex-row-fluid justify-content-end gap-5 mt-3">
                            <div class="w-100 mw-150px">
                                <!--begin::Select2-->
                                <select class="form-select form-select-solid " name="type" id="type">
                                    <option @if (isset($_GET['type']) && $_GET['type'] == 'all') selected @endif value="all">All</option>
                                    <option @if (isset($_GET['type']) && $_GET['type'] == 'single_day_only') selected @endif value="single_day_only">Single
                                        Report</option>
                                    <option @if (isset($_GET['type']) && $_GET['type'] == 'date_range') selected @endif value="date_range">Bulk Report
                                    </option>
                                    <option @if (isset($_GET['type']) && $_GET['type'] == 'schedule') selected @endif value="schedule">Schedule
                                    </option>
                                    <option @if (isset($_GET['type']) && $_GET['type'] == 'schedule_cron') selected @endif value="schedule_cron">Schedule
                                        Report
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
                                            <td class="min-w-100px">ID</td>
                                            <td class="min-w-100px">Mall Name</td>
                                            <td class="min-w-100px">Location</td>
                                            <td class="min-w-100px">Report Type</td>
                                            <td class="min-w-100px">Report Date</td>
                                            <td class="min-w-200px">Files</td>
                                            <td class="min-w-100px">Store Name</td>
                                            <td class="min-w-200px">Action</td>
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
                var table = $('.datatable').DataTable({
                    "dom": 'frtip<l>',
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('reports.index') }}",
                        type: 'GET',
                        data: function(d) {
                            d.type = $('#type').val();
                        }
                    },
                    columns: [{
                            data: 'id',
                            name: 'id',
                            render: function(data, type, row) {
                                return `<a href="/admin/reports/${data}">${data}</a>`;
                            }
                        },
                        {
                            data: 'mall',
                            name: 'mall'
                        },
                        {
                            data: 'location',
                            name: 'location'
                        },
                        {
                            data: 'report_type',
                            name: 'report_type'
                        },
                        {
                            data: 'report_date',
                            name: 'report_date'
                        },
                        // Render the filenames
                        {
                            data: 'filenames',
                            name: 'filenames',
                            render: function(data, type, row) {
                                let files = '';
                                data.forEach(file => {
                                    files +=
                                        `<a href="${file.url}" target="_blank" rel="noopener noreferrer">${file.name}</a><br/>`;
                                });
                                return files;
                            }
                        },
                        {
                            data: 'shop',
                            name: 'shop'
                        },
                        // Render the action buttons
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                            render: function(data, type, row) {
                                let actionButtons = `
                    <a href='${data.download_url}' target="_blank">
                        <button class="btn btn-primary">Download</button>
                    </a>
                    <form action="${data.delete_url}" method="Post" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                `;
                                return actionButtons;
                            }
                        }
                    ]
                });

                $('#type').change(function() {
                    table.ajax.reload();
                });
            });
        </script>

        {{-- <script>
            $(document).ready(function() {
                $('.datatable').DataTable({
                    "dom": 'frtip<l>',
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('reports.index') }}",
                        type: 'GET',
                    },

                });

            });
            $('#type').change(function() {
                var type = $('select[name="type"] option:selected').val();
                var redirectURL = window.location.origin + window.location.pathname + "?type=" + type;
                window.location = redirectURL;
            });
        </script> --}}
    @endpush
@endsection
