{{-- Delete Post Modal --}}

<div class="modal fade" id="delete-post-{{ $post->id }}">
    <div class="modal-dialog">
        <div class="modal-content border-danger">
            <div class="modal-header border-danger">
                <h5 class="modal-title text-danger fw-bold">
                    <i class="fa-solid fa-circle-exclamation me-1"></i>Delete Post
                </h5>
            </div>

            <div class="modal-body">
                <p>Are you sure you want to delete this post?</p>
                <div class="mt-3">
                   <strong class="d-block text-dark mb-1">{{ $post->title }}</strong>
                    <p class="mt-1 text-muted">{{ $post->description }}</p>
                </div>
            </div>

            <div class="modal-footer border-0">
                <form action="{{ route('posts.destroy', $post->id) }}" method="post">
                    @csrf
                    @method('DELETE')

                    <button class="btn btn-outline-danger btn-sm" type="button" data-bs-dismiss="modal">Cancel</button>

                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
