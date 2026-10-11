@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">

                {{-- back to index --}}
                <a href="{{ route('home') }}" class="btn btn-outline-secondary mb-3">
                    <i class="fa-solid fa-arrow-left me-1"></i>
                </a>

                {{-- my profile --}}
                <div class="card shadow-sm border-2">
                    <div class="card-header bg-white fw-bold py-3 d-flex justify-content-between align-items-center">
                        <span><i class="fa-solid fa-user me-2"></i>My Profile</span>
                        <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fa-solid fa-pen-to-square me-1"></i>Edit Profile
                        </a>
                    </div>

                    <div class="card-body py-4">
                        {{-- Profile image/avatar --}}
                        <div class="row align-items-center mb-4 p-2">
                            <div class="col-4 text-md-end mb-4 mb-md-0">
                                @if ($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}"
                                        class="rounded-circle img-fluid img-thumbnail shadow-sm"
                                        style="width: 100%; max-width: 130px; aspect-ratio: 1/1; object-fit: cover;">
                                @else
                                    <i class="fa-solid fa-circle-user fa-6x text-secondary"></i>
                                @endif
                            </div>

                            {{-- Name --}}
                            <div class="col-8">
                                <div class="d-flex flex-wrap align-items-center justify-content-between mb-2">
                                    <h2 class="fw-bold mb-0 me-3">{{ $user->name }}</h2>
                                </div>
                                {{-- Email address --}}
                                <p class="text-muted fs-6 mb-2">
                                    <i class="fa-regular fa-envelope me-1"></i>{{ $user->email }}
                                </p>

                                {{-- User Type --}}
                                <div class="d-flex align-items-center me-2">
                                    <span
                                        class="badge fs-6 px-3 py-2 {{ $user->role === 'owner' ? 'bg-primary-subtle text-dark' : 'bg-success-subtle text-dark' }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                    <small class="text-muted ms-2">Joined {{ $user->created_at->format('M Y') }}</small>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- My Post show detail --}}
                <div class="card shadow-sm my-2">
                    <div
                        class="card-header fw-bold d-flex align-items-center justify-content-between {{ Auth::user()->role === 'owner' ? 'bg-primary-subtle' : 'bg-success-subtle' }}">
                        <div>
                            <i class="fa-solid fa-list me-2"></i>&nbsp;My Post list
                        </div>
                    </div>

                    @if (isset($my_posts))
                        <div class="d-flex flex-column mb-3 gap-2 p-3">
                            @forelse ($my_posts as $post)
                                {{-- if my post exist, show each post --}}
                                <div
                                    class="card shadow-sm border-3 border-{{ $post->user->role === 'owner' ? 'primary-subtle' : 'success-subtle' }}">
                                    {{-- card headertitle/created_at --}}
                                    <div
                                        class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                                        <h4 class="fw-bold">
                                            <a href="{{ route('posts.show', $post->id) }}"
                                                class="text-decoration-none text-dark">
                                                {{ $post->title }}
                                            </a>
                                        </h4>
                                        <small class="text-muted text-end">Posted
                                            {{ $post->created_at->format('Y-m-d H:i') }}</small>
                                    </div>

                                    {{-- body : details&description --}}
                                    <div class="card-body">
                                        {{-- for only Owner account --}}
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
                                        <p class="card-text text-start fs-6 lh-md" style="white-space: pre-wrap;">
                                            {{ $post->description }}</p>
                                    </div>

                                    {{-- footer /onlt the owner of the post can edit and delete --}}
                                    <div class="card-footer bg-white d-flex justify-content-between align-items-center py-3">
                                        <div class="mb-2">
                                            {{-- 職種バッジ（クリックで絞り込み） --}}
                                            @if ($post->category)
                                                <a href="{{ route('posts.index', ['category_id' => $post->category->id]) }}"
                                                    class="badge bg-secondary-subtle text-dark border text-decoration-none px-2 py-1">
                                                    {{ $post->category->name }}
                                                </a>
                                            @endif
                                            <span class="text-secondary">/<span>
                                            {{-- 雇用形態バッジ --}}
                                            <span class="badge bg-light text-dark border"><i class="fa-solid fa-business-time"></i>&nbsp;{{ $post->employment_type }}</span>

                                            {{-- 勤務スタイルバッジ --}}
                                            <span class="badge bg-light text-dark border"><i class="fa-solid fa-left-right"></i>&nbsp;{{ $post->work_style }}</span>

                                            {{-- 勤務地 --}}
                                            @if ($post->location)
                                                <span class="badge bg-light text-dark border"><i class="fa-solid fa-location-dot"></i>&nbsp;{{ $post->location }}</span>
                                            @endif
                                        </div>

                                        {{-- footer right side edit/delete --}}
                                        @if (Auth::check() && Auth::id() === $post->user_id)
                                            <div class="d-flex me-2">
                                                {{-- Edit button --}}
                                                <a href="{{ route('posts.edit', $post) }}"
                                                    class="btn btn-outline-warning me-1">
                                                    <i class="fa-solid fa-pen-to-square me-1"></i> Edit
                                                </a>

                                                {{-- Delete to connect popup modal/delete.blade --}}
                                                <button type="button" class="btn btn-sm btn-outline-danger"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#delete-post-{{ $post->id }}">
                                                    <i class="fa-solid fa-trash-can me-1"></i> Delete
                                                </button>
                                            </div>

                                            {{-- to connect modal folder --}}
                                            @include('posts.modal.delete')
                                        @endif
                                    </div>
                                </div>
                            @empty
                                {{-- if not post uplaoded yet --}}
                                <div class="text-center text-muted py-4 bg-light rounded">
                                    <small>Not posts Yet.</small>
                                </div>
                            @endforelse
                        </div>
                    @endif

                </div>

                {{-- bookmarked list --}}
                <div class="card shadow-sm my-2">
                    <div
                        class="card-header fw-bold d-flex justify-content-between align-items-center {{ Auth::user()->role === 'owner' ? 'bg-success-subtle' : 'bg-primary-subtle' }}">
                        <div>
                            <i class="fa-regular fa-bookmark me-1"></i>&nbsp;Bookmark List
                        </div>
                    </div>

                    <div class="d-flex flex-column mb-3 gap-2 p-3">
                        @forelse ($bookmarkedPosts as $bookmarkedPost)
                            <div
                                class="card shadow-sm border-3 border-{{ $bookmarkedPost->user->role === 'owner' ? 'primary-subtle' : 'success-subtle' }}">
                                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                                    <h4 class="fw-bold">
                                        <a href="{{ route('posts.show', $bookmarkedPost->id) }}"
                                            class="text-decoration-none text-dark">
                                            {{ $bookmarkedPost->title }}</a>
                                    </h4>
                                    <small class="text-muted text-end">
                                        {{ $bookmarkedPost->created_at->diffForHumans() }}</small>
                                </div>

                                {{-- Body --}}
                                <div class="card-body">
                                    {{-- for only owner account --}}
                                    @if ($bookmarkedPost->company_name)
                                        <div class="p-3 bg-light rounded mb-4">
                                            <div class="row g-2">
                                                <div class="col-md-4">
                                                    <i class="fa-solid fa-building text-secondary me-1"></i>
                                                    <strong>Company: </strong>{{ $bookmarkedPost->company_name }}
                                                </div>
                                                <div class="col-md-4">
                                                    <i class="fa-solid fa-location-dot text-danger me-1"></i>
                                                    <strong>Location: </strong>{{ $bookmarkedPost->location }}
                                                </div>
                                                <div class="col-md-4">
                                                    <i class="fa-solid fa-sack-dollar text-success me-1"></i>
                                                    <strong>Wage: </strong>{{ $bookmarkedPost->wage }}
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <h6 class="fw-bold text-secondary">Description</h6>
                                    <p class="card-text text-start fs-6 lh-md" style="white-space: pre-wrap;">
                                        {{ $bookmarkedPost->description }} </p>
                                </div>

                                {{-- footer bookmark --}}
                                <div class="card-footer bg-white d-flex  justify-content-between align-items-center py-3">
                                   {{-- Posted by  --}}
                                    <span class="badge bg-light text-secondary text-start fw-bold border">
                                        Posted by: {{ $bookmarkedPost->user->name }}
                                    </span>
                                    <div class="mb-2">
                                            {{-- 職種バッジ（クリックで絞り込み） --}}
                                            @if ($post->category)
                                                <a href="{{ route('posts.index', ['category_id' => $post->category->id]) }}"
                                                    class="badge bg-primary text-decoration-none">
                                                    {{ $post->category->name }}
                                                </a>
                                            @endif

                                            {{-- 雇用形態バッジ --}}
                                            <span class="badge bg-light text-dark border"><i class="fa-solid fa-business-time"></i>&nbsp;{{ $post->employment_type }}</span>

                                            {{-- 勤務スタイルバッジ --}}
                                            <span class="badge bg-light text-dark border"><i class="fa-solid fa-left-right"></i>&nbsp;{{ $post->work_style }}</span>

                                            {{-- 勤務地 --}}
                                            @if ($post->location)
                                                <span class="badge bg-light text-dark border"><i class="fa-solid fa-location-dot"></i>&nbsp;{{ $post->location }}</span>
                                            @endif
                                        </div>

                                    {{-- Bookmark --}}
                                    @if ($bookmarkedPost->isBookmarkedBy(Auth::user()))
                                        {{-- already bookmarked --}}
                                        <form action="{{ route('posts.bookmark', $bookmarkedPost->id) }}" method="post"
                                            class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-secondary btn-sm me-1"
                                                title="Remove Bookmark">
                                                <i
                                                    class="fa-solid fa-bookmark fa-lg text-secondary me-1"></i>&nbsp;Bookmarked
                                            </button>
                                        </form>
                                    @else
                                        {{-- Not bookmark Yet  --}}
                                        <form action="{{ route('posts.bookmark', $bookmarkedPost->id) }}" method="post"
                                            class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-secondary btn-sm me-1"
                                                title="Add Bookmark">
                                                <i class="fa-regular fa-bookmark me-1 fa-lg"></i>&nbsp;Bookmark
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="card-body text-center text-muted py-4">
                                <small>No Bookmarked Posts Yet.</small>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>









        </div>
    </div>
    </div>
@endsection
