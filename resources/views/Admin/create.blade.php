@extends('Admin.layout.main')

@section('content')
    <h2>Add Product</h2>

    @if (session('success'))
        <p style="color:green;">{{ session('success') }}</p>
    @endif

    <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
        @csrf

        <label>Category:</label>
<select name="category_id" class="form-control">
    @foreach($categories as $category)
        <option value="{{ $category->id }}">{{ $category->name }}</option>
    @endforeach
</select>

        <label>Name:</label>
        <input type="text" name="name" class="form-control" value="{{ old('name') }}">
        @error('name') <p style="color:red">{{ $message }}</p> @enderror

        <label>Description:</label>
        <textarea class="form-control"  name="description">{{ old('description') }}</textarea>

        <label>Price:</label>
        <input type="text" name="price" class="form-control"  value="{{ old('price') }}">
        @error('price') <p style="color:red">{{ $message }}</p> @enderror
        <label>Discount Price:</label>
        <input type="text" name="dp_price" class="form-control"  value="{{ old('dp_price') }}">
        @error('dp_price') <p style="color:red">{{ $message }}</p> @enderror

        <label>Stock:</label>
        <input type="number" name="stock" class="form-control"  value="{{ old('stock') }}">
        @error('stock') <p style="color:red">{{ $message }}</p> @enderror

        <label>Volume Point:</label>
        <input type="number" name="volume_point" class="form-control" value="{{ old('volume_point') }}">
        @error('volume_point') <p style="color:red">{{ $message }}</p> @enderror

         <label>Product Image:</label>
    <input type="file" name="image" class="form-control">
    @error('image') <p style="color:red">{{ $message }}</p> @enderror

        <button class="btn btn-primary" type="submit">Add Product</button>
    </form>
@endsection
