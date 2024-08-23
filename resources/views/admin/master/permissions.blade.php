@extends('admin.layout.app')
@section('page-title', $data->name)

@section('section')
<style>
    label{
        cursor: pointer;
        padding-right: 15px;
    }
    #master_permission li{
        margin-bottom: 30px;
    }
</style>
<section class="content">
    <div class="container-fluid" id="master_permission">
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
                                    <ul>
                                        <li>
                                            <input type="checkbox" id="admission_management" name="roles[]" {{ in_array('ADMISSION MANAGEMENT', $permissions) ? 'checked' : '' }} value="ADMISSION MANAGEMENT" data-parent="">
                                            <label for="admission_management">ADMISSION MANAGEMENT</label>
                                            <ul>
                                                <input type="checkbox" id="admission_application" name="roles[]" value="admission_application" data-parent="ADMISSION MANAGEMENT" {{ in_array('admission_application', $permissions) ? 'checked' : '' }}>
                                                    <label for="admission_application">Admission Applications</label>
                                            </ul>
                                        </li>
                                        <li>
                                            <input type="checkbox" id="career_management" name="roles[]" {{ in_array('CAREER MANAGEMENT', $permissions) ? 'checked' : '' }} value="CAREER MANAGEMENT" data-parent="">
                                            <label for="career_management">CAREER MANAGEMENT</label>
                                            <ul>
                                                <li>
                                                    <input type="checkbox" id="Posts" name="roles[]" value="Posts" data-parent="CAREER MANAGEMENT" {{ in_array('Posts', $permissions) ? 'checked' : '' }}>
                                                    <label for="Posts">Posts</label>
                                                    <input type="checkbox" id="Units" name="roles[]" value="Units" data-parent="CAREER MANAGEMENT" {{ in_array('Units', $permissions) ? 'checked' : '' }}>
                                                    <label for="Units">Units</label>
                                                    <input type="checkbox" id="Subjetcs" name="roles[]" value="Subjetcs" data-parent="CAREER MANAGEMENT" {{ in_array('Subjetcs', $permissions) ? 'checked' : '' }}>
                                                    <label for="Subjetcs">Subjetcs</label>
                                                    <input type="checkbox" id="job_category" name="roles[]" value="Job Categories" data-parent="CAREER MANAGEMENT" {{ in_array('Job Categories', $permissions) ? 'checked' : '' }}>
                                                    <label for="job_category">Job Categories</label>
                                                    <input type="checkbox" id="job_vacancy" name="roles[]" value="Job Vacancies" data-parent="CAREER MANAGEMENT" {{ in_array('Job Vacancies', $permissions) ? 'checked' : '' }}>
                                                    <label for="job_vacancy">Job Vacancies</label>
                                                    <input type="checkbox" id="Applications" name="roles[]" value="Applications" data-parent="CAREER MANAGEMENT" {{ in_array('Applications', $permissions) ? 'checked' : '' }}>
                                                    <label for="Applications">Job Applications</label>
                                                </li>
                                            </ul>
                                        </li>
                                        <li>
                                            <input type="checkbox" id="master_modules" name="roles[]" value="MASTER MODULES" data-parent="" {{ in_array('MASTER MODULES', $permissions) ? 'checked' : '' }}>
                                            <label for="master_modules">MASTER MODULES</label>
                                            <ul>
                                                <li>
                                                    <input type="checkbox" id="classes" name="roles[]" value="classes" data-parent="MASTER MODULES" {{ in_array('classes', $permissions) ? 'checked' : '' }}>
                                                    <label for="classes">Classes</label>
                                                    <input type="checkbox" id="Facilities" name="roles[]" value="Facilities" data-parent="MASTER MODULES" {{ in_array('Facilities', $permissions) ? 'checked' : '' }}>
                                                    <label for="Facilities">Facilities</label>
                                                    <input type="checkbox" id="extra_curricular" name="roles[]" value="Extra Curricular" data-parent="MASTER MODULES" {{ in_array('Extra Curricular', $permissions) ? 'checked' : '' }}>
                                                    <label for="extra_curricular">Extra Curricular</label>
                                                    <input type="checkbox" id="teaching_process" name="roles[]" value="Teaching Process" data-parent="MASTER MODULES" {{ in_array('Teaching Process', $permissions) ? 'checked' : '' }}>
                                                    <label for="teaching_process">Teaching Process</label>
                                                    <input type="checkbox" id="why_choose_us" name="roles[]" value="Why Choose Us" data-parent="MASTER MODULES" {{ in_array('Why Choose Us', $permissions) ? 'checked' : '' }}>
                                                    <label for="why_choose_us">Why Choose Us</label>
                                                    <input type="checkbox" id="Blogs" name="roles[]" value="Blogs" data-parent="MASTER MODULES" {{ in_array('Blogs', $permissions) ? 'checked' : '' }}>
                                                    <label for="Blogs">Blogs</label>
                                                    <input type="checkbox" id="Events" name="roles[]" value="Events" data-parent="MASTER MODULES" {{ in_array('Events', $permissions) ? 'checked' : '' }}>
                                                    <label for="Events">Events</label>
                                                    <input type="checkbox" id="Faculties" name="roles[]" value="Faculties" data-parent="MASTER MODULES" {{ in_array('Faculties', $permissions) ? 'checked' : '' }}>
                                                    <label for="Faculties">Faculties</label>
                                                    <input type="checkbox" id="Testimonials" name="roles[]" value="Testimonials" data-parent="MASTER MODULES" {{ in_array('Testimonials', $permissions) ? 'checked' : '' }}>
                                                    <label for="Testimonials">Testimonials</label>
                                                    <input type="checkbox" id="Gallery" name="roles[]" value="Gallery" data-parent="MASTER MODULES" {{ in_array('Gallery', $permissions) ? 'checked' : '' }}>
                                                    <label for="Gallery">Gallery</label>
                                                    <input type="checkbox" id="social_Media" name="roles[]" value="social_Media" data-parent="MASTER MODULES" {{ in_array('social_Media', $permissions) ? 'checked' : '' }}>
                                                    <label for="social_Media">Social Media</label>
                                                </li>
                                            </ul>
                                        </li>
                                        <li>
                                            <input type="checkbox" id="content_management" name="roles[]" value="CONTENT MANAGEMENT" data-parent="" {{ in_array('CONTENT MANAGEMENT', $permissions) ? 'checked' : '' }}>
                                            <label for="content_management">CONTENT MANAGEMENT</label>
                                            <ul>
                                                <li>
                                                    <input type="checkbox" id="Page_content" name="roles[]" value="Page content" data-parent="CONTENT MANAGEMENT" {{ in_array('Page content', $permissions) ? 'checked' : '' }}>
                                                    <label for="Page_content">Page Content</label>
                                                    <input type="checkbox" id="Leads" name="roles[]" value="Leads" data-parent="CONTENT MANAGEMENT" {{ in_array('Leads', $permissions) ? 'checked' : '' }}>
                                                    <label for="Leads">Leads</label>
                                                    <input type="checkbox" id="routine" name="roles[]" value="Class Wise Routine" data-parent="CONTENT MANAGEMENT" {{ in_array('Class Wise Routine', $permissions) ? 'checked' : '' }}>
                                                    <label for="routine">Class Wise Routine</label>
                                                </li>
                                            </ul>
                                        </li>
                                        <li>
                                            <input type="checkbox" id="seo_management" name="roles[]" value="SEO MANAGEMENT" data-parent="" {{ in_array('SEO MANAGEMENT', $permissions) ? 'checked' : '' }}>
                                            <label for="seo_management">SEO MANAGEMENT</label>
                                        </li>
                                        <li>
                                            <input type="checkbox" id="websie_setting" name="roles[]" value="WEBSITE SETTINGS" data-parent="" {{ in_array('WEBSITE SETTINGS', $permissions) ? 'checked' : '' }}>
                                            <label for="websie_setting">WEBSITE SETTINGS</label>
                                        </li>
                                    </ul>
                                    
                                </div>
                            </div>
                            {{-- <button type="submit" class="btn btn-primary">Update</button> --}}
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('script')
<script>
     $(document).ready(function() {
        $('input[type="checkbox"]').change(function() {
                var value = $(this).val();
                var parent = $(this).data('parent');
                var admin_id ="{{$data->id}}"
                var isChecked = $(this).is(':checked');
                var token = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: "{{route('admin.user_management.permissions.update')}}",
                    type: 'POST',
                    data: {
                        value: value,
                        parent: parent,
                        admin_id: admin_id,
                        isChecked: isChecked
                    },
                    headers: {
                        'X-CSRF-TOKEN': token // Include CSRF token in the request headers
                    },
                    success: function(response) {
                        console.log(response);
                        // Handle success response
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        // Handle error response
                    }
                });
            });
        });
</script>
@endsection

