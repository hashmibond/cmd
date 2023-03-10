@extends('admin.layouts.master')

@section('content')
    <div class="page-heading row">
        <div class="col-md-6">  <h1 class="page-title">User</h1></div>
            <div class="col-md-6"><a class="btn btn-info float-right mt-4" href="{{ route('users.create') }}" > <i class="fa fa-plus"></i> Create</a></div>
    </div>

    <div class="page-content fade-in-up">
        <div class="row">
            <div class="col-md-12">
                <div class="ibox">
                    <div class="ibox-head">
                        <div class="ibox-title">Filter</div>
                        <div class="ibox-tools">
                            <a class="ibox-collapse"><i class="fa fa-minus"></i></a>
                            <a class="fullscreen-link"><i class="fa fa-expand"></i></a>
                        </div>
                    </div>
                    <div class="ibox-body">
                        <form action="{{ route('users.index') }}">
                            <div class="row">
                                <div class="col-sm-4 form-group">
                                    <label>Start Date : </label>
                                    <div class="input-group date">
                                        <span class="input-group-addon bg-white"><i class="fa fa-calendar"></i></span>
                                        <input class="form-control date" type="text" name="StartDate" placeholder="Start Date" value="{{ $StartDate }}">
                                    </div>
                                </div>
                                <div class="col-sm-4 form-group">
                                    <label>End Date : </label>
                                    <div class="input-group date">
                                        <span class="input-group-addon bg-white"><i class="fa fa-calendar"></i></span>
                                        <input class="form-control date" type="text" name="EndDate" placeholder="End Date" value="{{ $EndDate }}">
                                    </div>
                                </div>

                                <div class="col-sm-4 form-group" >
                                    <label>Terminals: </label>
                                    <select name="userId" class="form-control" id="userId">
                                        <option value="">All</option>
                                        @foreach ($userList as $user)
                                            <option value="{{ $user->id }}" {{ (request('userId') == $user->id) ? 'selected' : '' }} >{{ $user->name }} {{--({{ $terminal->customer->CustomerName }})--}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                {{--<div class="col-sm-3 form-group" >
                                    <label>Terminals: </label>
                                    <select name="regStatus" class="form-control" id="terminalId">
                                        <option value="">All</option>
                                        @foreach ($terminalList as $terminal)
                                            <option value="{{ $terminal->id }}" {{ (request('terminalId') == $terminal->id) ? 'selected' : '' }} >{{ $terminal->reg_no }} ({{ $terminal->customer->CustomerName }})</option>
                                        @endforeach
                                    </select>
                                </div>--}}
                                <div class="form-group col-sm-6 text-left ">
                                    <button class="btn btn-info d-none" type="submit" name="download">Download</button>
                                </div>
                                <div class="form-group col-sm-6 text-right" style="float: right!important;">
                                    <a class="btn btn-outline-default" href="{{ route('users.index') }}" type="submit">Refresh</a>
                                    <button class="btn btn-danger" type="submit">Search</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
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
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Address</th>
                                    <th class="text-center">Created At</th>
                                    {{--<th>Image</th>--}}
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
            $('.date').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd',
            });
            $("#userId").select2();
            $('#item-table').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                "ajax": {
                    "url"   : '{{ route('usersDatatable')}}',
                    "type"  : 'POST',
                    "data"  : {
                        StartDate : '{{ request('StartDate') }}',
                        EndDate : '{{ request('EndDate') }}',
                        userId : '{{ request('userId') }}'
                    },
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },

                "columnDefs": [
                    {"className": "text-center", "targets": "_all", orderable: false }
                ],
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: true, searchable: false },
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'phone', name: 'phone' },
                    { data: 'address', name: 'address'},
                    { data: 'created_at', name: 'created_at', searchable : false },
                    /*{ data: 'image', name: 'image'},*/
                    { data: 'actions', name: 'actions', searchable : false },
                ]
            });
        })

        // Delete record
        $('#item-table').on('click','.deleteUser',function(){
            var id = $.trim($(this).data('id'));
            console.log($.trim(id));
            var deleteConfirm = confirm("Are you sure?");
            if (deleteConfirm == true) {
                // AJAX request
                $.ajax({
                    url: "terminals/destroy/",
                    type: 'get',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: {
                        id: id,
                        /*_method: 'DELETE'*/
                    },
                    dataType: 'json',
                    success: function(response){
                        if(response.success == 1){
                            alert("Record deleted.");

                            // Reload DataTable
                            empTable.ajax.reload();
                        }else{
                            alert("Invalid ID.");
                        }
                    }
                });
            }

        });

    </script>
@endpush
