@extends('layouts.main') {{-- Or your main layout --}}

@section('content')
<div class="container mt-5">
    <h2>Your Cart</h2>

    @if($items->isEmpty())
        <p>Your cart is empty.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Image</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @php $grandTotal = 0; @endphp
                @foreach($items as $item)
                    @php
                        $product = is_array($item) ? $item['product'] : $item->product;
                        $quantity = is_array($item) ? $item['quantity'] : $item->quantity;
                        $total = $product ? $product->price * $quantity : 0;
                        $grandTotal += $total;
                    @endphp

                    @if($product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>
                            <img src="{{ asset('storage/' . $product->image) }}" alt="Image" width="60">
                        </td>
                        <td>₹{{ number_format($product->price, 2) }}</td>
                        <td>{{ $quantity }}</td>
                        <td>₹{{ number_format($total, 2) }}</td>
                        <td>
                            {{-- Add update/delete buttons here --}}
                            <form method="POST" action="{{ route('cart.remove', $product->id) }}">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Remove</button>
                            </form>
                        </td>
                    </tr>
                    @endif
                @endforeach
            </tbody>
        </table>

        <h4>Total: ₹{{ number_format($grandTotal, 2) }}</h4>
        <a href="{{ route('checkout') }}" class="btn btn-primary">Proceed to Checkout</a>
    @endif
</div>
@endsection
