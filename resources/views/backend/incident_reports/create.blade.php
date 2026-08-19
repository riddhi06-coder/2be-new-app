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
                <div class="col-6"><h3>New Incident Report</h3></div>
                <div class="col-6">
                    <ol class="breadcrumb justify-content-end">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa fa-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.incident-reports.index') }}">Incident Reports</a></li>
                        <li class="breadcrumb-item active">New</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.incident-reports.store') }}" method="POST" enctype="multipart/form-data" class="theme-form">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Reporter Name <span class="text-danger">*</span></label>
                                    <input type="text" name="reporter_name" class="form-control @error('reporter_name') is-invalid @enderror"
                                           value="{{ old('reporter_name', auth()->user()->name) }}" required>
                                    @error('reporter_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                @if($canManage)
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Employee Involved</label>
                                    <select name="employee_id" class="form-control @error('employee_id') is-invalid @enderror">
                                        <option value="">-- Select employee (optional) --</option>
                                        @foreach($employees as $emp)
                                            <option value="{{ $emp->id }}" {{ old('employee_id') == $emp->id ? 'selected' : '' }}>{{ $emp->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('employee_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                @endif

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Incident Date <span class="text-danger">*</span></label>
                                    <input type="date" name="incident_date" class="form-control @error('incident_date') is-invalid @enderror"
                                           value="{{ old('incident_date', now()->toDateString()) }}" required>
                                    @error('incident_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Incident Time</label>
                                    <input type="time" name="incident_time" class="form-control" value="{{ old('incident_time') }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Location / Site <span class="text-danger">*</span></label>
                                    <input type="text" name="location" class="form-control @error('location') is-invalid @enderror"
                                           value="{{ old('location') }}" required placeholder="Job site, address or yard">
                                    @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Category <span class="text-danger">*</span></label>
                                    <select name="category" class="form-control @error('category') is-invalid @enderror" required>
                                        <option value="">-- Select category --</option>
                                        @foreach(\App\Models\IncidentReport::CATEGORIES as $val => $label)
                                            <option value="{{ $val }}" {{ old('category') === $val ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Severity <span class="text-danger">*</span></label>
                                    <select name="severity" class="form-control @error('severity') is-invalid @enderror" required>
                                        <option value="">-- Select severity --</option>
                                        @foreach(\App\Models\IncidentReport::SEVERITIES as $val => $label)
                                            <option value="{{ $val }}" {{ old('severity') === $val ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('severity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="form-label">Description <span class="text-danger">*</span></label>
                                    <textarea name="description" class="form-control ckeditor @error('description') is-invalid @enderror" rows="5" placeholder="Describe what happened...">{{ old('description') }}</textarea>
                                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="form-label">Immediate Action Taken</label>
                                    <textarea name="immediate_action" class="form-control ckeditor" rows="3" placeholder="Any first response or steps taken (optional)">{{ old('immediate_action') }}</textarea>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Witnesses</label>
                                    <input type="text" name="witnesses" class="form-control" value="{{ old('witnesses') }}" placeholder="Names of any witnesses (optional)">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Photos</label>
                                    <input type="file" name="photos[]" id="photosInput" class="form-control @error('photos.*') is-invalid @enderror" accept=".jpg,.jpeg,.png,.gif,.webp" multiple>
                                    <small class="text-muted">You can select multiple images. JPG, PNG, GIF or WEBP. Max {{ round(config('uploads.image_max_kb') / 1024) }} MB each.</small>
                                    @error('photos.*')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    <div id="photosPreview" class="d-flex flex-wrap gap-2 mt-2"></div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit Report</button>
                            <a href="{{ route('admin.incident-reports.index') }}" class="btn btn-light">Cancel</a>
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
        var input = document.getElementById('photosInput');
        var wrap = document.getElementById('photosPreview');
        if (!input) return;
        input.addEventListener('change', function () {
            wrap.innerHTML = '';
            Array.prototype.forEach.call(this.files, function (file) {
                var img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                img.className = 'incident-photo-thumb';
                wrap.appendChild(img);
            });
        });
    })();
</script>
</body>
</html>
