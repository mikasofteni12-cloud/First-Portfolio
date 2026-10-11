{{-- RIGHT SIDE --}}
{{-- Profile --}}
<a href="{{ route('profile.show', Auth::id()) }}" class="text-decoration-none text-dark">
    <div
        class="card shadow-sm mb-4 mt-2 border-2 {{ Auth::user()->role === 'owner' ? 'border-primary-subtle' : 'border-success-subtle' }}">
        <div class="card-body d-flex align-items-center">
            @if (Auth::user()->avatar)
                <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}"
                    class="rounded-circle me-3" style="width: 52px; height: 52px; object-fit: cover;">
            @else
                <i class="fa-solid fa-circle-user fa-3x text-secondary me-3"></i>
            @endif
            <div>
                <h6 class="mb-1 fw-bold">{{ Auth::user()->name }}</h6>
                <small class="text-muted">{{ Auth::user()->email }}</small>
                <span
                    class="badge d-block mt-1 {{ Auth::user()->role === 'owner' ? 'bg-primary-subtle text-dark' : 'bg-success-subtle text-dark' }}"
                    style="width: fit-content;">
                    {{ ucfirst(Auth::user()->role) }}
                </span>
            </div>
        </div>
    </div>
</a>

{{-- my posts list --}}
<div class="card shadow-sm mb-4">
    <div
        class="card-header fw-bold d-flex align-items-center justify-content-between {{ Auth::user()->role === 'owner' ? 'bg-primary-subtle' : 'bg-success-subtle' }}">
        <div>
            <i class="fa-solid fa-list me-2"></i> My Post list
        </div>
        <span class="badge bg-secondary rounded-pill">
            {{ isset($my_posts) ? $my_posts->count() : 0 }}
        </span>
    </div>
    <div class="card-body p-0">
        @if (isset($my_posts) && $my_posts->isNotEmpty())
            <div class="list-group list-group-flush">
                @foreach ($my_posts as $my_post)
                    <a href="{{ route('posts.show', $my_post->id) }}"
                        class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3">
                        <div class="text-truncate pe-2">
                            <div class="fw-bold text-dark text-truncate">{{ $my_post->title }}</div>
                            <small class="text-muted">{{ $my_post->created_at->diffForHumans() }}</small>
                        </div>
                        <i class="fa-solid fa-chevron-right text-muted small"></i>
                    </a>
                @endforeach
            </div>
        @else
            <div class="text-center text-muted">
                <small>Not Posts Yet.</small>
            </div>
        @endif
    </div>
</div>

{{-- Bookmark List --}}
<div class="card shadow-sm">
    <div
        class="card-header fw-bold d-flex justify-content-between align-items-center {{ Auth::user()->role === 'owner' ? 'bg-success-subtle' : 'bg-primary-subtle' }}">
        <div>
            <i class="fa-regular fa-bookmark me-1"></i>&nbsp;Bookmark List
        </div>
        <span class="badge bg-secondary rounded-pill">{{ $bookmarkedPosts->count() }}</span>
    </div>
    <div class="list-group list-group-flush">
        @forelse ($bookmarkedPosts as $bookmarkedPost)
            <a href="{{ route('posts.show', $bookmarkedPost->id) }}"
                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3">
                <div>
                    <div class="text-decoration-none text-dark fw-bold text-truncate me-2">
                        {{ $bookmarkedPost->title }}
                    </div>
                    <small class="text-muted">
                        {{ $bookmarkedPost->created_at->diffForHumans() }}
                    </small>
                </div>
                <i class="fa-solid fa-chevron-right text-muted small"></i>
            </a>
        @empty
            <div class="card-body text-center text-muted py-4">
                <small>No Bookmarked Posts Yet.</small>
            </div>
        @endforelse
    </div>
</div>
