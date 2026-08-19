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
                <div class="col-6"><h3>Announcements</h3></div>
                <div class="col-6 text-end">
                    @if(auth()->user()->hasPermission('announcements.create'))
                        <a href="{{ route('admin.announcements.create') }}" class="btn btn-primary">+ New Announcement</a>
                    @endif
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="basic-1" class="display table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th style="width:70px;">Image</th>
                                        <th>Title</th>
                                        <th>Status</th>
                                        <th class="text-end" style="min-width:170px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($announcements as $a)
                                        <tr>
                                            <td>
                                                @if($a->image_path)
                                                    <img src="{{ asset($a->image_path) }}" alt="" class="preview-img"
                                                         data-url="{{ asset($a->image_path) }}" data-title="{{ $a->title }}"
                                                         title="Click to preview"
                                                         style="width:52px; height:52px; object-fit:cover; border-radius:6px; cursor:pointer;">
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $a->title }}                                            </td>
                                            <td>
                                                @if($a->is_active)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-secondary">Hidden</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <div class="d-flex gap-1 justify-content-end">
                                                    @if(auth()->user()->hasPermission('announcements.edit'))
                                                        <a href="{{ route('admin.announcements.edit', $a) }}" class="btn btn-sm btn-primary">Edit</a>
                                                    @endif
                                                    @if(auth()->user()->hasPermission('announcements.delete'))
                                                        <form action="{{ route('admin.announcements.destroy', $a) }}" method="POST" class="m-0" onsubmit="return confirm('Delete this announcement?')">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="text-center text-muted py-4">No announcements yet. Click &ldquo;New Announcement&rdquo; to add one.</td></tr>
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

<!-- Image preview modal -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewTitle">Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-3" id="previewBody" style="background:#f4f4f4;"></div>
        </div>
    </div>
</div>

@include('components.backend.main-js')
<script>
    document.querySelectorAll('.preview-img').forEach(function (img) {
        img.addEventListener('click', function () {
            var url = this.dataset.url, title = this.dataset.title || 'Preview';
            document.getElementById('previewTitle').textContent = title;
            document.getElementById('previewBody').innerHTML = '<img src="' + url + '" style="max-width:100%; max-height:72vh;">';
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
