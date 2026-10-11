{{-- DeleteAccount Modal --}}

<div class="modal fade" id="deleteAccountModal">
    <div class="modal-dialog">
        <div class="modal-content border-danger">
            <div class="modal-header border-danger">
                <h5 class="modal-title text-danger fw-bold">
                    <i class="fa-solid fa-circle-exclamation me-1"></i>Delete Account
                </h5>
            </div>

            <div class="modal-body fw-bold">
                <p>Once your account is deleted, all of its resources and data will be permanently erased.</p>
                <div class="mt-3">
                    <div
                        class="card shadow-sm mb-4 border-2 {{ Auth::user()->role === 'owner' ? 'border-primary-subtle' : 'border-success-subtle' }}">
                        <div class="card-body d-flex align-items-center">
                            <i class="fa-solid fa-circle-user fa-3x text-secondary me-3"></i>
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
                </div>
            </div>

            <div class="modal-footer border-0">
                <form action="{{ route('users.destroy', $user->id) }}" method="post">
                    @csrf
                    @method('DELETE')

                    <button class="btn btn-outline-danger btn-sm" type="button" data-bs-dismiss="modal">Cancel</button>

                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
