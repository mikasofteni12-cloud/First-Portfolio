@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <h4>Welcome, {{ Auth::user()->name }}!</h4>

                        {{-- display UI based on user role --}}
                        @if (Auth::user()->role === 'owner')
                            <div class="alert alert-info mt-3">
                                <p>Create new job listings to recruit top talent and view posts from available candidates.</p>
                                <a href="{{ route('posts.create') }}" class="btn btn-primary">Create job listing</a>
                            </div>
                        @else
                            <div class="alert alert-success mt-3">
                                <p>Create a job-seeking profile to introduce yourself and search through active job listings.</p>
                                <a href="{{ route('posts.create') }}" class="btn btn-outline-success">Create your seeking profile</a>
                            </div>
                        @endif



                        {{-- show posts,Depends on role  --}}
                        <h5 class="fw-bold mb-3">
                            @if (Auth::user()->role === 'owner')
                                <i class="fa-solid fa-users me-1"></i> Job Seekers Posts
                            @else
                                <i class="fa-solid fa-briefcase me-1"></i> Available Job Offerings
                            @endif
                        </h5>

                        <hr>

                        <!-- カテゴリー絞り込みバッジエリア -->
                        <div class="mb-3 p-1 bg-light rounded-border">
                            <div class="fw-bold mb-2 text-dark d-flex align-items-center">
                                <i class="fa-solid fa-font-awesome"></i>&nbsp;Categories:
                            </div>
                            {{-- index badges --}}
                            <div class="d-flex flex-wrap me-2">
                                <a href="{{ route('home',array_diff_key(request()->query(), ['category_id' => ''])) }}"
                                    class="badge my-1 {{ !request('category_id') ? 'bg-dark' : 'bg-outline-primary text-dark border' }} text-decoration-none me-1 p-2">
                                    All
                                </a>
                                {{-- each categories --}}
                                @foreach ($categories as $category)
                                    <a href="{{ route('home', array_merge(request()->query(), ['category_id' => $category->id])) }}"
                                        class="badge my-1 {{ request('category_id') == $category->id ? 'bg-dark' : 'bg-outline-primary text-dark border' }} text-decoration-none me-1 p-2">
                                        {{ $category->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        <hr>

                        {{-- filter location --}}
                        <div class="mb-3 p-1 bg-light rounded-border">
                            <div class="fw-bold mb-2 text-dark d-flex align-items-center">
                                <i class="fa-solid fa-location-dot"></i>&nbsp;Location:
                            </div>
                            {{-- index badges --}}
                            <div class="d-flex flex-wrap me-2">
                                <a href="{{ route('home', array_diff_key(request()->query(), ['location' => ''])) }}"
                                    class="badge my-1 {{ !request('location') ? 'bg-dark' : 'bg-outline-primary text-dark border' }} text-decoration-none me-1 p-2">
                                    All
                                </a>
                                {{-- each location --}}
                                @foreach ($locations as $location)
                                    <a href="{{ route('home', array_merge(request()->query(), ['location' => $location])) }}"
                                        class="badge my-1 {{ request('location') == $location ? 'bg-dark' : 'bg-outline-primary text-dark border' }} text-decoration-none me-1 p-2">
                                        {{ $location }}
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        <hr>

                        {{-- show the posts --}}
                        @forelse ($posts as $post)
                            {{-- distinguish color depends on seeker or owner --}}
                            @php
                                $themeColor = $post->user->role === 'owner' ? 'primary' : 'success';
                            @endphp

                            <div class="card shadow-sm mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h5 class="card-title fw-bold text-{{ $themeColor }} mb-0">{{ $post->title }}
                                        </h5>
                                        <small class="text-muted">{{ $post->created_at->diffForHumans() }}</small>
                                    </div>

                                    <p class="card-text text-secondary mb-2">{{ Str::limit($post->description, 20) }}</p>

                                    {{-- owner / seeker  --}}
                                    <div class="small text-muted mb-3">
                                        @if ($post->company_name)
                                            <span class="me-3"><i
                                                    class="fa-solid fa-building text-secondary me-1"></i>{{ $post->company_name }}</span>
                                        @endif
                                        @if ($post->location)
                                            <span class="me-3"><i
                                                    class="fa-solid fa-location-dot text-danger me-1"></i>{{ $post->location }}</span>
                                        @endif
                                        @if ($post->wage)
                                            <span><i
                                                    class="fa-solid fa-sack-dollar text-success me-1"></i>{{ $post->wage }}</span>
                                        @endif
                                    </div>

                                    {{-- details --}}
                                    <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                        {{-- LEFT:Posted By name --}}

                                        <span class="badge bg-light text-secondary fw-bold border">
                                            Posted by: {{ $post->user->name }}
                                        </span>

                                        <div class="align-items-center">
                                            {{-- 職種バッジ（クリックで絞り込み） --}}
                                            @if ($post->category)
                                                <a href="{{ route('home', ['category_id' => $post->category->id]) }}"
                                                    class="badge text-dark bg-secondary-subtle border text-decoration-none px-2 py-1">
                                                    <i
                                                        class="fa-solid fa-font-awesome"></i>&nbsp;{{ $post->category->name }}
                                                </a>
                                            @endif
                                            <span class="text-secondary">/<span>
                                            {{-- 雇用形態バッジ --}}
                                            <span class="badge bg-light text-dark border"><i
                                                    class="fa-solid fa-business-time"></i>&nbsp;{{ $post->employment_type }}</span>

                                            {{-- 勤務スタイルバッジ --}}
                                            <span class="badge bg-light text-dark border"><i
                                                    class="fa-solid fa-left-right"></i>&nbsp;{{ $post->work_style }}</span>

                                            {{-- 勤務地 --}}
                                            @if ($post->location)
                                                <span class="badge bg-light text-dark border"><i
                                                        class="fa-solid fa-location-dot"></i>&nbsp;{{ $post->location }}</span>
                                            @endif
                                        </div>




                                        {{-- RIGHT:detail , bookmark --}}
                                        <div class="d-flex align-items-center me-1">
                                            {{-- View Details --}}
                                            <a href="{{ route('posts.show', $post->id) }}"
                                                class="btn btn-outline-{{ $themeColor }} btn-sm me-1">
                                                View Details
                                            </a>
                                            {{-- Bookmark --}}
                                            @if ($post->isBookmarkedBy(Auth::user()))
                                                {{-- already bookmarked --}}
                                                <form action="{{ route('posts.bookmark', $post->id) }}" method="post"
                                                    class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-secondary btn-sm me-1"
                                                        title="Remove Bookmark">
                                                        <i class="fa-solid fa-bookmark fa-lg text-secondary me-1"></i>
                                                    </button>
                                                </form>
                                            @else
                                                {{-- Not bookmark Yet  --}}
                                                <form action="{{ route('posts.bookmark', $post->id) }}" method="post"
                                                    class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-secondary btn-sm me-1"
                                                        title="Add Bookmark">
                                                        <i class="fa-regular fa-bookmark me-1 fa-lg"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            {{-- If No Post Uploaded Yet --}}
                            <div class="card card-body text-center py-4 text-muted border-dashed">
                                <i class="fa-regular fa-folder-open fa-2x mb-2"></i>
                                <p class="mb-0">No Posts Uploaded Yet At The Moment.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- RIGHT SIDE TO  sidebar.blade --}}
            <div class="col-md-4">
                @include('posts.sidebar')
            </div>
        </div>
    </div>
@endsection
