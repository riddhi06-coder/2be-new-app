<!doctype html>
<html lang="en">

<head>
    @include('components.backend.head')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .edit-page-header {
            background: linear-gradient(135deg, #ffffff 0%, #f6f8fb 100%);
            border: 1px solid #e6eaf0;
            border-radius: 12px;
            padding: 20px 26px;
            margin-bottom: 24px;
            box-shadow: 0 1px 2px rgba(16, 24, 40, 0.04);
        }
        .edit-page-header .page-title {
            font-size: 1.3rem; font-weight: 600; color: #1f2937;
            margin: 0; display: flex; align-items: center; gap: 12px;
        }
        .edit-page-header .title-icon {
            width: 38px; height: 38px;
            display: inline-flex; align-items: center; justify-content: center;
            background: #fff5e6; color: #d97706;
            border-radius: 10px; font-size: 1rem;
        }
        .edit-page-header .breadcrumb { margin: 6px 0 0 50px; font-size: 0.82rem; }
        .edit-page-header .breadcrumb-item + .breadcrumb-item::before { content: "›"; color: #9aa3b2; }
        .edit-page-header .breadcrumb-item a { color: #6b7280; text-decoration: none; }
        .edit-page-header .breadcrumb-item a:hover { color: #0d6efd; }
        .edit-page-header .breadcrumb-item.active { color: #1f2937; font-weight: 500; }

        .edit-card {
            border: 1px solid #e6eaf0; border-radius: 12px;
            box-shadow: 0 1px 2px rgba(16, 24, 40, 0.04);
        }
        .edit-card .nav-tabs {
            border-bottom: 1px solid #e6eaf0;
            padding: 8px 16px 0;
            background: #fafbfd;
            border-top-left-radius: 12px; border-top-right-radius: 12px;
            gap: 4px;
        }
        .edit-card .nav-tabs .nav-link {
            font-size: 0.88rem; font-weight: 600; color: #6b7280;
            border: none; padding: 12px 18px;
            border-bottom: 3px solid transparent;
        }
        .edit-card .nav-tabs .nav-link.active {
            color: #d97706; background: transparent;
            border-bottom-color: #d97706;
        }
        .edit-card .nav-tabs .nav-link i { margin-right: 6px; }

        .section-block {
            padding: 28px 30px; border-bottom: 1px dashed #e6eaf0;
        }
        .section-block:last-child { border-bottom: 0; }
        .section-block .section-title {
            font-size: 0.78rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.1em; color: #6b7280; margin-bottom: 18px;
            padding-bottom: 8px; border-bottom: 1px solid #f1f3f7;
        }
        .section-block .section-title i { color: #d97706; margin-right: 8px; }

        .form-label {
            font-weight: 600; color: #344054; font-size: 0.85rem; margin-bottom: 6px;
        }
        .form-control, .form-select {
            font-size: 0.92rem; padding: 9px 12px;
            border: 1px solid #d9dde3; border-radius: 8px;
        }
        .form-control:focus, .form-select:focus {
            border-color: #d97706; box-shadow: 0 0 0 3px rgba(217,119,6,0.12);
        }
        .form-text { font-size: 0.76rem; color: #8a93a3; margin-top: 4px; }

        .row.g-form { --bs-gutter-x: 1.5rem; --bs-gutter-y: 1.25rem; }

        .status-bar {
            background: #f8fafc; border: 1px solid #eef0f4; border-radius: 10px;
            padding: 12px 18px; margin-bottom: 20px;
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 10px;
        }
        .status-bar .badge { font-size: 0.78rem; padding: 7px 14px; }
        .status-bar .submitted-time { font-size: 0.82rem; color: #6b7280; }
        .status-bar .submitted-time i { margin-right: 4px; color: #9aa3b2; }

        .media-card {
            background: #fafbfd; border: 1px dashed #d9dde3; border-radius: 10px;
            padding: 18px; height: 100%;
        }
        .media-card .media-title { font-weight: 600; color: #344054; margin-bottom: 4px; }
        .media-card .media-help { color: #8a93a3; font-size: 0.78rem; margin-bottom: 12px; }
        .media-card .media-preview img,
        .media-card .media-preview video {
            max-width: 100%; max-height: 220px; border-radius: 8px;
            border: 1px solid #e6eaf0; object-fit: cover;
        }
        .media-card .empty-state {
            color: #9aa3b2; font-size: 0.85rem; padding: 14px;
            text-align: center; background: #fff; border-radius: 8px;
            border: 1px dashed #e6eaf0;
        }

        .card-footer-actions {
            background: #fafbfd; border-top: 1px solid #e6eaf0;
            padding: 16px 26px; display: flex; gap: 10px; justify-content: flex-end;
            border-bottom-left-radius: 12px; border-bottom-right-radius: 12px;
        }
        .card-footer-actions .btn { padding: 9px 22px; font-weight: 500; }

        @media (max-width: 768px) {
            .section-block { padding: 22px 18px; }
        }

        /* Toast */
        .app-toast-container { z-index: 1080; }
        .app-toast { min-width: 280px; border: 0; box-shadow: 0 8px 20px rgba(16,24,40,0.18); }
        .app-toast .toast-body i { font-size: 1.05rem; }
    </style>
</head>

@include('components.backend.header')
@include('components.backend.sidebar')

<div class="page-body">
    <div class="container-fluid">

        <!-- Toast container -->
        <div class="toast-container position-fixed top-0 end-0 p-3 app-toast-container" id="toastContainer"></div>

        <div class="row">
            <div class="col-12">

                <!-- Page Header -->
                <div class="edit-page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h5 class="page-title">
                            <span class="title-icon"><i class="fa-solid fa-water"></i></span>
                            Edit Cesspool Record <span class="text-muted fw-normal">#{{ $record->id }}</span>
                        </h5>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-house-chimney me-1"></i>Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('cesspool-records.index') }}">Cesspool Records</a></li>
                                <li class="breadcrumb-item active">Edit #{{ $record->id }}</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('cesspool-records.pdf', $record->id) }}" class="btn btn-sm btn-danger" target="_blank">
                            <i class="fa-solid fa-file-pdf me-1"></i> Download PDF
                        </a>
                        <button type="button" class="btn btn-sm btn-info text-white" id="btnOpenSendModal">
                            <i class="fa-solid fa-envelope me-1"></i> Send Report
                        </button>
                        <a href="{{ route('cesspool-records.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fa-solid fa-arrow-left me-1"></i> Back
                        </a>
                    </div>
                </div>

                <!-- Status Bar -->
                <div class="status-bar">
                    <div>
                        @if($record->is_draft)
                            <span class="badge bg-warning text-dark"><i class="fa-solid fa-pen-ruler me-1"></i>Draft</span>
                        @else
                            <span class="badge bg-success"><i class="fa-solid fa-check me-1"></i>Submitted</span>
                        @endif
                    </div>
                    <div class="submitted-time">
                        <i class="fa-regular fa-clock"></i>
                        Submitted: {{ $record->inserted_at ? \Carbon\Carbon::parse($record->inserted_at)->format('d-m-Y H:i') : '—' }}
                    </div>
                </div>

                <form method="POST" action="{{ route('cesspool-records.update', $record->id) }}" enctype="multipart/form-data">
                    @csrf @method('PUT')

                    <div class="card edit-card">

                        <ul class="nav nav-tabs" id="editTabs">
                            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#tab-basic"><i class="fa-solid fa-circle-info"></i>Basic Info</a></li>
                            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-site"><i class="fa-solid fa-eye"></i>Site Observations</a></li>
                            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-eval"><i class="fa-solid fa-magnifying-glass-chart"></i>System Evaluation</a></li>
                            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-media"><i class="fa-solid fa-photo-film"></i>Media Files</a></li>
                        </ul>

                        <div class="tab-content">

                            <!-- ==================== TAB 1: BASIC INFO ==================== -->
                            <div class="tab-pane fade show active" id="tab-basic">

                                <div class="section-block">
                                    <div class="section-title"><i class="fa-solid fa-circle-info"></i>Inspection Details</div>
                                    <div class="row g-form">
                                        <div class="col-md-12">
                                            <label class="form-label">Type of Inspection</label>
                                            <input type="text" name="inspection_type" class="form-control"
                                                   value="{{ old('inspection_type', $record->inspection_type) }}">
                                            <div class="form-text">Comma-separated, e.g. Home Inspector, Realtor, Routine Maintenance</div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Date of Inspection</label>
                                            <input type="date" name="date_of_pickup" class="form-control"
                                                   value="{{ old('date_of_pickup', $record->date_of_pickup ? \Carbon\Carbon::parse($record->date_of_pickup)->format('Y-m-d') : '') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Status</label>
                                            <select name="is_draft" class="form-select">
                                                <option value="0" {{ !$record->is_draft ? 'selected' : '' }}>Submitted</option>
                                                <option value="1" {{  $record->is_draft ? 'selected' : '' }}>Draft</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="section-block">
                                    <div class="section-title"><i class="fa-solid fa-user-tie"></i>Inspector &amp; Site</div>
                                    <div class="row g-form">
                                        <div class="col-md-12">
                                            <label class="form-label">Inspector Name &amp; Company</label>
                                            <input type="text" name="inspector_name_company" class="form-control"
                                                   value="{{ old('inspector_name_company', $record->inspector_name_company) }}">
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">Site Address</label>
                                            <textarea name="site_address" class="form-control" rows="2">{{ old('site_address', $record->site_address) }}</textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Tax Map Number</label>
                                            <input type="text" name="tax_map_number" class="form-control"
                                                   value="{{ old('tax_map_number', $record->tax_map_number) }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Type of System (DOH code if available)</label>
                                            <input type="text" name="type_of_system" class="form-control"
                                                   value="{{ old('type_of_system', $record->type_of_system) }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="section-block">
                                    <div class="section-title"><i class="fa-solid fa-signature"></i>Signature &amp; Acknowledgement</div>
                                    <div class="row g-form">
                                        <div class="col-md-12">
                                            <label class="form-label">Inspector Signature</label>
                                            @if($record->inspector_signature && file_exists(public_path($record->inspector_signature)))
                                                <div class="mb-2">
                                                    <div style="font-size:12px; color:#6b7d72; text-transform:uppercase; letter-spacing:0.4px; margin-bottom:4px;">Current signature on file</div>
                                                    <img src="{{ asset($record->inspector_signature) }}" alt="Signature" style="max-height:80px; border:1px solid #c4cac6; padding:6px; background:#fafbfa;">
                                                </div>
                                            @elseif($record->inspector_signature)
                                                <div class="mb-2 text-muted" style="font-size:13px;">
                                                    Current value (legacy text): <em>{{ $record->inspector_signature }}</em>
                                                </div>
                                            @endif
                                            <div class="signature-pad-wrap" style="border:1px solid #c4cac6; border-radius:6px; background:#fafbfa; padding:10px; max-width:600px;">
                                                <canvas id="signature_canvas" style="width:100%; height:160px; display:block; background:#fff; touch-action:none; border:1px dashed #c4cac6; border-radius:4px; cursor:crosshair;"></canvas>
                                                <div style="margin-top:8px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:6px;">
                                                    <small class="text-muted" style="font-size:13px;">Draw a new signature to replace the current one. Leave blank to keep existing.</small>
                                                    <button type="button" id="sig_clear_btn" class="btn btn-sm btn-outline-secondary" style="padding:4px 14px; font-size:13px;">Clear</button>
                                                </div>
                                            </div>
                                            <input type="hidden" id="inspector_signature" name="inspector_signature" value="">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Print Name</label>
                                            <input type="text" name="print_name" class="form-control"
                                                   value="{{ old('print_name', $record->print_name) }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Signature Date</label>
                                            <input type="date" name="date" class="form-control"
                                                   value="{{ old('date', $record->date ? \Carbon\Carbon::parse($record->date)->format('Y-m-d') : '') }}">
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <!-- ==================== TAB 2: SITE OBSERVATIONS ==================== -->
                            <div class="tab-pane fade" id="tab-site">

                                <div class="section-block">
                                    <div class="section-title"><i class="fa-solid fa-house-chimney-user"></i>Property &amp; Conditions</div>
                                    <div class="row g-form">
                                        <div class="col-md-6">
                                            <label class="form-label">Property in Use</label>
                                            <select name="property_in_use" class="form-select">
                                                <option value="">— Select —</option>
                                                @foreach(['Yes','No'] as $opt)
                                                    <option value="{{ $opt }}" {{ old('property_in_use', $record->property_in_use) === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Surface Runoff Directed Away from System</label>
                                            <select name="surface_runoff" class="form-select">
                                                <option value="">— Select —</option>
                                                @foreach(['Yes','No','N/A'] as $opt)
                                                    <option value="{{ $opt }}" {{ old('surface_runoff', $record->surface_runoff) === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">General Site Conditions</label>
                                            <input type="text" name="site_conditions" class="form-control"
                                                   value="{{ old('site_conditions', $record->site_conditions) }}">
                                            <div class="form-text">Comma-separated values from the inspection form</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="section-block">
                                    <div class="section-title"><i class="fa-solid fa-droplet"></i>Malfunction &amp; Surface Discharge</div>
                                    <div class="row g-form">
                                        <div class="col-md-6">
                                            <label class="form-label">Malfunction at Time of Inspection</label>
                                            <select name="malfunction" class="form-select">
                                                <option value="">— Select —</option>
                                                @foreach(['Yes','No'] as $opt)
                                                    <option value="{{ $opt }}" {{ old('malfunction', $record->malfunction) === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">Surface Discharge</label>
                                            <input type="text" name="surface_discharge" class="form-control"
                                                   value="{{ old('surface_discharge', $record->surface_discharge) }}">
                                            <div class="form-text">Comma-separated values, e.g. Grey water, Black water, Surface discharge in area of cesspool</div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <!-- ==================== TAB 3: SYSTEM EVALUATION ==================== -->
                            <div class="tab-pane fade" id="tab-eval">

                                <div class="section-block">
                                    <div class="section-title"><i class="fa-solid fa-circle-dot"></i>Lids &amp; Access</div>
                                    <div class="row g-form">
                                        <div class="col-md-6">
                                            <label class="form-label">Accessible Lids</label>
                                            <select name="accessible_lids" class="form-select">
                                                <option value="">— Select —</option>
                                                @foreach(['Yes','No'] as $opt)
                                                    <option value="{{ $opt }}" {{ old('accessible_lids', $record->accessible_lids) === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Access Lid(s) Need Repair</label>
                                            <select name="access_lid_repair" class="form-select">
                                                <option value="">— Select —</option>
                                                @foreach(['Yes','No'] as $opt)
                                                    <option value="{{ $opt }}" {{ old('access_lid_repair', $record->access_lid_repair) === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="section-block">
                                    <div class="section-title"><i class="fa-solid fa-flask"></i>Cesspool Contents &amp; Pumping</div>
                                    <div class="row g-form">
                                        <div class="col-md-6">
                                            <label class="form-label">Cesspool Water Level Depth</label>
                                            <input type="text" name="cesspool_water_level_depth" class="form-control"
                                                   value="{{ old('cesspool_water_level_depth', $record->cesspool_water_level_depth) }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Pumping Recommended</label>
                                            <select name="pumping_recommended" class="form-select">
                                                <option value="">— Select —</option>
                                                @foreach(['Yes','No'] as $opt)
                                                    <option value="{{ $opt }}" {{ old('pumping_recommended', $record->pumping_recommended) === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">Cesspool Pumped (Liquids &amp; Solids)</label>
                                            <input type="text" name="cesspool_pumped" class="form-control"
                                                   value="{{ old('cesspool_pumped', $record->cesspool_pumped) }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="section-block">
                                    <div class="section-title"><i class="fa-solid fa-arrows-left-right-to-line"></i>Flow &amp; Pipe</div>
                                    <div class="row g-form">
                                        <div class="col-md-6">
                                            <label class="form-label">Water Stream from House</label>
                                            <input type="text" name="water_stream_from_house" class="form-control"
                                                   value="{{ old('water_stream_from_house', $record->water_stream_from_house) }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Inlet Pipe Needs Repair</label>
                                            <input type="text" name="inlet_pipe_needs_repair" class="form-control"
                                                   value="{{ old('inlet_pipe_needs_repair', $record->inlet_pipe_needs_repair) }}">
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">Cesspool Composition</label>
                                            <input type="text" name="cesspool_composition" class="form-control"
                                                   value="{{ old('cesspool_composition', $record->cesspool_composition) }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="section-block">
                                    <div class="section-title"><i class="fa-solid fa-pen-nib"></i>Service &amp; Notes</div>
                                    <div class="row g-form">
                                        <div class="col-md-12">
                                            <label class="form-label">Service Recommended</label>
                                            <input type="text" name="service_recommended" class="form-control"
                                                   value="{{ old('service_recommended', $record->service_recommended) }}">
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">Comments</label>
                                            <textarea name="comments" class="form-control" rows="4">{{ old('comments', $record->comments) }}</textarea>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">Notes</label>
                                            <textarea name="notes" class="form-control" rows="4">{{ old('notes', $record->notes) }}</textarea>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <!-- ==================== TAB 4: MEDIA ==================== -->
                            <div class="tab-pane fade" id="tab-media">
                                <div class="section-block">
                                    <div class="section-title"><i class="fa-solid fa-photo-film"></i>Inspection Media</div>
                                    <div class="row g-form">
                                        <div class="col-md-6">
                                            <div class="media-card">
                                                <div class="media-title">Inspection Image</div>
                                                <div class="media-help">Accepted: JPG, PNG, WebP &nbsp;|&nbsp; Max size: 2 MB</div>
                                                @if($record->image_path)
                                                    <div class="media-preview mb-3">
                                                        <img src="{{ Storage::url($record->image_path) }}" alt="Inspection Image">
                                                    </div>
                                                    <div class="form-check mb-3">
                                                        <input class="form-check-input" type="checkbox" name="remove_image" id="remove_image" value="1">
                                                        <label class="form-check-label text-danger small" for="remove_image">Remove existing image</label>
                                                    </div>
                                                @else
                                                    <div class="empty-state mb-3"><i class="fa-regular fa-image me-1"></i> No image uploaded yet</div>
                                                @endif
                                                <input type="file" name="image" class="form-control" accept=".jpg,.jpeg,.png,.webp">
                                                @error('image')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="media-card">
                                                <div class="media-title">Inspection Video</div>
                                                <div class="media-help">Accepted: MP4, MOV, AVI, WMV, MKV &nbsp;|&nbsp; Max size: 5 MB</div>
                                                @if($record->video_path)
                                                    <div class="media-preview mb-3">
                                                        <video controls>
                                                            <source src="{{ asset($record->video_path) }}">
                                                            Your browser does not support video playback.
                                                        </video>
                                                    </div>
                                                    <div class="form-check mb-3">
                                                        <input class="form-check-input" type="checkbox" name="remove_video" id="remove_video" value="1">
                                                        <label class="form-check-label text-danger small" for="remove_video">Remove existing video</label>
                                                    </div>
                                                @else
                                                    <div class="empty-state mb-3"><i class="fa-regular fa-circle-play me-1"></i> No video uploaded yet</div>
                                                @endif
                                                <input type="file" name="video" class="form-control" accept=".mp4,.mov,.avi,.wmv,.mkv">
                                                @error('video')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div><!-- end tab-content -->

                        <div class="card-footer-actions">
                            <a href="{{ route('cesspool-records.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-floppy-disk me-1"></i> Save Changes
                            </button>
                        </div>

                    </div><!-- end edit-card -->
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
                <h5 class="modal-title"><i class="fa-solid fa-envelope me-2"></i>Send Inspection Report</h5>
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
                    <span id="sendBtnText"><i class="fa-solid fa-paper-plane me-1"></i>Send Report</span>
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
/* ── Toast helper ────────────────────────────────────────────────────────── */
function showToast(message, type) {
    type = type || 'success';
    const colorMap = {
        success: 'bg-success text-white',
        error:   'bg-danger  text-white',
        warning: 'bg-warning text-dark',
        info:    'bg-info    text-white'
    };
    const iconMap = {
        success: 'fa-circle-check',
        error:   'fa-circle-xmark',
        warning: 'fa-triangle-exclamation',
        info:    'fa-circle-info'
    };
    const cls = colorMap[type] || colorMap.success;
    const ico = iconMap[type]  || iconMap.success;
    const id  = 't' + Date.now() + Math.floor(Math.random() * 999);
    const html =
        '<div id="' + id + '" class="toast app-toast align-items-center ' + cls + ' border-0" role="alert" aria-live="assertive" aria-atomic="true">' +
            '<div class="d-flex">' +
                '<div class="toast-body"><i class="fa-solid ' + ico + ' me-2"></i>' + message + '</div>' +
                '<button type="button" class="btn-close ' + (type === 'warning' ? '' : 'btn-close-white') + ' me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>' +
            '</div>' +
        '</div>';
    const container = document.getElementById('toastContainer');
    container.insertAdjacentHTML('beforeend', html);
    const el = document.getElementById(id);
    const t  = new bootstrap.Toast(el, { delay: 4500 });
    t.show();
    el.addEventListener('hidden.bs.toast', () => el.remove());
}

@if(session('success'))
    document.addEventListener('DOMContentLoaded', () => showToast(@json(session('success')), 'success'));
@endif
@if(session('error'))
    document.addEventListener('DOMContentLoaded', () => showToast(@json(session('error')), 'error'));
@endif
@if($errors->any())
    document.addEventListener('DOMContentLoaded', () => showToast(@json($errors->first()), 'error'));
@endif

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

    fetch('{{ route("cesspool-records.send-report") }}', {
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
        showToast(data.message, data.success ? 'success' : 'error');
    })
    .catch(() => {
        document.getElementById('sendBtnText').style.display    = 'inline';
        document.getElementById('sendBtnSpinner').style.display = 'none';
        document.getElementById('btnSendReport').disabled = false;
        showToast('Something went wrong. Please try again.', 'error');
    });
});
</script>

<!-- Signature Pad library + canvas init + serialize on submit -->
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.2.0/dist/signature_pad.umd.min.js"></script>
<script>
    (function () {
        const canvas = document.getElementById('signature_canvas');
        if (!canvas || typeof SignaturePad === 'undefined') return;

        window.editSignaturePad = new SignaturePad(canvas, {
            backgroundColor: 'rgb(255, 255, 255)',
            penColor: 'rgb(13, 58, 23)',
            minWidth: 0.8,
            maxWidth: 2.2
        });

        let lastW = 0, lastH = 0;
        function resizeCanvas() {
            if (canvas.offsetWidth === 0 || canvas.offsetHeight === 0) return;
            if (canvas.offsetWidth === lastW && canvas.offsetHeight === lastH) return;

            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            const data  = window.editSignaturePad.toData();

            canvas.width  = canvas.offsetWidth  * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext('2d').scale(ratio, ratio);

            window.editSignaturePad.clear();
            if (data && data.length) window.editSignaturePad.fromData(data);

            lastW = canvas.offsetWidth;
            lastH = canvas.offsetHeight;
        }

        window.addEventListener('resize', resizeCanvas);
        if (typeof ResizeObserver !== 'undefined') {
            new ResizeObserver(resizeCanvas).observe(canvas);
        }
        resizeCanvas();

        document.getElementById('sig_clear_btn').addEventListener('click', function () {
            window.editSignaturePad.clear();
            document.getElementById('inspector_signature').value = '';
        });

        // Serialize canvas to hidden input on form submit (only if user drew something)
        const editForm = document.querySelector('form[action*="cesspool-records"]');
        if (editForm) {
            editForm.addEventListener('submit', function () {
                if (window.editSignaturePad && !window.editSignaturePad.isEmpty()) {
                    document.getElementById('inspector_signature').value =
                        window.editSignaturePad.toDataURL('image/png');
                } else {
                    document.getElementById('inspector_signature').value = '';
                }
            });
        }
    })();
</script>

</body>
</html>
