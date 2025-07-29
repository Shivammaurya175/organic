@extends('Admin.layout.main')

@section('content')
<div class="container">
    

    @if ($histories->isEmpty())
        <p>No rank changes found.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Old Rank</th>
                    <th>New Rank</th>
                    <th>Self VP</th>
                    <th>Team VP</th>
                    <th>Changed At</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($histories as $history)
                    <tr>
                        <td>{{ $history->old_rank ?? '—' }}</td>
                        <td>{{ $history->new_rank }}</td>
                        <td>{{ $history->self_volume }}</td>
                        <td>{{ $history->team_volume }}</td>
                        <td>{{ $history->changed_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
