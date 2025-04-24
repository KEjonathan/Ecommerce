@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Edit Category: {{ $category->name }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $category->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="parent_id" class="form-label">Parent Category</label>
                            <select class="form-select @error('parent_id') is-invalid @enderror" id="parent_id" name="parent_id">
                                <option value="">None (Top Level Category)</option>
                                @foreach($categories as $parentCategory)
                                    <option value="{{ $parentCategory->id }}" {{ (old('parent_id', $category->parent_id) == $parentCategory->id) ? 'selected' : '' }}>
                                        {{ $parentCategory->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Optional. Select a parent category if this is a subcategory.</small>
                            @error('parent_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="image" class="form-label">Category Image</label>
                            @if($category->image)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" 
                                         class="img-thumbnail" style="max-height: 100px;">
                                </div>
                            @endif
                            <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image">
                            <small class="text-muted">Supported formats: JPEG, PNG, JPG, GIF. Max size: 2MB</small>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="status" name="status" {{ old('status', $category->status) ? 'checked' : '' }}>
                            <label class="form-check-label" for="status">
                                Active
                            </label>
                        </div>

                        <div class="form-group text-end">
                            <a href="{{ route('categories.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Category</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 