@extends('layouts.app')

@section('content')

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h4 class="mb-4 fw-bold text-primary">Create New Post</h4>
                    </div>

                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger mb-4">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('posts.store') }}" method="post">
                            @csrf

                            {{-- title --}}
                            <div class="mb-3">
                                <label for="title" class="form-label fw-bold">Title</label>
                                <input type="text" name="title" id="title" class="form-control"
                                    value="{{ old('title') }}" placeholder="Enter Post Title" required autofocus>
                            </div>

                            {{-- for Owner --}}
                            @if (Auth::user()->role === 'owner')
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label for="company_name" class="form-label fw-bold">Company Name</label>
                                        <input type="text" name="company_name" id="company_name" class="form-control"
                                            value="{{ old('company_name') }}" placeholder="e.g. ABC Cafe">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="wage" class="form-label fw-bold">Wage</label>
                                        <input type="number" name="wage" id="wage" class="form-control"
                                            value="{{ old('wage') }}" placeholder="e.g. $17.95 /hr">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="phone" class="form-label fw-bold">Phone Number</label>
                                        <input type="number" name="phone" id="phone" class="form-control"
                                            value="{{ old('phone') }}" placeholder="(000)000-000">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="email" class="form-label fw-bold">Email Address</label>
                                        <input type="email" name="email" id="email" class="form-control"
                                            value="{{ old('email') }}" placeholder="e.g. xxx@xxx.com">
                                    </div>
                                </div>
                            @endif

                            {{-- Details --}}
                            <div class="mb-3">
                                <label for="description" class="form-label fw-bold">Description</label>
                                <textarea name="description" id="description" class="form-control" rows="5"
                                    placeholder="Write post details here..." required>{{ old('description') }}</textarea>
                            </div>

                            {{--  --}}
                            <!-- Category (職種) -->
                            <div class="mb-3">
                                <label for="category_id" class="form-label fw-bold">Category</label>
                                <select name="category_id" id="category_id" class="form-select" required>
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('category_id', $post->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Location (勤務地 / 希望地域) -->
                            <div class="mb-3">
                                <label for="location" class="form-label fw-bold">Location</label>
                                <select name="location" id="location" class="form-select" required>
                                    <option value="">Select Location</option>
                                    @foreach (\App\Models\Post::$locations as $loc)
                                        <option value="{{ $loc }}"
                                            {{ old('location') == $loc ? 'selected' : '' }}>
                                            {{ $loc }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Work Style (勤務スタイル) -->
                            <div class="mb-3">
                                <label for="work_style" class="form-label fw-bold">Work Style</label>
                                <select name="work_style" id="work_style" class="form-select" required>
                                    @foreach (['On-site', 'Remote', 'Hybrid'] as $style)
                                        <option value="{{ $style }}"
                                            {{ old('work_style', $post->work_style ?? 'On-site') == $style ? 'selected' : '' }}>
                                            {{ $style }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Employment Type (雇用形態) -->
                            <div class="mb-3">
                                <label for="employment_type" class="form-label fw-bold">Employment Type</label>
                                <select name="employment_type" id="employment_type" class="form-select" required>
                                    @foreach (['Full-time', 'Part-time'] as $type)
                                        <option value="{{ $type }}"
                                            {{ old('employment_type', $post->employment_type ?? 'Full-time') == $type ? 'selected' : '' }}>
                                            {{ $type }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- button --}}
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <a href="{{ route('posts.index') }}" class="btn btn-outline-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">Create Post</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
