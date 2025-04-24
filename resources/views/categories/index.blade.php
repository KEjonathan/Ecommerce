@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0">Categories</h1>
            <p class="text-muted">Browse our products by category</p>
        </div>
    </div>
    
    <div class="row">
        @forelse($categories as $category)
            <div class="col-md-4 mb-4">
                <div class="card h-100 category-card">
                    <div class="position-relative">
                        <img src="https://placehold.co/300x160/0275d8/ffffff/png?text={{ Str::limit($category->name, 15) }}" class="card-img-top" alt="{{ $category->name }}" style="height: 160px; object-fit: cover;">
                        <div class="position-absolute bottom-0 start-0 w-100 p-3" style="background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0) 100%);">
                            <h5 class="card-title text-white mb-0">{{ $category->name }}</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="card-text text-truncate">{{ $category->description }}</p>
                        <a href="{{ route('categories.show', $category) }}" class="btn btn-outline-primary btn-sm">View Products</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    No categories available at the moment.
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection

@section('styles')
<style>
    .category-card {
        transition: transform 0.2s, box-shadow 0.2s;
        overflow: hidden;
    }
    .category-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .category-card img {
        transition: transform 0.3s ease;
    }
    .category-card:hover img {
        transform: scale(1.05);
    }
</style>
@endsection 