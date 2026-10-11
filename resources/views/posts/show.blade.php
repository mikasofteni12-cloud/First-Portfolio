@extends('layouts.app')

@section('content')
    <div class="container y-4">
        <div class="row justify-content-center">
            <div class="col-mb-8">

                {{-- back to index --}}
                <a href="{{ route('home') }}" class="btn btn-outline-secondary mb-3">
                    <i class="fa-solid fa-arrow-left me-1"></i>
                </a>

                {{-- show detail --}}
                <div
                    class="card shadow-sm border-start border-3 border-{{ $post->user->role === 'owner' ? 'primary-subtle' : 'success-subtle' }}">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                        <div class="d-flex align-items-center">
                            <i class="fa-solid fa-circle-user fa-2x me-2 text-secondary"></i>
                            <div>
                                <h6 class="mb-0 fw-bold">{{ $post->user->name }}</h6>
                                <small class="text-muted">Posted {{ $post->created_at->format('Y-m-d H:i') }}</small>
                            </div>
                        </div>

                        <span
                            class="badge text-dark bg-{{ $post->user->role === 'owner' ? 'primary-subtle' : 'success-subtle' }} border-0 py-2 px-3 fs-6">
                            {{ ucfirst($post->user->role) }}
                        </span>
                    </div>

                    <div class="card-body">
                        <h4 class="fw-bold mb-3">{{ $post->title }}</h4>
                        {{-- Only Owner --}}
                        @if ($post->company_name)
                            <div class="p-3 bg-light rounded mb-4">
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <i class="fa-solid fa-building text-secondary me-1"></i>
                                        <strong>Company: </strong>{{ $post->company_name }}
                                    </div>
                                    <div class="col-md-4">
                                        <i class="fa-solid fa-location-dot text-danger me-1"></i>
                                        <strong>Location: </strong>{{ $post->location }}
                                    </div>
                                    <div class="col-md-4">
                                        <i class="fa-solid fa-sack-dollar text-success me-1"></i>
                                        <strong>Wage: </strong>{{ $post->wage }}
                                    </div>
                                </div>
                            </div>
                        @endif

                        <h6 class="fw-bold text-secondary">Description</h6>
                        <p class="card-text fs-6 lh-lg" style="white-space: pre-wrap;">{{ $post->description }}</p>
                    </div>


                    {{-- footer /only the owner of the post can edit and delete --}}
                    <div class="card-footer bg-white d-flex justify-content-between align-items-center py-3">
                        <div>
                            {{-- if its not own post --}}
                            @if ($post->user_id !== Auth::id())
                                @if ($post->isBookmarkedBy(Auth::user()))
                                    {{-- already bookmarked --}}
                                    <form action="{{ route('posts.bookmark', $post->id) }}" method="post">
                                        @csrf
                                        <button type="submit" class="btn btn-secondary btn-sm me-1">
                                            <i class="fa-solid fa-bookmark me-1"></i>Bookmarked
                                        </button>
                                    </form>
                                @else
                                    {{-- Not bookmark Yet  --}}
                                    <form action="{{ route('posts.bookmark', $post->id) }}" method="post">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-secondary btn-sm me-1">
                                            <i class="fa-regular fa-bookmark me-1"></i>Bookmark
                                        </button>
                                    </form>
                                @endif
                            @endif
                        </div>

                        <div class="mb-2">
                            {{-- 職種バッジ（クリックで絞り込み） --}}
                            @if ($post->category)
                                <a href="{{ route('posts.index', ['category_id' => $post->category->id]) }}"
                                    class="badge bg-secondary-subtle text-dark border-1 text-decoration-none px-2 py-1">
                                    {{ $post->category->name }}
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
                                <span class="badge bg-light text-dark border me-2"><i
                                        class="fa-solid fa-location-dot"></i>&nbsp;{{ $post->location }}</span>
                            @endif
                        </div>

                        @if (Auth::check() && Auth::id() === $post->user_id)
                            <div class="d-flex me-2">
                                {{-- Edit button --}}
                                <a href="{{ route('posts.edit', $post) }}" class="btn btn-outline-warning me-1">
                                    <i class="fa-solid fa-pen-to-square me-1"></i> Edit
                                </a>

                                {{-- Delete to connect popup modal/delete.blade --}}
                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal"
                                    data-bs-target="#delete-post-{{ $post->id }}">
                                    <i class="fa-solid fa-trash-can me-1"></i> Delete
                                </button>
                            </div>

                            {{-- to connect modal folder --}}
                            @include('posts.modal.delete')
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
