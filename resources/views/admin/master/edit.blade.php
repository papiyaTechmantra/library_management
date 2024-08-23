@extends('admin.layout.app')
@section('page-title', 'Update new admin')

@section('section')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row mb-3">
                            <div class="col-md-12 text-right">
                                <a href="{{ route('admin.user_management.list.all') }}" class="btn btn-sm btn-primary"> <i class="fa fa-chevron-left"></i> Back</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.user_management.update') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row form-group">
                                <div class="col-md-12">
                                    <label for="name">Name *</label>
                                    <input type="text" class="form-control" name="name" id="name" placeholder="Enter name.." value="{{ $data->name }}">
                                    @error('name') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="email">Email *</label>
                                    <input type="email" class="form-control" name="email" id="email" placeholder="Enter email.." value="{{ $data->email }}">
                                    @error('email') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="mobile">Mobile *</label>
                                    <input type="number" class="form-control" name="mobile" id="mobile" placeholder="Enter mobile number.." value="{{ $data->mobile_no }}">
                                    @error('mobile') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <label for="username">User Name *</label>
                                    <input type="text" class="form-control" name="username" id="username" placeholder="Enter username.." value="{{ $data->username }}">
                                    @error('username') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="password">Password *</label>
                                    <input type="password" class="form-control" name="password" id="password" placeholder="Enter password.." value="{{ old('password') }}">
                                    @error('password') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="password_confirmation">Confirm Password *</label>
                                    <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="Confirm password.." value="{{ old('password_confirmation') }}">
                                    @error('password_confirmation') <p class="small text-danger">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            <input type="hidden" name="id" value="{{ $data->id }}">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

