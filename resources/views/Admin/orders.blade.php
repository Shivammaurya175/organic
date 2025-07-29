@extends('Admin.layout.main')

@section('content')
<div class="container">
    <h3>Orders for {{ $user->name }} ({{ $user->user_id }})</h3>

    @php
        $grandVolumePoint = 0;
    @endphp

    @if ($orders->isEmpty())
        <p>No orders found.</p>
    @else
        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Order ID</th>
                    <th>Date</th>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Price (₹)</th>
                    <th>VP</th>
                    <th>VP × Qty</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    @php
                        $orderVolume = 0;
                    @endphp
                    @foreach ($order->orderItems as $item)
                        @php
                            $vpQty = $item->volume_point * $item->quantity;
                            $orderVolume += $vpQty;
                            $grandVolumePoint += $vpQty;
                        @endphp
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>{{ $order->created_at->format('d M Y') }}</td>
                            <td>{{ $item->product->name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ $item->price }}</td>
                            <td>{{ $item->volume_point }}</td>
                            <td>{{ $vpQty }}</td>
                        </tr>
                    @endforeach
                    <tr class="table-secondary">
                        <td colspan="6" class="text-right font-weight-bold">Order Total VP</td>
                        <td><strong>{{ $orderVolume }}</strong></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="alert alert-info text-right">
            <h5><strong>Grand Total Volume Point: {{ $grandVolumePoint }}</strong></h5>
        </div>
    @endif
</div>
@endsection
