@extends('admin.layout.app')
@section('page-title', 'Dashboard')

@section('section')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            {{-- <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="far fa-list-alt"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Category</span>
                        <span class="info-box-number">{{ $data->categories }}</span>
                    </div>
                </div>
            </div> --}}
            {{-- <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box">
                    <span class="info-box-icon bg-success"><i class="fas fa-shopping-cart"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Product</span>
                        <span class="info-box-number">{{ $data->products }}</span>
                    </div>
                </div>
            </div> --}}
            {{-- <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box">
                    <span class="info-box-icon bg-warning"><i class="far fa-copy"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Leads</span>
                        <span class="info-box-number">{{ $data->leads }}</span>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>
</div>
@endsection