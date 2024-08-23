@extends('admin.layout.app')
@section('page-title', 'Admission application list')

@section('section')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6"></div>
                            <div class="col-md-6">
                                <form action="" method="get">
                                    <div class="d-flex justify-content-end">
                                        <div class="form-group ml-2">
                                            <lable class="text-sm">Start Date</lable>
                                            <input type="datetime-local" class="form-control form-control-sm" name="start_date" id="start_date" value="{{ request()->input('start_date')}}">
                                        </div>
                                        <div class="form-group ml-2">
                                            <lable class="text-sm">End Date</lable>
                                            <input type="datetime-local" class="form-control form-control-sm" name="end_date" id="end_date" value="{{ request()->input('end_date')}}">
                                        </div>
                                        <div class="form-group ml-2 mt-4">
                                             <lable class=""></lable>
                                            <input type="search" style="width: 250px;" class="form-control form-control-sm" name="keyword" id="keyword" value="{{ request()->input('keyword') }}" placeholder="Search something...">
                                        </div>
                                        <div class="form-group ml-2 mt-4">
                                            <div class="btn-group">
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    <i class="fa fa-filter"></i>
                                                </button>
                                                <a href="{{ url()->current() }}" class="btn btn-sm btn-light" data-toggle="tooltip" title="Clear filter">
                                                    <i class="fa fa-times"></i>
                                                </a>
                                                 <a href="{{ route('admin.admission.application.export', ['start_date' => request()->input('start_date'), 'end_date' => request()->input('end_date'), 'keyword' => request()->input('keyword')]) }}" class="btn btn-sm btn-primary" data-toggle="tooltip" title="Export Data">
                                                    Export
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th style="min-width: 160px">Student Details</th>
                                    <th>Mobile</th>
                                    <th>Email</th>
                                    <th style="min-width: 66px">Class</th>
                                    <th>Source</th>
                                    <th>Medium</th>
                                    <th>Campaign</th>
                                    <th>Term</th>
                                    <th>Content</th>
                                    <th style="width: 100px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data as $index => $item)
                                    <tr>
                                        <td>{{ $index + $data->firstItem() }}</td>
                                        <td style="min-width: 160px">
                                            <div class="title-part">
                                                <p class="text-muted mb-0"><strong>Name: </strong>{{ $item->name }}</p>
                                            </div>
                                            <div class="title-part">
                                                <p class="text-muted mb-0"><strong>Parent Name: </strong>{{ $item->parent_name }}</p>
                                            </div>
                                            <div class="title-part">
                                                <p class="text-muted mb-0"><strong>DOB: </strong>{{ $item->dob }}</p>
                                            </div>
                                            <div class="title-part">
                                                <p class="text-muted mb-0"><strong>PinCode: </strong>{{ $item->pincode }}</p>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="title-part">
                                                <p class="text-muted mb-0">{{ $item->mobile }}</p>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="title-part">
                                                <p class="text-muted mb-0">{{ $item->email }}</p>
                                            </div>
                                        <td style="min-width: 66px">
                                            <div class="title-part">
                                                <p class="text-muted mb-0">{{ $item->class }}</p>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="title-part">
                                                <p class="text-muted mb-0">{{ $item->utm_source }}</p>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="title-part">
                                                <p class="text-muted mb-0">{{ $item->utm_medium }}</p>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="title-part">
                                                <p class="text-muted mb-0">{{ $item->utm_campaign }}</p>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="title-part">
                                                <p class="text-muted mb-0">{{ $item->utm_term }}</p>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="title-part">
                                                <p class="text-muted mb-0">{{ $item->utm_content }}</p>
                                            </div>
                                        </td>
                                        
                                        <td class="">
                                            <div class="btn-group">
                                                <a href="{{ route('admin.admission.application.view', $item->id) }}" class="btn btn-sm btn-dark" data-toggle="tooltip" title="Edit">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            </div>
                                            <div class="title-part">
                                                <p class="badge bg-primary">{{date('d-m-Y h:i a' ,strtotime($item->created_at))}}</p>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="100%" class="text-center">No records found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <div class="pagination-container">
                            {{$data->appends($_GET)->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('script')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $('.delete-btn').click(function() {
            var itemId = $(this).data('id');
            
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'job-vacancy/delete/' + itemId; // Replace '/delete/' with your actual delete route
                }
            });
        });
    });
</script>
@endsection