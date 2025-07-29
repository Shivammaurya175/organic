@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <h2>Checkout</h2>

    @if($items->isEmpty())
        <p>Your cart is empty.</p>
    @else
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>VP</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @php $total = 0; @endphp
                @foreach($items as $item)
    @php
        $product = $item['product'];
        $quantity = $item['quantity'];
        $subtotal = $product->price * $quantity;
        $vptotal = $product->volume_point * $quantity;
        $total += $subtotal;
    @endphp
    <tr>
        <td>{{ $product->name }}</td>
        <td>
            <form action="{{ route('cart.update1', $item['id'] ?? $product->id) }}" method="POST">
                @csrf
                @method('PUT')
                <button type="submit" name="action" value="decrease" class="btn btn-sm btn-outline-secondary">-</button>
                <span>{{ $quantity }}</span>
                <button type="submit" name="action" value="increase" class="btn btn-sm btn-outline-secondary">+</button>
            </form>
        </td>
        <td>{{ $vptotal }}</td>
        <td>₹{{ number_format($product->price, 2) }}</td>
        <td>₹{{ number_format($subtotal, 2) }}</td>
        <td>
    <form action="{{ route('cart.remove') }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this item?');">
        @csrf
        <input type="hidden" name="product_id" value="{{ $item['product_id'] ?? $product->id }}">
        <button type="submit" class="btn btn-danger btn-sm">Remove</button>
    </form>
</td>

    </tr>
@endforeach

            </tbody>
        </table>

        <h4 class="text-right">Total: ₹{{ number_format($total, 2) }}</h4>

        <form method="POST" action="{{ route('checkout.placeOrder') }}">
            @csrf
            <button type="submit" class="btn btn-success">Place Order</button>
        </form>
    @endif
</div>
@endsection

@section('scripts')
<script>
    document.querySelectorAll('.update-qty').forEach(button => {
        button.addEventListener('click', function () {
            const itemId = this.dataset.id;
            const action = this.dataset.action;

            fetch(`/carts/update/${itemId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ action })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('qty-' + itemId).innerText = data.quantity;
                    location.reload(); // optional — use smarter DOM update if preferred
                }
            })
            .catch(err => console.error(err));
        });
    });
</script>
@endsection
