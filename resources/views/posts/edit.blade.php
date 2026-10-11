@extends('layouts.app')

@section('content')
    
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="cad-header bg-white py-3">
                    <h4 class="mb-0 text-warning fw-bold">&nbsp; Edit Post</h4>
                </div>

                <div class="card-body">
                    <form action="{{ route('posts.update', $post->id) }}" method="post">
                        @csrf
                        @method('PATCH')

                        {{-- title --}}
                        <div class="mb-3">
                            <label for="title" class="fofrm-label fw-bold">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $post->title )}}" required>
                        </div>

                        {{-- details/description --}}
                        <div class="mb-3">
                            <label for="description" class="form-label fw-bold">Description</label>
                            <textarea name="description" id="description" rows="5" class="form-control" required>{{ old('description', $post->description) }}</textarea>
                        </div>

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
                                            {{ old('location' , $post->location) == $loc ? 'selected' : '' }}>
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

                        {{-- for owner --}}
                        @if (Auth::user()->role === 'owner')
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="company_name" class="form-label fw-bold">Company Name</label>
                                    <input type="text" name="company_name" id="company_name" class="form-control" value="{{ old('company_name', $post->company_name) }}">
                                </div>
                                <div class="col-md-4">
                                    <label for="location" class="form-label fw-bold">Location</label>
                                    <input type="text" name="location" id="location" class="form-control" value="{{ old('location', $post->location) }}">
                                </div>
                                <div class="col-md-4">
                                    <label for="wage" class="form-label fw-bold">Wage</label>
                                    <input type="number" name="wage" id="wage" class="form-control" value="{{ old('wage', $post->wage) }}">
                                </div>
                                <div class="col-md-4">
                                    <label for="phone" class="form-label fw-bold">Phone Number</label>
                                    <input type="number" name="phone" id="phone" class="form-control" value="{{ old('phone', $post->phone) }}">
                                </div>
                                <div class="col-md-4">
                                    <label for="email" class="form-label fw-bold">Email Address</label>
                                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $post->email) }}">
                                </div>
                            </div>
                        @endif

                        {{-- button --}}
                        <div class="d-flex justify-content-end align-items-center me-1 mt-4">
                            {{-- cancel --}}
                            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary me-1">Cancel</a>
                            {{-- save changes --}}
                            <button type="submit" class="btn btn-warning px-4 fw-bold">Update Post</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection