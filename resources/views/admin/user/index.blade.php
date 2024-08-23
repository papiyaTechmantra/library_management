@extends('admin.layout.app')

@section('page-title', 'Users list')

@section('section')
<section class="quick-boxes">
    <div class="table-responsive">
        <table class="table table-sm table-hover table-striped">
            <thead>
                <tr>
                    <th>SR</th>
                    <th>Name</th>
                    <th>Mobile</th>
                    <th>Whatsapp</th>
                    <th>Email</th>
                    <th>Referred by</th>
                    <th>Referrals</th>
                    <th>Created at</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($data as $userKey => $user)
                <tr>
                    <td>{{ $userKey + 1 }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->mobile_no }}</td>
                    <td>{{ $user->whatsapp_no }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->referredByDetails ? $user->referredByDetails->name : '' }}</td>
                    <td>{{ $user->referralDetails ? count($user->referralDetails) : 0 }}</td>
                    <td>{{ $user->created_at ? h_date($user->created_at) : '' }}</td>
                    <td>
                        {{-- <a href="" class="badge bg-dark">Status</a>
                        <a href="" class="badge bg-dark">Edit</a>
                        <a href="" class="badge bg-dark">Delete</a> --}}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{$data->appends($_GET)->links()}}
</section>
@endsection