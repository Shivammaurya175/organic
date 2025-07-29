@extends('Admin.layout.main')

@section('content')
<div class="container">
    <h3>Product List</h3>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>#ID</th>
                <th>Image</th>
                <th>Name</th>
                <th>VP</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>
                    @if ($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="Image" width="50">
                    @endif
                </td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->volume_point }}</td>
                <td>₹{{ number_format($product->price, 2) }}</td>
                <td>{{ $product->stock }}</td>
                <td>
                    @if ($product->deleted_at)
                        <span class="badge bg-danger">Deleted</span>
                    @else
                        <span class="badge bg-success">Active</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-warning btn-sm">Edit</a>

                    @if (!$product->deleted_at)
                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" style="display:inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Soft delete this product?')">Delete</button>
                    </form>
                    @else
                    <form action="{{ route('admin.products.restore', $product->id) }}" method="POST" style="display:inline-block">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm">Restore</button>
                    </form>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
