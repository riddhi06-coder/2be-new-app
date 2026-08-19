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
                <div class="col-6"><h3>Manage Incident Report — {{ $report->reference_no }}</h3></div>
                <div class="col-6">
                    <ol class="breadcrumb justify-content-end">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa fa-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.incident-reports.index') }}">Incident Reports</a></li>
                        <li class="breadcrumb-item active">Manage</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.incident-reports.update', $report) }}" method="POST" enctype="multipart/form-data" class="theme-form">
                            @csrf @method('PUT')
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Reporter Name <span class="text-danger">*</span></label>
                                    <input type="text" name="reporter_name" class="form-control @error('reporter_name') is-invalid @enderror"
                                           value="{{ old('reporter_name', $report->reporter_name) }}" required>
                                    @error('reporter_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Employee Involved</label>
                                    <select name="employee_id" class="form-control">
                                        <option value="">-- Select employee (optional) --</option>
                                        @foreach($employees as $emp)
                                            <option value="{{ $emp->id }}" {{ old('employee_id', $report->employee_id) == $emp->id ? 'selected' : '' }}>{{ $emp->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Incident Date <span class="text-danger">*</span></label>
                                    <input type="date" name="incident_date" class="form-control @error('incident_date') is-invalid @enderror"
                                           value="{{ old('incident_date', optional($report->incident_date)->toDateString()) }}" required>
                                    @error('incident_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Incident Time</label>
                                    <input type="time" name="incident_time" class="form-control" value="{{ old('incident_time', $report->incident_time) }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Location / Site <span class="text-danger">*</span></label>
                                    <input type="text" name="location" class="form-control @error('location') is-invalid @enderror"
                                           value="{{ old('location', $report->location) }}" required>
                                    @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Category <span class="text-danger">*</span></label>
                                    <select name="category" class="form-control @error('category') is-invalid @enderror" required>
                                        @foreach(\App\Models\IncidentReport::CATEGORIES as $val => $label)
                                            <option value="{{ $val }}" {{ old('category', $report->category) === $val ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Severity <span class="text-danger">*</span></label>
                                    <select name="severity" class="form-control @error('severity') is-invalid @enderror" required>
                                        @foreach(\App\Models\IncidentReport::SEVERITIES as $val => $label)
                                            <option value="{{ $val }}" {{ old('severity', $report->severity) === $val ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('severity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="form-label">Description <span class="text-danger">*</span></label>
                                    <textarea name="description" class="form-control ckeditor @error('description') is-invalid @enderror" rows="5">{{ old('description', $report->description) }}</textarea>
                                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label">Immediate Action Taken</label>
                                    <textarea name="immediate_action" class="form-control ckeditor" rows="3">{{ old('immediate_action', $report->immediate_action) }}</textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Witnesses</label>
                                    <input type="text" name="witnesses" class="form-control" value="{{ old('witnesses', $report->witnesses) }}">
                                </div>
                            </div>

                            <hr>
                            <h6 class="mb-3">Review</h6>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Status <span class="text-danger">*</span></label>
                                    <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                                        @foreach(\App\Models\IncidentReport::STATUSES as $val => $label)
                                            <option value="{{ $val }}" {{ old('status', $report->status) === $val ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label">Review Notes</label>
                                    <textarea name="review_notes" class="form-control ckeditor" rows="3">{{ old('review_notes', $report->review_notes) }}</textarea>
                                </div>
                            </div>

                            <hr>
                            <h6 class="mb-2">Photos</h6>
                            @if($report->photos->count())
                                <div class="d-flex flex-wrap gap-3 mb-3">
                                    @foreach($report->photos as $photo)
                                        <div class="text-center">
                                            <img src="{{ asset($photo->file_path) }}" alt="" class="incident-photo-thumb d-block mb-1">
                                            <button type="submit" form="delphoto-{{ $photo->id }}" class="btn btn-sm btn-link text-danger p-0" onclick="return confirm('Remove this photo?')">Remove</button>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            <div class="mb-3">
                                <label class="form-label">Add More Photos</label>
                                <input type="file" name="photos[]" class="form-control @error('photos.*') is-invalid @enderror" accept=".jpg,.jpeg,.png,.gif,.webp" multiple>
                                <small class="text-muted">JPG, PNG, GIF or WEBP. Max {{ round(config('uploads.image_max_kb') / 1024) }} MB each.</small>
                                @error('photos.*')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>

                            <button type="submit" class="btn btn-primary">Save Changes</button>
                            <a href="{{ route('admin.incident-reports.index') }}" class="btn btn-light">Cancel</a>
                        </form>

                        {{-- Separate delete-photo forms (kept outside the main form) --}}
                        @foreach($report->photos as $photo)
                            <form id="delphoto-{{ $photo->id }}" action="{{ route('admin.incident-reports.photos.destroy', $photo) }}" method="POST" class="d-none">
                                @csrf @method('DELETE')
                            </form>
                        @endforeach
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
