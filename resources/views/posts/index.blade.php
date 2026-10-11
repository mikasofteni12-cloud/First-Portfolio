@extends('layouts.app')

@section('content')
    <div class="container">
        {{-- 8:4 layout --}}
        <div class="row">

            {{-- LEFT SIDE --}}
            <div class="col-md-8">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="fw-bold mb-0">Posts</h3>
                    <a href="{{ route('posts.create') }}" class="btn btn-primary">
                        <i class="fa-solid fa-plus me-1"></i>Add Post
                    </a>
                </div>

                @forelse ($posts as $post)
                    {{-- Distinguish with color for owner or seeker --}}
                    <div
                        class="card shadow-sm mb-4 border-start border-2 border-{{ $post->user->role === 'owner' ? 'primary' : 'success' }}">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                            <i
                                class="fa-regular fa-circle-user fa-2x me-2 text-{{ $post->user->role === 'owner' ? 'primary' : 'success' }}"></i>
                            <div>
                                <h6 class="mb-0 fw-bold">{{ $post->user->name }}</h6>
                                <small class="text-muted">{{ $post->created_at->diffForHumans() }}</small>
                            </div>
                        </div>

                        {{-- bs\adge cateogry 入れたい --}}
                        <span
                            class="badge bg-{{ $post->user->role === 'owner' ? 'primary' : 'success' }}-subtle text-{{ $post->user->role === 'owner' ? 'primary' : 'success' }} border border-{{ $post->user->role === 'owner' ? 'primary' : 'success' }}">
                            {{ ucfirst($post->user->role) }}
                        </span>

                        <div class="card-body">
                            <h5 class="card-title fw-bold">
                                <a href="{{ route('posts.show', $post) }}" class="text-decoration-none text-dark">
                                    {{ $post->title }}
                                </a>
                            </h5>

                            {{-- IF OWNER /Comapany --}}
                            @if ($post->company_name)
                                <div class="p-2 bg-light rounded mb-3 small">
                                    <span class="me-3 fw-bold">
                                        <i class="fa-solid fa-building text-secondary me-1"></i>Company Name:
                                        {{ $post->company_name }}</span>
                                    <span class="me-3 fw-bold">
                                        <i class="fa-solid fa-location-dot text-danger me-1"></i>Location:
                                        {{ $post->location }}</span>
                                    <span class="fw-bold">
                                        <i class="fa-solid fa-sack-dollar text-success me-1"></i>Wage:
                                        ${{ $post->wage }}</span>
                                </div>
                            @endif

                            <div class="card-text text-truncate">Description about Job: {{ $post->description }}</div>
                        </div>

                        <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                            <form action="{{ route('posts.show', $post) }}" method="get" class="m-0">
                                <button type="submit"
                                    class="btn btn-sm btn-outline-{{ $post->user->role === 'owner' ? 'primary' : 'success' }}">
                                    View Details
                                </button>
                            </form>

                            <div>
                                <button class="btn btn-link text-muted p-0 me-2" title="Bookmark">
                                    <i class="fa-regular fa-bookmark"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-info">No posts founds yet.</div>
                @endforelse
            </div>

            {{-- right  side--}}
            <div class="col-md-4">
                @include('posts.sidebar')
            </div>
        </div>
    </div>
@endsection
