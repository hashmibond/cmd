@extends('admin.layouts.master')

@section('content')

    <div class="page-heading row">
        <div class="col-md-6">  <h1 class="page-title">Terminal</h1></div>
        <div class="col-md-6"><a class="btn btn-default float-right mt-4" href="{{ route('users.index') }}" > <i class="fa fa-arrow-left"></i> Back</a></div>
    </div>

    <div class="page-content fade-in-up">
        <div class="row">
            <div class="col-md-12">

                <div class="ibox">
                    <div class="ibox-head">
                        <div class="ibox-title">Edit</div>
                        <div class="ibox-tools">
                            <a class="ibox-collapse"><i class="fa fa-minus"></i></a>
                            <a class="fullscreen-link"><i class="fa fa-expand"></i></a>
                        </div>
                    </div>
                    <div class="ibox-body">
                        <form class="form-horizontal" action="{{ route('users.update',$user->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="user_id" value="{{$user->id}}">
                            <div class="row">
                                <div class="form-group col-md-6 row  @error('name') has-error @enderror ">
                                    <label class="col-sm-4 col-form-label">Name<span class="text-danger">*</span> </label>
                                    <div class="col-sm-8">
                                        <input class="form-control" type="text" name="name" value="{{ old('name')?old('name'):$user->name }}" placeholder="Enter Name">
                                        @error('name')
                                        <span class="help-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror

                                    </div>
                                </div>

                                <div class="form-group col-md-6 row  @error('email') has-error @enderror d-none">
                                    <label class="col-sm-4 col-form-label">Email </label>
                                    <div class="col-sm-8">
                                        <input class="form-control" type="email" name="email" value="{{ $user->email }}" placeholder="Enter Email">
                                        @error('email')
                                        <span class="help-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror

                                    </div>
                                </div>

                                <div class="form-group col-md-6 row  @error('phone') has-error @enderror ">
                                    <label class="col-sm-4 col-form-label">Phone<span class="text-danger">*</span> </label>
                                    <div class="col-sm-8">
                                        <input class="form-control" type="number" name="phone" value="{{ old('phone')?old('phone'):$user->phone }}" placeholder="Enter Phone No">
                                        @error('phone')
                                        <span class="help-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror

                                    </div>
                                </div>
                                <div class="form-group col-md-6 row  @error('role_id') has-error @enderror d-none">
                                    <label class="col-sm-4 col-form-label">Role<span class="text-danger">*</span> </label>
                                    <div class="col-sm-8">
                                        <select name="role_id" class="form-control select_item_list" id="role_id">
                                            <option value="">Select one</option>
                                            {{--@foreach ($Roles as $Role)
                                                <option value="{{ $Role->RoleID }}"  {{ $Role->RoleID == old('role_id') ? 'selected' : '' }} >{{ $Role->RoleName }}</option>
                                            @endforeach--}}
                                        </select>
                                        @error('role_id')
                                        <span class="help-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group col-md-6 row  @error('address') has-error @enderror  d-none">
                                    <label class="col-sm-4 col-form-label">Address </label>
                                    <div class="col-sm-8">
                                        <input class="form-control" type="text" name="address" value="{{ $user->address }}" placeholder="Enter Address">
                                        @error('address')
                                        <span class="help-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror

                                    </div>
                                </div>

                                <div class="form-group col-md-6 row  @error('password') has-error @enderror ">
                                    <label class="col-sm-4 col-form-label">Password<span class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <input class="form-control" type="text" name="password" value="">
                                        @error('password')
                                        <span class="help-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror

                                    </div>
                                </div>

                                <div class="form-group col-md-6 row  @error('password_confirmation') has-error @enderror ">
                                    <label class="col-sm-4 col-form-label">Confirm Password<span class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <input class="form-control " type="text" name="password_confirmation" value="">
                                        @error('password_confirmation')
                                        <span class="help-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror

                                    </div>
                                </div>

                                <div class="form-group col-md-6 row  {{--@error('terminalId') has-error @enderror--}} d-none">
                                    <label class="col-sm-4 col-form-label">Terminal Reg.No</label>
                                    <div class="col-sm-8">
                                        <select name="terminalId" class="form-control select_item_list" id="terminalId">
                                            <option value="">Select one</option>
                                            {{--@foreach ($terminalList as $terminal)
                                                <option value="{{ $terminal->id }}"  {{ array_key_exists($userTerminal,$terminal->id) ? 'selected' : '' }} >{{ $terminal->reg_no }}</option>
                                            @endforeach--}}
                                        </select>
                                        {{--@error('terminalId')
                                        <span class="help-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror--}}
                                    </div>
                                </div>

                                <div class="form-group col-md-6 row  @error('allocate_place') has-error @enderror d-none">
                                    <label class="col-sm-4 col-form-label">Terminal Address</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" type="text" name="allocate_place" value="{{ old('allocate_place') }}" placeholder="Enter Terminal Address">
                                        @error('allocate_place')
                                        <span class="help-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror

                                    </div>
                                </div>
                            </div>

                            <div class="form-group row justify-content-md-center">
                                <div class="col-sm-2">
                                    <button class="btn btn-info btn-block pointer" type="submit">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop


