@extends('Admin.layout.main')

@section('content')
    <h2>Edit Product</h2>

    @if (session('success'))
        <p style="color:green;">{{ session('success') }}</p>
    @endif

    <form method="POST" action="{{ route('products.update', $product->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <label>Category:</label>
        <select name="category_id" class="form-control">
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>

        <label>Name:</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}">
        @error('name') <p style="color:red">{{ $message }}</p> @enderror

        <label>Description:</label>
        <textarea class="form-control" name="description">{{ old('description', $product->description) }}</textarea>

        <label>Price:</label>
        <input type="text" name="price" class="form-control" value="{{ old('price', $product->price) }}">
        @error('price') <p style="color:red">{{ $message }}</p> @enderror

        <label>Discount Price:</label>
        <input type="text" name="dp_price" class="form-control" value="{{ old('dp_price', $product->dp_price) }}">
        @error('dp_price') <p style="color:red">{{ $message }}</p> @enderror

        <label>Stock:</label>
        <input type="number" name="stock" class="form-control" value="{{ old('stock', $product->stock) }}">
        @error('stock') <p style="color:red">{{ $message }}</p> @enderror

        <label>Volume Point:</label>
        <input type="number" name="volume_point" class="form-control" value="{{ old('volume_point', $product->volume_point) }}">
        @error('volume_point') <p style="color:red">{{ $message }}</p> @enderror

        <label>Current Image:</label><br>
        @if($product->image)
            <img src="{{ asset('storage/' . $product->image) }}" width="100"><br><br>
        @endif

        <label>Change Image:</label>
        <input type="file" name="image" class="form-control">
        @error('image') <p style="color:red">{{ $message }}</p> @enderror

        <br>
        <button class="btn btn-success" type="submit">Update Product</button>
    </form>
@endsection
