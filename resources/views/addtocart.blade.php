@extends('layouts.main')
@section('content')

<h2>Your Cart</h2>

@if($items->isEmpty())
    <p>Your cart is empty.</p>
@else
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Product</th>
                <th>Thumbnail</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                @php
                    $product = is_array($item) ? $item['product'] : $item->product;
                    $quantity = is_array($item) ? $item['quantity'] : $item->quantity;
                @endphp
                @if ($product)
                    <tr>
    <td>{{ $product->name }}</td>
    <td><img src="{{ asset('storage/' . $product->image) }}" width="50"></td>
    <td>₹{{ $product->dp_price ?? $product->price }}</td>
    <td>
        <form action="{{ route('cart.update') }}" method="POST" class="d-flex align-items-center">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <button type="submit" name="quantity" value="{{ $quantity - 1 }}" class="btn btn-sm btn-secondary" {{ $quantity <= 1 ? 'disabled' : '' }}>-</button>
            <span class="mx-2">{{ $quantity }}</span>
            <button type="submit" name="quantity" value="{{ $quantity + 1 }}" class="btn btn-sm btn-secondary">+</button>
        </form>
    </td>
    <td>₹{{ ($product->dp_price ?? $product->price) * $quantity }}</td>
    <td>
        <form action="{{ route('cart.remove') }}" method="POST" onsubmit="return confirm('Remove this item?')">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <button type="submit" class="btn btn-sm btn-danger">Remove</button>
        </form>
    </td>
</tr>

                @endif
            @endforeach
            
        </tbody>

    </table>
@endif
  @php
    $total = $items->reduce(function ($carry, $item) {
        $product = is_array($item) ? $item['product'] : $item->product;
        $quantity = is_array($item) ? $item['quantity'] : $item->quantity;
        return $carry + (($product->dp_price ?? $product->price) * $quantity);
    }, 0);
@endphp

<h4>Total: ₹{{ $total }}</h4>
<h1>imran</h1>
@endsection