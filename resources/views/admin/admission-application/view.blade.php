@extends('admin.layout.app')
@section('page-title', 'Admission application Details')

@section('section')

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row mb-3">
                            <div class="col-md-12 text-right">
                                <a href="{{ route('admin.admission.application.list') }}" class="btn btn-sm btn-primary"> <i class="fa fa-chevron-left"></i> Back</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-xl-3 col-lg-4 col-sm-6 col-12">
                                    <label>Student Name:</label>
                                    <p>{{$data->name}}</p>
                                </div>
                                <div class="col-xl-3 col-lg-4 col-sm-6 col-12">
                                    <label>Date of Birth:</label>
                                    <p>{{$data->dob}}</p>
                                </div>
                                <div class="col-xl-3 col-lg-4 col-sm-6 col-12">
                                    <label>Mobile:</label>
                                    <p>{{$data->mobile}}</p>
                                </div>
                                <div class="col-xl-3 col-lg-4 col-sm-6 col-12">
                                    <label>Email:</label>
                                    <p>{{$data->email}}</p>
                                </div>
                                <div class="col-xl-3 col-lg-4 col-sm-6 col-12">
                                    <label>Parent name:</label>
                                    <p>{{$data->parent_name}}</p>
                                </div>
                                <div class="col-xl-3 col-lg-4 col-sm-6 col-12">
                                    <label>Pincode:</label>
                                    <p>{{$data->pincode}}</p>
                                </div>
                                <div class="col-xl-3 col-lg-4 col-sm-6 col-12">
                                    <label>Class:</label>
                                    <p>{{$data->class}}</p>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('script')
@endsection