<!doctype html>
<html lang="en">

<head>
    @include('components.backend.head')
    <style>
        .section-label { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #6c757d; margin-bottom: 0.75rem; }
        .field-group { background: #f8f9fa; border-radius: 8px; padding: 1.25rem; margin-bottom: 1rem; }
        .media-preview img  { max-width: 100%; max-height: 200px; border-radius: 6px; object-fit: cover; border: 1px solid #dee2e6; }
        .media-preview video { max-width: 100%; max-height: 200px; border-radius: 6px; border: 1px solid #dee2e6; }
        .nav-tabs .nav-link { font-size: 0.85rem; font-weight: 600; }
    </style>
</head>

@include('components.backend.header')
@include('components.backend.sidebar')

<div class="page-body">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-xl-10">

                <!-- Page Header -->
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                    <div>
                        <h5 class="mb-1 fw-semibold">Edit Septic Record #{{ $record->id }}</h5>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 small">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('septic-records.index') }}">Septic Records</a></li>
                                <li class="breadcrumb-item active">Edit #{{ $record->id }}</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('septic-records.pdf', $record->id) }}" class="btn btn-sm btn-danger" target="_blank">
                            <i class="fa fa-file-pdf-o me-1"></i> Download PDF
                        </a>
                        <button type="button" class="btn btn-sm btn-info text-white" id="btnOpenSendModal">
                            <i class="fa fa-envelope me-1"></i> Send Report
                        </button>
                        <a href="{{ route('septic-records.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fa fa-arrow-left me-1"></i> Back
                        </a>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Status Badge -->
                <div class="mb-3">
                    @if($record->is_draft)
                        <span class="badge bg-warning text-dark fs-6 px-3 py-2">Draft</span>
                    @else
                        <span class="badge bg-success fs-6 px-3 py-2">Submitted</span>
                    @endif
                    <span class="text-muted small ms-2">Submitted: {{ $record->inserted_at ? \Carbon\Carbon::parse($record->inserted_at)->format('m/d/Y H:i') : '—' }}</span>
                </div>

                <form method="POST" action="{{ route('septic-records.update', $record->id) }}" enctype="multipart/form-data">
                    @csrf @method('PUT')

                    <div class="card shadow-sm">
                        <div class="card-body p-0">

                            <ul class="nav nav-tabs px-3 pt-3" id="editTabs">
                                <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#tab-basic">Basic Info</a></li>
                                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-site">Site Observations</a></li>
                                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-eval">System Evaluation</a></li>
                                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-media">Media Files</a></li>
                            </ul>

                            <div class="tab-content p-4">

                                <!-- TAB 1: BASIC INFO -->
                                <div class="tab-pane fade show active" id="tab-basic">
                                    <p class="section-label">Basic Information</p>
                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <label class="form-label">Type of Inspection</label>
                                            <input type="text" name="inspection_type" class="form-control" value="{{ old('inspection_type', $record->inspection_type) }}">
                                            <div class="form-text">Comma-separated e.g. Home Inspector, Realtor</div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Date of Inspection</label>
                                            <input type="date" name="date_of_pickup" class="form-control" value="{{ old('date_of_pickup', $record->date_of_pickup ? \Carbon\Carbon::parse($record->date_of_pickup)->format('Y-m-d') : '') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Time</label>
                                            <input type="text" name="time" class="form-control" value="{{ old('time', $record->time) }}" placeholder="e.g. 10:30 AM">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Weather</label>
                                            <input type="text" name="weather" class="form-control" value="{{ old('weather', $record->weather) }}">
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">Inspector Name & Company</label>
                                            <input type="text" name="inspector_name_company" class="form-control" value="{{ old('inspector_name_company', $record->inspector_name_company) }}">
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">Site Address</label>
                                            <textarea name="site_address" class="form-control" rows="2">{{ old('site_address', $record->site_address) }}</textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Tax Map Number</label>
                                            <input type="text" name="tax_map_number" class="form-control" value="{{ old('tax_map_number', $record->tax_map_number) }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Type of System</label>
                                            <input type="text" name="type_of_system" class="form-control" value="{{ old('type_of_system', $record->type_of_system) }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Inspector Signature</label>
                                            <input type="text" name="inspector_signature" class="form-control" value="{{ old('inspector_signature', $record->inspector_signature) }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Status</label>
                                            <select name="is_draft" class="form-select">
                                                <option value="0" {{ !$record->is_draft ? 'selected' : '' }}>Submitted</option>
                                                <option value="1" {{ $record->is_draft  ? 'selected' : '' }}>Draft</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- TAB 2: SITE OBSERVATIONS -->
                                <div class="tab-pane fade" id="tab-site">
                                    <p class="section-label">Site Observations</p>
                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <label class="form-label">Property in Use</label>
                                            <input type="text" name="property_in_use" class="form-control" value="{{ old('property_in_use', $record->property_in_use) }}">
                                            <div class="form-text">Comma-separated e.g. Yes, Full time</div>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">General Site Conditions</label>
                                            <input type="text" name="site_conditions" class="form-control" value="{{ old('site_conditions', $record->site_conditions) }}">
                                            <div class="form-text">Comma-separated selected conditions</div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Surface Runoff</label>
                                            <select name="surface_runoff" class="form-select">
                                                <option value="">— Select —</option>
                                                @foreach(['Yes','No','N/A'] as $opt)
                                                    <option value="{{ $opt }}" {{ old('surface_runoff', $record->surface_runoff) === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">Malfunction / Surface Discharge</label>
                                            <input type="text" name="malfunction" class="form-control" value="{{ old('malfunction', $record->malfunction) }}">
                                            <div class="form-text">Comma-separated selected options</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- TAB 3: SYSTEM EVALUATION -->
                                <div class="tab-pane fade" id="tab-eval">
                                    <p class="section-label">Manhole Covers &amp; Tank</p>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Accessible</label>
                                            <select name="manhole_accessible" class="form-select">
                                                <option value="">— Select —</option>
                                                @foreach(['Yes','No'] as $opt)
                                                    <option value="{{ $opt }}" {{ old('manhole_accessible', $record->manhole_accessible) === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Lid(s) Need Repair</label>
                                            <select name="lid_needs_repair" class="form-select">
                                                <option value="">— Select —</option>
                                                @foreach(['Yes','No'] as $opt)
                                                    <option value="{{ $opt }}" {{ old('lid_needs_repair', $record->lid_needs_repair) === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">Liquid Operating Level</label>
                                            <input type="text" name="liquid_operating_level" class="form-control" value="{{ old('liquid_operating_level', $record->liquid_operating_level) }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Scum Layer Thickness (in.)</label>
                                            <input type="text" name="scum_layer_thickness" class="form-control" value="{{ old('scum_layer_thickness', $record->scum_layer_thickness) }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Sludge Layer Thickness (in.)</label>
                                            <input type="text" name="sludge_layer_thickness" class="form-control" value="{{ old('sludge_layer_thickness', $record->sludge_layer_thickness) }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Tank Pumping Recommended</label>
                                            <select name="tank_pumping_recommended" class="form-select">
                                                <option value="">— Select —</option>
                                                @foreach(['Yes','No'] as $opt)
                                                    <option value="{{ $opt }}" {{ old('tank_pumping_recommended', $record->tank_pumping_recommended) === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Tank Pumped</label>
                                            <select name="tank_pumped" class="form-select">
                                                <option value="">— Select —</option>
                                                @foreach(['Yes','No','N/A'] as $opt)
                                                    <option value="{{ $opt }}" {{ old('tank_pumped', $record->tank_pumped) === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Approx. Volume Pumped (gals)</label>
                                            <input type="text" name="approx_volume_pumped" class="form-control" value="{{ old('approx_volume_pumped', $record->approx_volume_pumped) }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Tank Composition</label>
                                            <input type="text" name="tank_composition" class="form-control" value="{{ old('tank_composition', $record->tank_composition) }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Approx. Size of Tank (gals)</label>
                                            <input type="text" name="approx_tank_size" class="form-control" value="{{ old('approx_tank_size', $record->approx_tank_size) }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Water Stream from House</label>
                                            <input type="text" name="water_stream_from_house" class="form-control" value="{{ old('water_stream_from_house', $record->water_stream_from_house) }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Water Stream from Drain Field</label>
                                            <input type="text" name="water_stream_from_drain" class="form-control" value="{{ old('water_stream_from_drain', $record->water_stream_from_drain) }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Inlet Tee Needs Repair</label>
                                            <select name="inlet_tee_needs_repair" class="form-select">
                                                <option value="">— Select —</option>
                                                @foreach(['Yes','N/D'] as $opt)
                                                    <option value="{{ $opt }}" {{ old('inlet_tee_needs_repair', $record->inlet_tee_needs_repair) === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Outlet Tee Needs Repair</label>
                                            <select name="outlet_tee_needs_repair" class="form-select">
                                                <option value="">— Select —</option>
                                                @foreach(['Yes','N/D'] as $opt)
                                                    <option value="{{ $opt }}" {{ old('outlet_tee_needs_repair', $record->outlet_tee_needs_repair) === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Service Recommended</label>
                                            <select name="service_recommended" class="form-select">
                                                <option value="">— Select —</option>
                                                @foreach(['Yes','No','N/D'] as $opt)
                                                    <option value="{{ $opt }}" {{ old('service_recommended', $record->service_recommended) === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Comments</label>
                                            <textarea name="comments" class="form-control" rows="3">{{ old('comments', $record->comments) }}</textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Notes</label>
                                            <textarea name="notes" class="form-control" rows="3">{{ old('notes', $record->notes) }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- TAB 4: MEDIA -->
                                <div class="tab-pane fade" id="tab-media">
                                    <p class="section-label">Media Files</p>
                                    <div class="row g-4">

                                        <!-- Image -->
                                        <div class="col-md-6">
                                            <div class="field-group">
                                                <label class="form-label fw-semibold">Inspection Image</label>
                                                <div class="form-text mb-2">Accepted: JPG, PNG, WebP &nbsp;|&nbsp; Max: 2 MB</div>
                                                @if($record->image_path)
                                                    <div class="media-preview mb-2">
                                                        <img src="{{ Storage::url($record->image_path) }}" alt="Inspection Image">
                                                    </div>
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input" type="checkbox" name="remove_image" id="remove_image" value="1">
                                                        <label class="form-check-label text-danger small" for="remove_image">Remove existing image</label>
                                                    </div>
                                                @else
                                                    <p class="text-muted small mb-2">No image uploaded yet.</p>
                                                @endif
                                                <input type="file" name="image" class="form-control" accept=".jpg,.jpeg,.png,.webp">
                                                @error('image')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                            </div>
                                        </div>

                                        <!-- Video -->
                                        <div class="col-md-6">
                                            <div class="field-group">
                                                <label class="form-label fw-semibold">Inspection Video</label>
                                                <div class="form-text mb-2">Accepted: MP4, MOV, AVI, WMV, MKV &nbsp;|&nbsp; Max: 5 MB</div>
                                                @if($record->video_path)
                                                    <div class="media-preview mb-2">
                                                        <video controls>
                                                            <source src="{{ Storage::url($record->video_path) }}">
                                                            Your browser does not support video playback.
                                                        </video>
                                                    </div>
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input" type="checkbox" name="remove_video" id="remove_video" value="1">
                                                        <label class="form-check-label text-danger small" for="remove_video">Remove existing video</label>
                                                    </div>
                                                @else
                                                    <p class="text-muted small mb-2">No video uploaded yet.</p>
                                                @endif
                                                <input type="file" name="video" class="form-control" accept=".mp4,.mov,.avi,.wmv,.mkv">
                                                @error('video')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div><!-- end tab-content -->
                        </div>

                        <div class="card-footer d-flex justify-content-end gap-2 bg-white">
                            <a href="{{ route('septic-records.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fa fa-save me-1"></i> Save Changes
                            </button>
                        </div>

                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<!-- Send Report Modal -->
<div class="modal fade" id="sendReportModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-envelope me-2"></i>Send Inspection Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small mb-3">The PDF for this record will be generated and sent to the email you enter below.</p>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Recipient Email <span class="text-danger">*</span></label>
                    <input type="email" id="modal_to_email" class="form-control" placeholder="e.g. client@example.com">
                    <div id="modal_email_error" class="text-danger small mt-1" style="display:none;"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-info text-white" id="btnSendReport">
                    <span id="sendBtnText"><i class="fa fa-paper-plane me-1"></i>Send Report</span>
                    <span id="sendBtnSpinner" style="display:none;"><span class="spinner-border spinner-border-sm me-1"></span>Sending...</span>
                </button>
            </div>
        </div>
    </div>
</div>

@include('components.backend.footer')
</div>
</div>

@include('components.backend.main-js')

<script>
    document.getElementById('btnOpenSendModal').addEventListener('click', function() {
        document.getElementById('modal_to_email').value = '';
        document.getElementById('modal_email_error').style.display = 'none';
        new bootstrap.Modal(document.getElementById('sendReportModal')).show();
    });

    document.getElementById('btnSendReport').addEventListener('click', function() {
        const email  = document.getElementById('modal_to_email').value.trim();
        const errDiv = document.getElementById('modal_email_error');

        if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            errDiv.textContent = 'Please enter a valid email address.';
            errDiv.style.display = 'block';
            return;
        }
        errDiv.style.display = 'none';

        document.getElementById('sendBtnText').style.display    = 'none';
        document.getElementById('sendBtnSpinner').style.display = 'inline';
        this.disabled = true;

        fetch('{{ route("septic-records.send-report") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ record_id: {{ $record->id }}, to_email: email })
        })
        .then(res => res.json())
        .then(data => {
            document.getElementById('sendBtnText').style.display    = 'inline';
            document.getElementById('sendBtnSpinner').style.display = 'none';
            document.getElementById('btnSendReport').disabled = false;
            bootstrap.Modal.getInstance(document.getElementById('sendReportModal')).hide();

            const cls   = data.success ? 'alert-success' : 'alert-danger';
            const alert = '<div class="alert ' + cls + ' alert-dismissible fade show mb-3" role="alert">' + data.message + '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
            document.querySelector('.page-body .container-fluid').insertAdjacentHTML('afterbegin', alert);
        })
        .catch(() => {
            document.getElementById('sendBtnText').style.display    = 'inline';
            document.getElementById('sendBtnSpinner').style.display = 'none';
            document.getElementById('btnSendReport').disabled = false;
            errDiv.textContent = 'Something went wrong. Please try again.';
            errDiv.style.display = 'block';
        });
    });
</script>

</body>
</html>
