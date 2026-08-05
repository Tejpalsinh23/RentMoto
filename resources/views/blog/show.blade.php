@extends('layouts.app')

@section('content')
<div class="container py-5 animated-fade-in" style="max-width: 800px;">
    <!-- Back link -->
    <a href="{{ route('blog.index') }}" class="btn btn-link text-decoration-none text-muted mb-4 p-0"><i class="fa-solid fa-arrow-left me-1"></i> Back to Blog Directory</a>

    <article class="card card-custom p-4 p-md-5 border-0 shadow-sm mb-5" style="background-color: var(--card-bg);">
        <span class="badge bg-primary text-uppercase fw-bold mb-2 align-self-start">{{ $blog->category->name }}</span>
        <h1 class="fw-extrabold text-dark mb-3">{{ $blog->title }}</h1>
        
        <div class="d-flex align-items-center gap-3 text-muted small mb-4 border-bottom pb-3 border-secondary-subtle">
            <span><i class="fa-solid fa-calendar me-1"></i> Published: {{ $blog->created_at->format('F d, Y') }}</span>
            <span>|</span>
            <span><i class="fa-solid fa-eye me-1"></i> Views: {{ $blog->views }}</span>
        </div>

        @if($blog->image)
            <div class="mb-4" style="height: 350px; overflow: hidden; border-radius: 0.75rem;">
                <img src="{{ $blog->image }}" class="w-100 h-100 object-fit-cover" alt="Cover Image">
            </div>
        @endif

        <div class="blog-post-content text-secondary" style="line-height: 1.8; font-size: 1.05rem;">
            {!! nl2br(e($blog->content)) !!}
        </div>
    </article>

    <!-- Recent blog listings section -->
    @if($recentBlogs->count() > 0)
        <h4 class="fw-bold mb-4">Other Recent Reads</h4>
        <div class="row g-3">
            @foreach($recentBlogs as $post)
                <div class="col-md-6 col-12">
                    <div class="card card-custom p-3 border-0 shadow-sm" style="background-color: var(--card-bg);">
                        <span class="badge bg-secondary-subtle text-muted text-uppercase fw-bold small align-self-start mb-2">{{ $post->category->name }}</span>
                        <h6 class="fw-bold"><a href="{{ route('blog.show', $post->slug) }}" class="text-decoration-none text-dark">{{ $post->title }}</a></h6>
                        <small class="text-secondary d-block mt-2">{{ $post->created_at->format('F d, Y') }}</small>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
