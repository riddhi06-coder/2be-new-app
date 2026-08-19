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
                <div class="col-6"><h3>Document Folders</h3></div>
                <div class="col-6 text-end">
                    @if(auth()->user()->hasPermission('document-categories.create'))
                        <a href="{{ route('admin.document-categories.create') }}" class="btn btn-primary">+ New Folder</a>
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
                                        <th>Folder</th>
                                        <th>Description</th>
                                        <th>Documents</th>
                                        <th>Status</th>
                                        <th class="text-end" style="min-width:170px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($categories as $cat)
                                        <tr>
                                            <td>{{ $cat->name }}</td>
                                            <td>{{ $cat->description ?: '—' }}</td>
                                            <td><span class="badge bg-primary">{{ $cat->documents_count }}</span></td>
                                            <td>
                                                @if($cat->is_active)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-secondary">Inactive</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <div class="d-flex gap-1 justify-content-end">
                                                    @if(auth()->user()->hasPermission('document-categories.edit'))
                                                        <a href="{{ route('admin.document-categories.edit', $cat) }}" class="btn btn-sm btn-primary">Edit</a>
                                                    @endif
                                                    @if(auth()->user()->hasPermission('document-categories.delete'))
                                                        <form action="{{ route('admin.document-categories.destroy', $cat) }}" method="POST" class="m-0" onsubmit="return confirm('Delete this folder?')">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="text-center text-muted py-4">No folders yet. Click &ldquo;New Folder&rdquo; to add one.</td></tr>
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

@include('components.backend.main-js')
</body>
</html>
