@extends('Admin.layout.main')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">User Genealogy (Referral Tree)</h2>

    @foreach ($rootUsers as $user)
        @include('Admin.genealogy-node', ['user' => $user, 'level' => 0])
    @endforeach
</div>
@endsection
