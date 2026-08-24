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
                <div class="col-6"><h3>Incident Report — {{ $report->reference_no }}</h3></div>
                <div class="col-6">
                    <ol class="breadcrumb justify-content-end">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa fa-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.incident-reports.index') }}">Incident Reports</a></li>
                        <li class="breadcrumb-item active">{{ $report->reference_no }}</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3 flex-wrap gap-2">
                            <div>
                                <span class="badge {{ $report->severity_badge }} me-1">{{ $report->severity_label }}</span>
                                <span class="badge {{ $report->status_badge }} me-1">{{ $report->status_label }}</span>
                                <span class="badge {{ $report->source_badge }}">{{ $report->source_label }}</span>
                            </div>
                            <div>
                                @if($canManage && auth()->user()->hasPermission('incident-reports.edit'))
                                    <a href="{{ route('admin.incident-reports.edit', $report) }}" class="btn btn-sm btn-primary">Manage / Edit</a>
                                @endif
                                <a href="{{ route('admin.incident-reports.index') }}" class="btn btn-sm btn-light">Back</a>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3"><small class="text-muted d-block">Reported By</small>{{ $report->reporter_name }}</div>
                            <div class="col-md-4 mb-3"><small class="text-muted d-block">Employee Involved</small>{{ $report->employee->name ?? '—' }}</div>
                            <div class="col-md-4 mb-3"><small class="text-muted d-block">Category</small>{{ $report->category_label }}</div>
                            <div class="col-md-4 mb-3"><small class="text-muted d-block">Incident Date</small>{{ optional($report->incident_date)->format('d M Y') }} {{ $report->incident_time }}</div>
                            <div class="col-md-4 mb-3"><small class="text-muted d-block">Location</small>{{ $report->location }}</div>
                            <div class="col-md-4 mb-3"><small class="text-muted d-block">Witnesses</small>{{ $report->witnesses ?: '—' }}</div>
                        </div>

                        <hr>
                        <h6>Description</h6>
                        <div class="mb-3">{!! $report->description !!}</div>

                        @if($report->immediate_action)
                            <h6>Immediate Action Taken</h6>
                            <div class="mb-3">{!! $report->immediate_action !!}</div>
                        @endif

                        @if($report->photos->count())
                            <h6>Photos</h6>
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                @foreach($report->photos as $photo)
                                    <img src="{{ asset($photo->file_path) }}" alt="" class="preview-img incident-photo-thumb"
                                         data-url="{{ asset($photo->file_path) }}" data-title="{{ $photo->original_name }}" title="Click to preview">
                                @endforeach
                            </div>
                        @endif

                        @if($report->status !== 'open' || $report->review_notes || $report->reviewer)
                            <hr>
                            <h6>Review</h6>
                            <div class="row">
                                <div class="col-md-4 mb-2"><small class="text-muted d-block">Status</small><span class="badge {{ $report->status_badge }}">{{ $report->status_label }}</span></div>
                                <div class="col-md-4 mb-2"><small class="text-muted d-block">Reviewed By</small>{{ $report->reviewer->name ?? '—' }}</div>
                                <div class="col-md-4 mb-2"><small class="text-muted d-block">Reviewed At</small>{{ optional($report->reviewed_at)->format('d M Y, h:i A') ?? '—' }}</div>
                                @if($report->review_notes)
                                    <div class="col-12 mt-2"><small class="text-muted d-block">Review Notes</small>{!! $report->review_notes !!}</div>
                                @endif
                            </div>
                        @endif
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
            <div class="modal-body text-center p-3 preview-img-modal-body" id="previewBody"></div>
        </div>
    </div>
</div>

@include('components.backend.main-js')
<script>
    document.querySelectorAll('.preview-img').forEach(function (img) {
        img.addEventListener('click', function () {
            document.getElementById('previewTitle').textContent = this.dataset.title || 'Preview';
            document.getElementById('previewBody').innerHTML = '<img src="' + this.dataset.url + '" style="max-width:100%; max-height:72vh;">';
            if (window.bootstrap && bootstrap.Modal) {
                bootstrap.Modal.getOrCreateInstance(document.getElementById('previewModal')).show();
            } else {
                window.open(this.dataset.url, '_blank');
            }
        });
    });
</script>
</body>
</html>
