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
        <div class="page-title"><div class="row"><div class="col-12"><h3>Edit Document — {{ $document->title }}</h3></div></div></div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.documents.update', $document) }}" method="POST" enctype="multipart/form-data" class="theme-form">
                            @csrf @method('PUT')
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Title <span class="text-danger">*</span></label>
                                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                           value="{{ old('title', $document->title) }}" required>
                                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Folder <span class="text-danger">*</span></label>
                                    <select name="document_category_id" class="form-control @error('document_category_id') is-invalid @enderror" required>
                                        <option value="">-- Select a folder --</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}" {{ old('document_category_id', $document->document_category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('document_category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Replace File <small class="text-muted">(leave blank to keep current)</small></label>
                                    <input type="file" name="file" class="form-control @error('file') is-invalid @enderror"
                                           accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png">
                                    <small class="text-muted">Current: <a href="{{ route('admin.documents.download', $document) }}">{{ $document->original_name ?: 'file' }}</a> ({{ $document->readable_size }})</small>
                                    @error('file')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Access <span class="text-danger">*</span></label>
                                    <select name="is_public" id="is_public" class="form-control">
                                        <option value="1" {{ old('is_public', $document->is_public ? '1' : '0') == '1' ? 'selected' : '' }}>Public — visible to everyone</option>
                                        <option value="0" {{ old('is_public', $document->is_public ? '1' : '0') == '0' ? 'selected' : '' }}>Personal — one employee only</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3" id="owner_wrap" style="display:none;">
                                    <label class="form-label">Employee <span class="text-danger">*</span></label>
                                    <select name="user_id" class="form-control @error('user_id') is-invalid @enderror">
                                        <option value="">-- Select the employee --</option>
                                        @foreach($employees as $emp)
                                            <option value="{{ $emp->id }}" {{ old('user_id', $document->user_id) == $emp->id ? 'selected' : '' }}>{{ $emp->name }} ({{ $emp->email }})</option>
                                        @endforeach
                                    </select>
                                    @error('user_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    <small class="text-muted">Only this employee (and admins) will be able to see this document.</small>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                            <a href="{{ route('admin.documents.index') }}" class="btn btn-light">Cancel</a>
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
        var access = document.getElementById('is_public');
        var ownerWrap = document.getElementById('owner_wrap');
        function toggleOwner() {
            ownerWrap.style.display = (access.value === '0') ? '' : 'none';
        }
        access.addEventListener('change', toggleOwner);
        toggleOwner();
    })();
</script>
</body>
</html>
