@extends('admin.layout.app')
@section('page-title', 'Category detail')

@section('section')


<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row mb-3">
                            <div class="col-md-12 text-right">
                                <a href="{{ route('admin.category.index') }}" class="btn btn-sm btn-primary"> <i class="fa fa-chevron-left"></i> Back</a>

                                <!-- <a href="{{ route('admin.category.edit', $data->id) }}" class="btn btn-sm btn-primary"> <i class="fa fa-edit"></i> Edit</a> -->
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        

                        <p class="small text-muted mb-0">Title</p>
                        <p class="text-dark">{{ $data->title ?? 'NA' }}</p>

                        

                        <p class="small text-muted mb-0">Status</p>
                        @if($data->status==1)
                        <p class="text-dark">Active</p>
                        @else
                        <p class="text-dark">Inactive</p>
                        @endif
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('script')
    <script>
        checkCatParentLevel();
    </script>
@endsection