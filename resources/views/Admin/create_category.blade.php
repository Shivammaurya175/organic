@extends('Admin.layout.main')

@section('title', 'Create Category')

@section('content')
    <h2>Add Category</h2>

    @if(session('success'))
        <p style="color:green">{{ session('success') }}</p>
    @endif

    <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <label>Name:</label>
        <input type="text" class="form-control" name="name" value="{{ old('name') }}">
        @error('name') <p style="color:red">{{ $message }}</p> @enderror

        <label>Description:</label>
        <textarea class="form-control" name="description">{{ old('description') }}</textarea>

        
    <label>Image:</label>
    <input type="file" class="form-control" name="image">
    @error('image') <p style="color:red">{{ $message }}</p> @enderror
        

        <button type="submit" class="mt-2 btn btn-primary">Create Category</button>
    </form>
@endsection
