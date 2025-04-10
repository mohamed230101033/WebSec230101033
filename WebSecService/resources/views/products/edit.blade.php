@extends('layouts.master')
@section('title', 'Edit Product')
@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-12">
            <h1>{{ $product->id ? 'Edit' : 'Add' }} Product</h1>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('products_save', $product->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="code" class="form-label">Product Code</label>
                            <input type="text" class="form-control" id="code" name="code" value="{{ old('code', $product->code) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $product->name) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="model" class="form-label">Model</label>
                            <input type="text" class="form-control" id="model" name="model" value="{{ old('model', $product->model) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input type="number" step="0.01" class="form-control" id="price" name="price" value="{{ old('price', $product->price) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="stock" class="form-label">Stock</label>
                            <input type="number" class="form-control" id="stock" name="stock" value="{{ old('stock', $product->stock) }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="5">{{ old('description', $product->description) }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="photo" class="form-label">Product Photo</label>
                            <input type="file" class="form-control" id="photo" name="photo" accept="image/jpeg,image/png,image/gif,image/webp">
                            @if($product->photo)
                            <div class="mt-2">
                                @if(is_file(public_path('images/' . $product->photo)))
                                <img src="{{ asset('images/' . $product->photo) }}" alt="{{ $product->name }}" class="img-thumbnail" style="height: 100px;">
                                @else
                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    Image file not found
                                </div>
                                @endif
                                <p class="small text-muted">Current photo will be kept if no new photo is uploaded.</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">Save Product</button>
                    <a href="{{ route('products_list') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection