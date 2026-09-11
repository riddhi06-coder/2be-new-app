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
                <div class="col-6"><h3>Edit Event</h3></div>
                <div class="col-6">
                    <ol class="breadcrumb justify-content-end">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa fa-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.community-calendar.index') }}">Community Calendar</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.community-calendar.update', $event) }}" method="POST" enctype="multipart/form-data" class="theme-form">
                            @csrf @method('PUT')
                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    <label class="form-label">Title <span class="text-danger">*</span></label>
                                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                           value="{{ old('title', $event->title) }}" required>
                                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Category <span class="text-danger">*</span></label>
                                    <select name="category" class="form-control @error('category') is-invalid @enderror" required>
                                        @foreach(\App\Models\CalendarEvent::CATEGORIES as $key => $c)
                                            <option value="{{ $key }}" {{ old('category', $event->category) === $key ? 'selected' : '' }}>{{ $c['label'] }}</option>
                                        @endforeach
                                    </select>
                                    @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Start Date <span class="text-danger">*</span></label>
                                    <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror"
                                           value="{{ old('start_date', optional($event->start_date)->toDateString()) }}" required>
                                    @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">End Date <small class="text-muted">(optional)</small></label>
                                    <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror"
                                           value="{{ old('end_date', optional($event->end_date)->toDateString()) }}">
                                    @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">All-day event</label>
                                    <div class="form-check form-switch mt-2">
                                        <input type="hidden" name="all_day" value="0">
                                        <input class="form-check-input" type="checkbox" id="all_day" name="all_day" value="1" {{ old('all_day', $event->all_day) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="all_day">Yes</label>
                                    </div>
                                </div>

                                <div class="col-md-4 mb-3 time-field">
                                    <label class="form-label">Start Time</label>
                                    <input type="time" name="start_time" class="form-control" value="{{ old('start_time', $event->start_time) }}">
                                </div>
                                <div class="col-md-4 mb-3 time-field">
                                    <label class="form-label">End Time</label>
                                    <input type="time" name="end_time" class="form-control" value="{{ old('end_time', $event->end_time) }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Location <small class="text-muted">(optional)</small></label>
                                    <input type="text" name="location" class="form-control" value="{{ old('location', $event->location) }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Status</label>
                                    <div class="form-check form-switch mt-2">
                                        <input type="hidden" name="is_active" value="0">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $event->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">Active <small class="text-muted">(shown on the calendar)</small></label>
                                    </div>
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" class="form-control ckeditor" rows="3">{{ old('description', $event->description) }}</textarea>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Flyer / Document <small class="text-muted">(optional)</small></label>
                                    @if($event->attachment)
                                        <div class="mb-2">
                                            <a href="{{ asset($event->attachment) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="fa fa-file-o"></i> View current flyer
                                            </a>
                                            <label class="ms-2 small"><input type="checkbox" name="remove_attachment" value="1"> Remove</label>
                                        </div>
                                    @endif
                                    <input type="file" name="attachment" class="form-control @error('attachment') is-invalid @enderror" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.webp">
                                    <small class="text-muted">PDF, Word or image. Max {{ round(config('uploads.document_max_kb') / 1024) }} MB. Uploading a new file replaces the current one.</small>
                                    @error('attachment')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                            <a href="{{ route('admin.community-calendar.index') }}" class="btn btn-light">Cancel</a>
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
        var allDay = document.getElementById('all_day');
        function toggleTimes() {
            document.querySelectorAll('.time-field').forEach(function (el) {
                el.style.display = allDay.checked ? 'none' : '';
            });
        }
        allDay.addEventListener('change', toggleTimes);
        toggleTimes();
    })();
</script>
</body>
</html>
