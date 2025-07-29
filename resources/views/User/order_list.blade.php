@extends('User.layout.main') <!-- or your layout -->


@section('content')
<div class="container mt-4">
    <h3 class="mb-4">My Orders</h3>

    @if ($orders->isEmpty())
        <p>No orders found.</p>
    @else
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Products</th>
                    <th>Total (₹)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $index => $order)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $order->created_at->format('d M Y') }}</td>
                    <td>
                        <ul class="mb-0">
                            @foreach ($order->orderItems as $item)
                                <li>
                                    {{ $item->product->name }} —
                                    Qty: {{ $item->quantity }},
                                    Price: ₹{{ $item->price }},
                                    VP: {{ $item->volume_point }}
                                </li>
                            @endforeach
                        </ul>
                    </td>
                    <td><strong>₹{{ $order->total }}</strong></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection

