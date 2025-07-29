<div class="mb-3" style="margin-left: {{ $level * 30 }}px;">
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title mb-1">{{ $user->name }} <small class="text-muted">({{ $user->user_id }})</small></h5>
            <p class="card-text mb-1"><strong>Email:</strong> {{ $user->email }}</p>
            <p class="card-text mb-1"><strong>Role:</strong> 
                <span class="badge bg-{{ $user->role === 'admin' ? 'primary' : 'secondary' }}">
                    {{ ucfirst($user->role) }}
                </span>
            </p>
            <p class="card-text mb-0"><strong>Rank:</strong> {{ $user->rank_no ?? '--' }}</p>
        </div>
    </div>

    {{-- Show referrals (downline) recursively --}}
    @foreach ($user->referrals as $ref)
        @include('Admin.genealogy-node', ['user' => $ref, 'level' => $level + 1])
    @endforeach
</div>
