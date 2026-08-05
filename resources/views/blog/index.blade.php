@extends('layouts.app')

@section('content')
<div class="container py-5 animated-fade-in" style="max-width: 900px;">
    <div class="text-center mb-5">
        <span class="badge bg-primary text-uppercase px-3 py-2 mb-2">Our Blog</span>
        <h1 class="fw-extrabold text-dark">Travel Guides & Fleet Updates</h1>
        <p class="text-muted lead">Tips, road trip suggestions, and news from our car rental specialists.</p>
    </div>

    <!-- Search / Filter blog -->
    <div class="card card-custom p-3 border-0 shadow-sm mb-5" style="background-color: var(--card-bg);">
        <form action="{{ route('blog.index') }}" method="GET" class="row g-2">
            <div class="col-md-8">
                <input type="text" name="search" class="form-control border-secondary-subtle" placeholder="Search articles..." value="{{ request('search') }}">
            </div>
            <div class="col-md-4 d-grid">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </form>
    </div>

    <!-- Blog Posts Grid -->
    <div class="row g-4">
        @forelse($blogs as $post)
            <div class="col-md-6">
                <div class="card card-custom h-100 overflow-hidden">
                    <div style="height: 200px; background-color: #cbd5e1; overflow: hidden;">
                        @if($post->image)
                            <img src="{{ $post->image }}" class="w-100 h-100 object-fit-cover" alt="Image">
                        @else
                            <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted"><i class="fa-solid fa-image fs-1"></i></div>
                        @endif
                    </div>
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div>
                            <span class="badge bg-secondary-subtle text-muted text-uppercase fw-bold small mb-2">{{ $post->category->name }}</span>
                            <h5 class="fw-bold text-dark">{{ $post->title }}</h5>
                            <p class="text-muted small mt-2">{{ Str::limit(strip_tags($post->content), 120) }}</p>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top border-secondary-subtle">
                            <small class="text-secondary"><i class="fa-solid fa-eye me-1"></i> {{ $post->views }} Views</small>
                            <a href="{{ route('blog.show', $post->slug) }}" class="btn btn-sm btn-outline-primary">Read More</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5 text-muted">No blog posts found matching your criteria.</div>
            @endforelse
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-5">
        {{ $blogs->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
