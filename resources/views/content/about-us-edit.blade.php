@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Edit About Us Content</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('about.us.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label for="title" class="form-label">Page Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title', $aboutUs->title ?? '') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="image" class="form-label">Header Image</label>
                            @if(isset($aboutUs) && $aboutUs->image)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $aboutUs->image) }}" alt="About Us" 
                                         class="img-thumbnail" style="max-height: 150px;">
                                </div>
                            @endif
                            <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                   id="image" name="image">
                            <small class="text-muted">Recommended size: 1200x400px. Max size: 2MB</small>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="content" class="form-label">Page Content <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('content') is-invalid @enderror" 
                                      id="content" name="content" rows="8" required>{{ old('content', $aboutUs->content ?? '') }}</textarea>
                            <small class="text-muted">You can use plain text or basic HTML formatting.</small>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="mission" class="form-label">Our Mission</label>
                                    <textarea class="form-control @error('mission') is-invalid @enderror" 
                                              id="mission" name="mission" rows="4">{{ old('mission', $aboutUs->mission ?? '') }}</textarea>
                                    @error('mission')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="vision" class="form-label">Our Vision</label>
                                    <textarea class="form-control @error('vision') is-invalid @enderror" 
                                              id="vision" name="vision" rows="4">{{ old('vision', $aboutUs->vision ?? '') }}</textarea>
                                    @error('vision')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('about.us') }}" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Add a simple text editor for the content field
    document.addEventListener('DOMContentLoaded', function() {
        // You could integrate a rich text editor here if needed
    });
</script>
@endsection 