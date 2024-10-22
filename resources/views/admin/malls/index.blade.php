@extends('admin.layouts.app')
@section('title')
    Malls
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
                    <!--begin::Card header-->
                    <div class="card-header card-header-stretch border-bottom border-gray-200">
                        <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                            <!--begin::Title-->
                            <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">
                                Malls</h1>
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
                                <li class="breadcrumb-item text-muted">Malls</li>
                                <!--end::Item-->
                            </ul>
                            <!--end::Breadcrumb-->
                        </div>
                        <div class="d-flex align-items-center gap-2 gap-lg-3">
                            <a href="{{ route('malls.create') }}"
                                class="btn btn-flex btn-success h-40px fs-7 fw-bold">Create Mall</a>
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
                                            <td class="min-w-250px">Title</td>
                                            <td class="min-w-150px">Country</td>
                                            <td class="min-w-150px">Template</td>
                                            <td></td>
                                        </tr>
                                    </thead>
                                    <tbody class="fw-semibold text-gray-600">

                                    </tbody>
                                </table>
                                <!--end::Table-->
                            </div>
                            <!--end::Table container-->
                        </div>

                        {{-- <div class="text-center">
                            {{ $malls->links('pagination::bootstrap-5') }}
                        </div> --}}
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
        url: "{{ route('malls.index') }}",
        type: 'GET'
    },
    columns: [
        {
            data: 'id',
            name: 'id',
            render: function(data, type, row) {
                return `<a href="/admin/malls/${data}/edit">${data}</a>`;
            }
        },
        {
            data: 'title',
            name: 'title'
        },
        {
            data: 'country',
            name: 'country'
        },
        {
            data: 'template_name',
            name: 'template_name'
        },
        {
            data: 'action',
            name: 'action',
            orderable: false,
            searchable: false,
            render: function(data, type, row) {
                let actionButtons = `
                    <a href="${data.edit_url}" target="_blank">
                        <button class="btn btn-primary">Edit</button>
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

            });
        </script>
    @endpush
@endsection
