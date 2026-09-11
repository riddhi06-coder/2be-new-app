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
                <div class="col-6"><h3>Edit Document</h3></div>
                <div class="col-6">
                    <ol class="breadcrumb justify-content-end">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa fa-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.documents.index') }}">Documents</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>
        </div>

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
                                        <option value="0" {{ old('is_public', $document->is_public ? '1' : '0') == '0' ? 'selected' : '' }}>Personal — selected employees only</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3" id="owner_wrap" style="display:none;">
                                    <label class="form-label">Employees <span class="text-danger">*</span></label>
                                    @php $selectedIds = old('user_ids', $assignedIds ?? []); @endphp
                                    <div class="assignee-picker @error('user_ids') is-invalid @enderror">
                                        <div class="assignee-picker__search">
                                            <i class="fa fa-search"></i>
                                            <input type="text" class="assignee-search" placeholder="Search employees...">
                                        </div>
                                        <div class="assignee-list">
                                            @forelse($employees as $emp)
                                                <label class="assignee-item" data-name="{{ strtolower($emp->name.' '.$emp->email) }}">
                                                    <input type="checkbox" name="user_ids[]" value="{{ $emp->id }}" {{ collect($selectedIds)->contains($emp->id) ? 'checked' : '' }}>
                                                    <span class="assignee-item__text">{{ $emp->name }} <small>{{ $emp->email }}</small></span>
                                                </label>
                                            @empty
                                                <div class="assignee-empty">No active employees found.</div>
                                            @endforelse
                                        </div>
                                    </div>
                                    @error('user_ids')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    @error('user_ids.*')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    <small class="text-muted">Tick every employee who should see this document.</small>
                                </div>

                                <div class="col-12 mb-3" id="ack_wrap" style="display:none;">
                                    <div class="p-3 rounded" style="background:#f6f7f9; border:1px solid #eef0f4;">
                                        <div class="form-check">
                                            <input type="hidden" name="requires_acknowledgment" value="0">
                                            <input class="form-check-input @error('requires_acknowledgment') is-invalid @enderror" type="checkbox" id="requires_acknowledgment" name="requires_acknowledgment" value="1" {{ old('requires_acknowledgment', $document->requires_acknowledgment) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold" for="requires_acknowledgment">
                                                Require each employee to read &amp; sign (acknowledge) this document
                                            </label>
                                            @error('requires_acknowledgment')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                        </div>
                                        <small class="text-muted d-block mt-1"><i class="fa fa-file-pdf-o me-1"></i>PDF only. Each employee types their name to sign; a stamped signed copy is then filed under their profile.</small>
                                        <div id="ack_due_wrap" class="mt-3" style="display:none; max-width:260px;">
                                            <label class="form-label mb-1">Sign-by date <small class="text-muted">(optional)</small></label>
                                            <input type="date" name="acknowledgment_due" class="form-control form-control-sm" value="{{ old('acknowledgment_due', optional($document->acknowledgment_due)->toDateString()) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                            <a href="{{ route('admin.documents.index') }}" class="btn btn-light">Cancel</a>
                            @if($document->requires_acknowledgment)
                                <a href="{{ route('admin.documents.acknowledgments', $document) }}" class="btn btn-success float-end">
                                    <i class="fa fa-list-alt me-1"></i> View Sign-Off Status
                                    <span class="badge bg-white text-success ms-1">{{ $document->acknowledgments()->count() }} signed</span>
                                </a>
                            @endif
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
        var ackWrap = document.getElementById('ack_wrap');
        function toggleOwner() {
            var personal = (access.value === '0');
            ownerWrap.style.display = personal ? '' : 'none';
            if (ackWrap) ackWrap.style.display = personal ? '' : 'none';
        }
        access.addEventListener('change', toggleOwner);
        toggleOwner();
    })();

    // Show the sign-by date only when read & sign is required
    (function () {
        var chk = document.getElementById('requires_acknowledgment');
        var dueWrap = document.getElementById('ack_due_wrap');
        if (!chk || !dueWrap) return;
        function toggleDue() { dueWrap.style.display = chk.checked ? '' : 'none'; }
        chk.addEventListener('change', toggleDue);
        toggleDue();
    })();

    // Employee search filter for the assignee picker
    (function () {
        var search = document.querySelector('.assignee-search');
        if (!search) return;
        search.addEventListener('input', function () {
            var q = this.value.toLowerCase().trim();
            document.querySelectorAll('.assignee-item').forEach(function (item) {
                item.style.display = item.getAttribute('data-name').indexOf(q) !== -1 ? '' : 'none';
            });
        });
    })();
</script>
</body>
</html>
