@extends('admin.layout.app')
@section('page-title', 'Category list')

@section('section')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row mb-3">
                            <div class="col-md-12 text-right">
                                <a href="{{ url('admin/category/create') }}" class="btn btn-sm btn-primary"> <i class="fa fa-plus"></i> Create</a>
                            </div>
                        </div>
                        <!-- <div class="row">
                            <div class="col-md-6"></div>
                            <div class="col-md-6">
                                <form action="" method="get">
                                    <div class="d-flex justify-content-end">
                                        

                                        <div class="form-group ml-2">
                                            <input type="search" class="form-control form-control-sm" name="keyword" id="keyword" value="{{ request()->input('keyword') }}" placeholder="Search something...">
                                        </div>

                                        <div class="form-group ml-2">
                                            <div class="btn-group">
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    <i class="fa fa-filter"></i>
                                                </button>
                                                <a href="{{ url()->current() }}" class="btn btn-sm btn-light" data-toggle="tooltip" title="Clear filter">
                                                    <i class="fa fa-times"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div> -->
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Title</th>
                                    <!-- <th>Parent</th> -->
                                    <!-- <th>Level</th> -->
                                    <th>Status</th>
                                    <th style="width: 100px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($categories as $index => $item)
                                    <tr>
                                        <td>{{ $index + $categories->firstItem() }}</td>
                                        <td>{{ $item->title }}</td>
                                        @if($item->status == 1)
                                            <td>Active</td>
                                        @else
                                            <td>Inactive</td>
                                        @endif
                                       
                                       
                                    
                                        <td class="d-flex">
                                            <div class="custom-control custom-switch mt-1" data-toggle="tooltip" title="Toggle status">
                                                <input type="checkbox" class="custom-control-input" id="customSwitch{{$item->id}}" 
                                                    {{ ($item->status == 1) ? 'checked' : '' }} 
                                                    onchange="statusToggle('{{ route('admin.category.status', $item->id) }}', this)">
                                                <label class="custom-control-label" for="customSwitch{{$item->id}}"></label>
                                            </div>

                                            <div class="btn-group">
                                                <a href="{{ route('admin.category.detail', $item->id) }}" class="btn btn-sm btn-dark" data-toggle="tooltip" title="View">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.category.edit', $item->id) }}" class="btn btn-sm btn-dark" data-toggle="tooltip" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <a href="{{ route('admin.category.delete', $item->id) }}" class="btn btn-sm btn-dark" onclick="return confirm('Are you sure ?')" data-toggle="tooltip" title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </a>
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
                            {{$categories->appends($_GET)->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('script')
<script>
function statusToggle(url, checkbox) {
    var status = checkbox.checked ? 1 : 0;

    $.ajax({
        url: url,
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            status: status
        },
        success: function(response) {
            if (response.success) {
                alert(response.message);
            } else {
                alert('Failed to update status.');
            }
        },
        error: function(xhr) {
            alert('Something went wrong. Please try again.');
            // Revert the checkbox if an error occurs
            checkbox.checked = !checkbox.checked;
        }
    });
}
</script>
