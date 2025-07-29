@extends('Admin.layout.main')

@section('content')
<div class="container">
    <h3>User List</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>User ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Rank</th>
                <th>Orders</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($users as $user)
            <tr>
                <td>{{ $user->user_id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->rank_no ?? '--' }}</td>
                <td>{{ $user->orders_count }}</td>
                <td>
                    <a href="{{ route('admin.user.orders', $user->id) }}" class="btn btn-primary btn-sm">View Orders</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
