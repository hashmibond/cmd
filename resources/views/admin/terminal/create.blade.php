@extends('admin.layouts.master')

@push('styles')

@endpush

@section('content')

    <div class="page-heading row">
        <div class="col-md-6">  <h1 class="page-title">Terminal</h1></div>
        <div class="col-md-6"><a class="btn btn-default float-right mt-4" href="{{ route('terminals.index') }}" > <i class="fa fa-arrow-left"></i> Back</a></div>
    </div>

    <div class="page-content fade-in-up">
        <div class="row">
            <div class="col-md-12">

                <div class="ibox">
                    <div class="ibox-head">
                        <div class="ibox-title">Create</div>
                        <div class="ibox-tools">
                            <a class="ibox-collapse"><i class="fa fa-minus"></i></a>
                            <a class="fullscreen-link"><i class="fa fa-expand"></i></a>
                        </div>
                    </div>
                    <div class="ibox-body">
                        <form class="form-horizontal" action="{{ route('terminals.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="form-group col-md-6 row  @error('reg_no') has-error @enderror ">
                                    <label class="col-sm-4 col-form-label">Terminal Reg.No<span class="text-danger">*</span> </label>
                                    <div class="col-sm-8">
                                        <input class="form-control" type="number" name="reg_no" value="{{ old('reg_no') }}" placeholder="Enter Registration No">
                                        @error('reg_no')
                                        <span class="help-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror

                                    </div>
                                </div>

                                <div class="form-group col-md-6 row  @error('imei') has-error @enderror ">
                                    <label class="col-sm-4 col-form-label">Terminal IMEI.No<span class="text-danger">*</span> </label>
                                    <div class="col-sm-8">
                                        <input class="form-control" type="number" name="imei" value="{{ old('imei') }}" placeholder="Enter IMEI No">
                                        @error('imei')
                                        <span class="help-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror

                                    </div>
                                </div>
                            </div>

                            <div class="form-group row justify-content-md-center">
                                <div class="col-sm-2">
                                    <button class="btn btn-info btn-block pointer" type="submit">Create</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

{{--@push('scripts')
    <script>
        $('.date').datepicker({ autoclose: true });
    </script>
@endpush--}}
