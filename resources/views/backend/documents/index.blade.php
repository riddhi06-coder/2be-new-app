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
                <div class="col-6"><h3>Documents</h3></div>
                <div class="col-6 text-end">
                    @if(auth()->user()->hasPermission('documents.create'))
                        <a href="{{ route('admin.documents.create') }}" class="btn btn-primary">+ Upload Document</a>
                    @endif
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="basic-1" class="display table table-hover">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Folder</th>
                                        <th>Access</th>
                                        <th class="text-end" style="min-width:200px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($documents as $doc)
                                        <tr>
                                            <td>{{ $doc->title }}</td>
                                            <td>{{ $doc->category->name ?? '—' }}</td>
                                            <td>
                                                @if($doc->is_public)
                                                    <span class="badge bg-info">Public</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">Personal</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <div class="d-flex gap-1 justify-content-end">
                                                    <button type="button" class="btn btn-sm btn-outline-secondary preview-btn"
                                                            data-url="{{ asset($doc->file_path) }}"
                                                            data-mime="{{ $doc->mime_type }}"
                                                            data-title="{{ $doc->title }}"
                                                            title="Preview" data-bs-toggle="tooltip">
                                                        <i class="fa fa-eye"></i>
                                                    </button>
                                                    <a href="{{ route('admin.documents.download', $doc) }}" class="btn btn-sm btn-outline-secondary" title="Download" data-bs-toggle="tooltip">
                                                        <i class="fa fa-download"></i>
                                                    </a>
                                                    @if(auth()->user()->hasPermission('documents.edit'))
                                                        <a href="{{ route('admin.documents.edit', $doc) }}" class="btn btn-sm btn-primary">Edit</a>
                                                    @endif
                                                    @if(auth()->user()->hasPermission('documents.delete'))
                                                        <form action="{{ route('admin.documents.destroy', $doc) }}" method="POST" class="m-0" onsubmit="return confirm('Delete this document?')">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="text-center text-muted py-4">No documents yet. Click &ldquo;Upload Document&rdquo; to add one.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('components.backend.footer')
</div>
</div>

<!-- Preview modal -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewTitle">Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0" id="previewBody" style="min-height:70vh; background:#f4f4f4;"></div>
            <div class="modal-footer">
                <a href="#" id="previewOpen" target="_blank" class="btn btn-outline-secondary">Open in new tab</a>
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@include('components.backend.main-js')
<script>
    // Enable Bootstrap tooltips (falls back to the native title tooltip if BS isn't present)
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (el) {
        if (window.bootstrap && bootstrap.Tooltip) { new bootstrap.Tooltip(el); }
    });

    // Inline file preview (images + PDFs)
    document.querySelectorAll('.preview-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var url = this.dataset.url, mime = (this.dataset.mime || '').toLowerCase(), title = this.dataset.title || 'Preview';
            document.getElementById('previewTitle').textContent = title;
            document.getElementById('previewOpen').setAttribute('href', url);
            var body = document.getElementById('previewBody');

            if (mime.indexOf('image/') === 0) {
                body.innerHTML = '<div class="text-center p-3"><img src="' + url + '" style="max-width:100%; max-height:72vh;"></div>';
            } else if (mime === 'application/pdf') {
                body.innerHTML = '<iframe src="' + url + '" style="width:100%; height:75vh; border:0;"></iframe>';
            } else {
                body.innerHTML = '<div class="text-center text-muted py-5">Inline preview isn\'t available for this file type.<br><a href="' + url + '" target="_blank" class="btn btn-sm btn-primary mt-3">Open / Download</a></div>';
            }

            if (window.bootstrap && bootstrap.Modal) {
                bootstrap.Modal.getOrCreateInstance(document.getElementById('previewModal')).show();
            } else {
                window.open(url, '_blank');
            }
        });
    });
</script>
</body>
</html>
