@extends('admin.layout.app')
@section('page-title', 'Category detail')

@section('section')

@php
    if (categoryLevelFinder($data->id) == "Level 1") {
        $productsUnderCategory = catLeveltoProducts($data->id);
    } elseif (categoryLevelFinder($data->id) == "Level 2") {
        $productsUnderCategory = catLeve2toProducts($data->id);
    } elseif (categoryLevelFinder($data->id) == "Level 3") {
        $productsUnderCategory = $data->productDetails;
    } else {
        $productsUnderCategory = [];
    }
@endphp

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row mb-3">
                            <div class="col-md-12 text-right">
                                <a href="{{ route('admin.category.list.all') }}" class="btn btn-sm btn-primary"> <i class="fa fa-chevron-left"></i> Back</a>

                                <a href="{{ route('admin.category.edit', $data->id) }}" class="btn btn-sm btn-primary"> <i class="fa fa-edit"></i> Edit</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="small text-muted mb-0">Images</p>
                        @if (!empty($data->icon_path_small) && !empty($data->banner_image_path_small))
                            <div class="d-flex mb-3">
                                {{-- @foreach ($data->imageDetails as $image) --}}
                                    @if (!empty($data->icon_path_small) && file_exists(public_path($data->icon_path_small)))
                                        <img src="{{ asset($data->icon_path_small) }}" alt="product-img" class="img-thumbnail mr-3">
                                    @else
                                        <img src="{{ asset('backend-assets/images/placeholder.jpg') }}" alt="product-image" style="height: 50px" class="mr-2">
                                    @endif

                                    @if (!empty($data->banner_image_path_small) && file_exists(public_path($data->banner_image_path_small)))
                                        <img src="{{ asset($data->banner_image_path_small) }}" alt="product-img" class="img-thumbnail mr-3">
                                    @else
                                        <img src="{{ asset('backend-assets/images/placeholder.jpg') }}" alt="product-image" style="height: 50px" class="mr-2">
                                    @endif
                                {{-- @endforeach --}}
                            </div>
                        @else
                            <p class="text-dark">NA</p>
                        @endif

                        <p class="small text-muted mb-0">Title</p>
                        <p class="text-dark">{{ $data->title ?? 'NA' }}</p>

                        <p class="small text-muted mb-0">Level</p>
                        <p class="">
                            @if(categoryLevelFinder($data->id) == "Invalid")
                                <span class="badge badge-danger" data-toggle="tooltip" title="Parent not found. Delete and create again">{{ categoryLevelFinder($data->id) }}</span>
                            @else
                                <span class="badge badge-dark">{{ categoryLevelFinder($data->id) }}</span>
                            @endif
                        </p>

                        <p class="small text-muted mb-0">Tree</p>
                        @if ($data->parent_id == 0)
                            @forelse ($data->childCategories as $category)
                                <p class="text-dark d-inline-block">
                                    <a href="{{ route('admin.category.detail', $category->id) }}">
                                        {{ $category->title}}{{!$loop->last ? ', ' : ''}}
                                    </a>
                                </p>
                            @empty
                                <p class="text-dark">No records found</p>
                            @endforelse
                        @else

                            @if ($data->categoryDetails)

                                @if ($data->categoryDetails->categoryDetails)
                                <p class="text-dark d-inline-block">
                                    <a href="{{ route('admin.category.detail', $data->categoryDetails->categoryDetails->id) }}">
                                        {{ $data->categoryDetails->categoryDetails->title}}
                                    </a>
                                </p>
                                <p class="text-dark d-inline-block mx-2"><i class="fa fa-chevron-right"></i></p>
                                @endif

                                <p class="text-dark d-inline-block">
                                    <a href="{{ route('admin.category.detail', $data->categoryDetails->id) }}">
                                        {{ $data->categoryDetails->title}}
                                    </a>
                                </p>
                                <p class="text-dark d-inline-block mx-2"><i class="fa fa-chevron-right"></i></p>
                            @else
                                <span class="badge badge-danger" data-toggle="tooltip" title="Parent not found. Delete and create again">Invalid</span>
                            @endif

                            <p class="text-dark d-inline-block">{{ $data->title }}</p>
                        @endif

                        <p class="small text-muted mb-0">Short Description</p>
                        <p class="text-dark">{{ $data->short_desc ?? 'NA' }}</p>

                        <p class="small text-muted mb-0">Long Description</p>
                        <p class="text-dark">{{ $data->long_desc ?? 'NA' }}</p>

                        <hr>

                        <p class="small text-muted mb-0">Page title</p>
                        @if ($data->page_title)
                            <p class="text-dark">{{ nl2br($data->page_title) }}</p>
                        @else
                            <p class="text-dark">NA</p>
                        @endif

                        <p class="small text-muted mb-0">Meta title</p>
                        @if ($data->meta_title)
                            <p class="text-dark">{{ nl2br($data->meta_title) }}</p>
                        @else
                            <p class="text-dark">NA</p>
                        @endif

                        <p class="small text-muted mb-0">Meta description</p>
                        @if ($data->meta_desc)
                            <p class="text-dark">{{ nl2br($data->meta_desc) }}</p>
                        @else
                            <p class="text-dark">NA</p>
                        @endif

                        <p class="small text-muted mb-0">Meta keyword</p>
                        @if ($data->meta_keyword)
                            <p class="text-dark">{{ nl2br($data->meta_keyword) }}</p>
                        @else
                            <p class="text-dark">NA</p>
                        @endif

                        <p class="small text-muted mb-0">Products</p>

                        @if (count($productsUnderCategory) > 0)
                            <div class="row">
                            @foreach ($productsUnderCategory as $item)
                                <div class="col-md-2">
                                    <div class="card">
                                        <div class="card-body">
                                            @if (!empty($item->imageDetails) && count($item->imageDetails))
                                                @foreach ($item->imageDetails as $image)
                                                    <img src="{{ asset($image->img_small) }}" alt="product-image" style="height: 50px" class="img-thumbnail mr-2">
                                                    @break;
                                                @endforeach
                                            @else
                                                <img src="{{ asset('backend-assets/images/placeholder.jpg') }}" alt="product-image" style="height: 50px" class="mr-2">
                                            @endif
                                            <p class="card-text mb-2">{{$item->title}}</p>
                                            <a href="{{ route('admin.product.detail', $item->id) }}">View Details</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            </div>
                        @else
                            <p class="text-dark">NA</p>
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