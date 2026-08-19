<!doctype html>
<html lang="en">
<head>
    @include('components.backend.head')
</head>
<body>
@include('components.backend.header')
@include('components.backend.sidebar')

<div class="page-body">
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6"><h3>New Announcement</h3></div>
                <div class="col-6">
                    <ol class="breadcrumb justify-content-end">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa fa-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.announcements.index') }}">Announcements</a></li>
                        <li class="breadcrumb-item active">New</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.announcements.store') }}" method="POST" enctype="multipart/form-data" class="theme-form">
                            @csrf
                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    <label class="form-label">Title <span class="text-danger">*</span></label>
                                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                           value="{{ old('title') }}" required placeholder="e.g. Office closed on Friday">
                                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Publish Date</label>
                                    <input type="datetime-local" name="published_at" class="form-control @error('published_at') is-invalid @enderror"
                                           value="{{ old('published_at') }}">
                                    <small class="text-muted">Leave blank to publish now. Drives newest-first order.</small>
                                    @error('published_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label">Message <span class="text-danger">*</span></label>
                                    <textarea name="body" class="form-control ckeditor @error('body') is-invalid @enderror" rows="6" placeholder="Write the announcement...">{{ old('body') }}</textarea>
                                    @error('body')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Image <small class="text-muted">(optional)</small></label>
                                    <input type="file" name="image" id="imageInput" class="form-control @error('image') is-invalid @enderror" accept=".jpg,.jpeg,.png,.gif,.webp">
                                    <small class="text-muted">JPG, PNG, GIF or WEBP. Max {{ round(config('uploads.image_max_kb') / 1024) }} MB.</small>
                                    @error('image')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    <div class="mt-2">
                                        <img id="imagePreview" src="" alt="" class="form-image-preview d-none">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Status</label>
                                    <div class="form-check form-switch mt-2">
                                        <input type="hidden" name="is_active" value="0">
                                        <input class="form-check-input" type="checkbox" id="a-active" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="a-active">Active <small class="text-muted">(shown on the employee dashboard)</small></label>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Publish Announcement</button>
                            <a href="{{ route('admin.announcements.index') }}" class="btn btn-light">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('components.backend.footer')
</div>
</div>

@include('components.backend.main-js')
<script>
    (function () {
        var input = document.getElementById('imageInput');
        var preview = document.getElementById('imagePreview');
        if (!input) return;
        input.addEventListener('change', function () {
            var file = this.files && this.files[0];
            if (file) {
                preview.src = URL.createObjectURL(file);
                preview.classList.remove('d-none');
            } else {
                preview.classList.add('d-none');
            }
        });
    })();
</script>
</body>
</html>
