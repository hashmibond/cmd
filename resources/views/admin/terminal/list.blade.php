@extends('admin.layouts.master')

@section('content')

    <div class="page-heading row">
        <div class="col-md-6">  <h1 class="page-title">Terminal</h1></div>
            <div class="col-md-6"><a class="btn btn-info float-right mt-4" href="{{ route('terminals.create') }}" > <i class="fa fa-plus"></i> Create</a></div>
    </div>

    <div class="page-content fade-in-up">
        <div class="row">
            <div class="col-md-12">

                <div class="ibox">
                    <div class="ibox-head">
                        <div class="ibox-title">List</div>
                        <div class="ibox-tools">
                            <a class="ibox-collapse"><i class="fa fa-minus"></i></a>
                            <a class="fullscreen-link"><i class="fa fa-expand"></i></a>
                        </div>
                    </div>
                    <div class="ibox-body">

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="item-table">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Reg.No</th>
                                    <th>IMEI</th>
                                    <th>Status</th>
                                    <th class="text-center">Updated At</th>
                                    <th class="text-center">Reg.Status</th>
                                    <th class="text-center">Approved At</th>
                                    <th>Address</th>
                                    <th>User</th>
                                    <th style="border-right: 1px solid #ddd!important;">Actions</th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>



            </div>
        </div>
    </div>

@stop

@push('scripts')
    <script type="text/javascript">
        $(function() {
            $('#item-table').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                "ajax": {
                    "url"   : '{{ route('terminalsDatatable')}}',
                    "type"  : 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },

                "columnDefs": [
                    {"className": "text-center", "targets": "_all", orderable: false }
                ],
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: true, searchable: false },
                    { data: 'reg_no', name: 'reg_no' },
                    { data: 'imei', name: 'imei' },
                    { data: 'status', name: 'status', searchable : false },
                    { data: 'status_updated_at', name: 'status_updated_at'},
                    { data: 'is_approved', name: 'is_approved', searchable : false },
                    { data: 'approved_at', name: 'approved_at', searchable : false },
                    { data: 'allocate_place', name: 'allocate_place'},
                    { data: 'userName', name: 'userName', searchable : false },
                    { data: 'actions', name: 'actions', searchable : false },
                ]
            });
        })
    </script>
@endpush
