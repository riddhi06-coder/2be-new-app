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
                <div class="col-6"><h3>Edit Role</h3></div>
                <div class="col-6">
                    <ol class="breadcrumb justify-content-end">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa fa-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.roles.index') }}">Roles</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.roles.update', $role) }}" method="POST" class="theme-form">
                            @csrf @method('PUT')
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Role Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name', $role->name) }}" required placeholder="e.g. Content Editor">
                                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Status</label>
                                    @if($role->is_protected)
                                        <div class="mt-2">
                                            <span class="badge bg-success">Active</span>
                                            <small class="text-muted ms-2">Super Admin is always active.</small>
                                        </div>
                                    @else
                                        <div class="form-check form-switch mt-2">
                                            <input type="hidden" name="is_active" value="0">
                                            <input class="form-check-input" type="checkbox" id="role-active" name="is_active" value="1" {{ old('is_active', $role->is_active) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="role-active">Active <small class="text-muted">(only active roles can be assigned to users)</small></label>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" class="form-control ckeditor" rows="3" placeholder="Description of what this role represents">{{ old('description', $role->description) }}</textarea>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                            <a href="{{ route('admin.roles.index') }}" class="btn btn-light">Cancel</a>
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
</body>
</html>
