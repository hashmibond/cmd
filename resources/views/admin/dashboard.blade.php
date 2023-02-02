@extends('admin.layouts.master')

@section('content')

    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-head row">
                <div class="ibox-title col-sm-3">Terminal Data</div>
                <form action="{{ route('Dashboard') }}" class="col-sm-3 text-right p-2 ">
                    <div class="form-group ">
                        <button class="btn btn-info" type="submit" name="download">Download</button>
                    </div>
                </form>
            </div>
            <div class="ibox-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="item-table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Reg.No</th>
                        <th>Terminal Data</th>
                        <th>Shutter</th>
                        <th>Smoke</th>
                        <th>Gas</th>
                        <th>Motion</th>
                        <th style="border-right: 1px solid #ddd!important;">Received At</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
                </div>
            </div>
        </div>

    </div>

@endsection
@push('scripts')
    <script type="text/javascript">
        $(function() {
            $('#item-table').DataTable({
                "pageLength": 100,
                processing: true,
                serverSide: true,
                scrollX: true,
                "ajax": {
                    "url"   : '{{ route('receivedDatatable')}}',
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
                    { data: 'terminal_data', name: 'terminal_data', searchable : false },
                    { data: 'shutter_sensor_status', name: 'shutter_sensor_status', searchable : false},
                    { data: 'smoke_sensor_status', name: 'smoke_sensor_status', searchable : false },
                    { data: 'gas_sensor_status', name: 'gas_sensor_status',searchable : false},
                    { data: 'motion_sensor_status', name: 'motion_sensor_status', searchable : false },
                    { data: 'created_at', name: 'approved_at', searchable : false },
                ]
            });
        })
    </script>
@endpush

