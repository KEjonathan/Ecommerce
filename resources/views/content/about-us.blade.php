@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card border-0 shadow-sm rounded-lg overflow-hidden">
                @if($aboutUs && $aboutUs->image)
                    <div class="about-image-container">
                        <img src="{{ asset('storage/' . $aboutUs->image) }}" alt="About Us" class="img-fluid w-100 about-cover-image">
                    </div>
                @else
                    <div class="about-image-container bg-light">
                        <img src="https://images.unsplash.com/photo-1616530940355-351fabd9524b?q=80&w=1935&auto=format&fit=crop" alt="Default About Image" class="img-fluid w-100 about-cover-image">
                    </div>
                @endif
                
                <div class="card-body p-4 p-md-5">
                    <h1 class="display-4 mb-4 text-primary">
                        {{ $aboutUs ? $aboutUs->title : 'About Our Company' }}
                    </h1>
                    
                    <div class="content-section mb-5">
                        @if($aboutUs && $aboutUs->content)
                            {!! nl2br(e($aboutUs->content)) !!}
                        @else
                            <p class="lead">Welcome to our company! We're dedicated to providing high-quality products and excellent service to our customers.</p>
                            <p>This is a placeholder text since no about us content has been added yet. As an administrator, you can update this content from the admin dashboard.</p>
                        @endif
                    </div>
                    
                    @if(($aboutUs && $aboutUs->mission) || ($aboutUs && $aboutUs->vision))
                        <div class="row mb-4">
                            @if($aboutUs && $aboutUs->mission)
                                <div class="col-md-6 mb-4 mb-md-0">
                                    <div class="card h-100 border-0 shadow-sm">
                                        <div class="card-body p-4">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="icon-box bg-primary-subtle rounded-circle p-3 me-3">
                                                    <i class="bi bi-bullseye fs-4 text-primary"></i>
                                                </div>
                                                <h3 class="mb-0">Our Mission</h3>
                                            </div>
                                            <p class="mb-0">{{ $aboutUs->mission }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            @if($aboutUs && $aboutUs->vision)
                                <div class="col-md-6">
                                    <div class="card h-100 border-0 shadow-sm">
                                        <div class="card-body p-4">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="icon-box bg-primary-subtle rounded-circle p-3 me-3">
                                                    <i class="bi bi-eye fs-4 text-primary"></i>
                                                </div>
                                                <h3 class="mb-0">Our Vision</h3>
                                            </div>
                                            <p class="mb-0">{{ $aboutUs->vision }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                    
                    <div class="text-center mt-5">
                        <h3 class="mb-4">Have Questions?</h3>
                        <a href="{{ route('home') }}" class="btn btn-primary btn-lg me-2">Visit Our Shop</a>
                        <a href="#" class="btn btn-outline-primary btn-lg">Contact Us</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .about-image-container {
        height: 350px;
        overflow: hidden;
    }
    
    .about-cover-image {
        object-fit: cover;
        height: 100%;
        width: 100%;
    }
    
    .icon-box {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endsection 