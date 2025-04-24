@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Edit Product: {{ $product->name }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="name" class="form-label">Product Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $product->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="price" class="form-label">Price <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" 
                                               id="price" name="price" value="{{ old('price', $product->price) }}" required>
                                        @error('price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="stock_quantity" class="form-label">Stock Quantity <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('stock_quantity') is-invalid @enderror" 
                                           id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" required>
                                    @error('stock_quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                                    <select class="form-select @error('category_id') is-invalid @enderror" 
                                            id="category_id" name="category_id" required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ (old('category_id', $product->category_id) == $category->id) ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="supplier_id" class="form-label">Supplier</label>
                                    <select class="form-select @error('supplier_id') is-invalid @enderror" 
                                            id="supplier_id" name="supplier_id">
                                        <option value="">Select Supplier</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" {{ (old('supplier_id', $product->supplier_id) == $supplier->id) ? 'selected' : '' }}>
                                                {{ $supplier->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('supplier_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="4">{{ old('description', $product->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="image" class="form-label">Product Image</label>
                                    @if($product->image)
                                        <div class="mb-2">
                                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" 
                                                 class="img-thumbnail" style="max-height: 100px;">
                                        </div>
                                    @endif
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                           id="image" name="image">
                                    <small class="text-muted">Supported formats: JPEG, PNG, JPG, GIF. Max size: 2MB</small>
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label">Availability</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_morning_available" 
                                               name="is_morning_available" {{ old('is_morning_available', $product->is_morning_available) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_morning_available">
                                            Morning
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_afternoon_available" 
                                               name="is_afternoon_available" {{ old('is_afternoon_available', $product->is_afternoon_available) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_afternoon_available">
                                            Afternoon
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_evening_available" 
                                               name="is_evening_available" {{ old('is_evening_available', $product->is_evening_available) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_evening_available">
                                            Evening
                                        </label>
                                    </div>
                                </div>

                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="status" 
                                           name="status" {{ old('status', $product->status) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status">
                                        Active
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group text-end">
                            <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Product</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 