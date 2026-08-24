<!doctype html>
<html lang="en">

    <head>
        @include('components.frontend.head')
    </head>

    <body>

        @include('components.frontend.employee_header')



            <section class="pumping-log">
                <div class="container">
                    <div class="col-md-12">
                    <div class="pumping-log__content">
                        <!-- <p class="pumping-log__welcome">Incident Report Form</p> -->

                        <h1 class="pumping-log__title">
                        <span class="pumping-log__brand">Incident  </span>
                        Report Form
                        </h1>

                        <p class="pumping-log__description">
                        Please provide accurate details about the incident. All reports are confidential and used to improve safety.   
                        </p>
                    </div>
                    </div>
                </div>
                <svg class="shape-one" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none">
                    <path class="elementor-shape-fill" d="M421.9,6.5c22.6-2.5,51.5,0.4,75.5,5.3c23.6,4.9,70.9,23.5,100.5,35.7c75.8,32.2,133.7,44.5,192.6,49.7
                    c23.6,2.1,48.7,3.5,103.4-2.5c54.7-6,106.2-25.6,106.2-25.6V0H0v30.3c0,0,72,32.6,158.4,30.5c39.2-0.7,92.8-6.7,134-22.4
                    c21.2-8.1,52.2-18.2,79.7-24.2C399.3,7.9,411.6,7.5,421.9,6.5z"></path>
                </svg>
            </section>

            <section class="pumping-incident-report-wrap">
            <div class="container">
                <div class="col-md-12">
                <form action="{{ route('frontend.employee_incident_report_store') }}" method="POST" enctype="multipart/form-data" id="incidentForm" novalidate>
                @csrf
                @if ($errors->any())
                    <div class="alert alert-danger" style="border-radius:10px;">{{ $errors->first() }}</div>
                @endif
                <!-- 1. Incident Information -->
                <div class="incident-report-box">
                    <div class="incident-title">
                    <span class="incident-number">
                        1
                    </span>
                    <h4>
                        Incident Information
                    </h4>
                    </div>
                    <div class="row">
                    <div class="form-group col-md-3 col-sm-12">
                        <label>
                        Reported By
                        <span>
                            *
                        </span>
                        </label>
                        <input type="text" class="form-control" name="report_name" id="report_name" value="{{ auth()->user()->name }}" readonly>
                    </div>
                    <div class="form-group col-md-3 col-sm-12">
                        <label>
                        Employee Involved
                        </label>
                        <select class="form-control" name="employee_id" id="employee_id">
                        <option value="">Select employee</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" {{ (int) old('employee_id') === $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
                        @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3 col-sm-12">
                        <label>
                        Incident Date
                        <span>
                            *
                        </span>
                        </label>
                        <input type="date" class="form-control" name="incident_date" id="incident_date" required="">
                        <small class="field-error" data-error-for="incident_date"></small>
                    </div>
                    <div class="form-group col-md-3 col-sm-12">
                        <label>
                        Incident Time
                        </label>
                        <input type="time" class="form-control" name="incident_time" id="incident_time">
                        <small class="field-error" data-error-for="incident_time"></small>
                    </div>
                    <div class="form-group col-md-3 col-sm-12">
                        <label>
                        Location / Site
                        <span>
                            *
                        </span>
                        </label>
                        <input type="text" class="form-control" name="incident_location" id="incident_location" placeholder="Enter location or site" required="">
                        <small class="field-error" data-error-for="incident_location"></small>
                    </div>
                    <div class="form-group col-md-3 col-sm-12">
                        <label>
                        Category
                        <span>
                            *
                        </span>
                        </label>
                        <select class="form-control" name="category" id="category" required="">
                        <option value="">Select category</option>
                        @foreach($categories as $val => $label)
                            <option value="{{ $val }}" {{ old('category') === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                        </select>
                        <small class="field-error" data-error-for="category"></small>
                    </div>
                    <div class="form-group col-md-3 col-sm-12">
                        <label>
                        Severity
                        <span>
                            *
                        </span>
                        </label>
                        <select class="form-control" name="severity" id="severity" required="">
                        <option value="">Select severity</option>
                        @foreach($severities as $val => $label)
                            <option value="{{ $val }}" {{ old('severity') === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                        </select>
                        <small class="field-error" data-error-for="severity"></small>
                    </div>
                    <div class="form-group col-md-12 col-sm-12">
                        <label>
                        Description
                        <span>
                            *
                        </span>
                        </label>
                        <textarea class="form-control" name="description" id="description" rows="4" placeholder="Describe the incident in detail..." required="">{{ old('description') }}</textarea>
                        <small class="field-error" data-error-for="description"></small>
                    </div>
                    </div>
                </div>
                <!-- 2. Additional Details -->
                <div class="incident-report-box">
                    <div class="incident-title">
                    <span class="incident-number">
                        2
                    </span>
                    <h4>
                        Additional Details
                    </h4>
                    </div>
                    <div class="row">
                    <div class="form-group col-md-8">
                        <label>
                        Immediate Action Taken
                        </label>
                        <textarea class="form-control" name="immediate_action" id="immediate_action" rows="4" placeholder="Describe the immediate action taken...">{{ old('immediate_action') }}</textarea>
                        <small class="field-error" data-error-for="immediate_action"></small>
                    </div>
                    <div class="form-group col-md-4">
                        <label>
                        Witnesses
                        </label>
                        <input type="text" class="form-control" name="witnesses" id="witnesses" placeholder="Enter witness names (comma separated)" value="{{ old('witnesses') }}">
                        <small class="field-error" data-error-for="witnesses"></small>
                    </div>
                    </div>
                </div>
                <!-- 3. Photos -->
                <div class="incident-report-box">
                    <div class="incident-title">
                    <span class="incident-number">
                        3
                    </span>
                    <h4>
                        Photos
                    </h4>
                    </div>
                    <div class="upload-photo-box">
                    <input type="file" name="incident_photos[]" id="incident_photos" multiple accept=".jpg,.jpeg,.png,.gif,.webp">
                    <div class="upload-content">
                        <i class="fa fa-cloud-upload">
                        </i>
                        <strong>
                        Upload Photos
                        </strong>
                        <p>
                        Drag and drop photos here or click to browse
                        </p>
                        <small>
                        JPG, PNG, GIF or WEBP &mdash; up to 2 MB each
                        </small>
                    </div>
                    </div>
                    <div class="photo-preview-grid" id="photoPreview"></div>
                    <small class="field-error" data-error-for="incident_photos"></small>
                    <p class="upload-disclaimer">
                        * Only JPG, PNG, GIF or WEBP images &mdash; max {{ round(config('uploads.image_max_kb') / 1024, 1) }} MB each, up to 6 photos.
                    </p>
                </div>
                <!-- Buttons -->
                <div class="incident-form-footer">
                    <div class="form-buttons">
                    <a href="{{ route('frontend.employee_dashboard') }}" class="btn btn-cancel">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-submit">
                        <i class="fa fa-paper-plane">
                        </i>
                        Submit Report
                    </button>
                    </div>
                </div>
                </form>
                </div>
            </div>
            </section>




        @include('components.frontend.footer')

        @include('components.frontend.main-js')

        <style>
            .field-error { color: #e73b3b; font-size: 12px; margin-top: 4px; display: none; }
            .field-error:not(:empty) { display: block; }
            .form-control.is-invalid,
            select.form-control.is-invalid { border-color: #e73b3b; box-shadow: 0 0 0 .15rem rgba(231,59,59,.15); }

            /* Photo upload previews */
            .photo-preview-grid { display: flex; flex-wrap: wrap; gap: 12px; margin-top: 14px; }
            .photo-preview-item {
                position: relative; width: 110px; height: 110px; border-radius: 10px;
                overflow: hidden; border: 1px solid #e3e3e3; background: #fafafa;
                box-shadow: 0 1px 4px rgba(0,0,0,.06);
            }
            .photo-preview-item img { width: 100%; height: 100%; object-fit: cover; display: block; }
            .photo-preview-remove {
                position: absolute; top: 4px; right: 4px; width: 22px; height: 22px;
                border: none; border-radius: 50%; background: rgba(117,4,6,.92); color: #fff;
                font-size: 14px; line-height: 1; cursor: pointer; display: flex;
                align-items: center; justify-content: center; padding: 0;
                transition: background .15s ease, transform .15s ease;
            }
            .photo-preview-remove:hover { background: #e73b3b; transform: scale(1.08); }
            .photo-preview-name {
                position: absolute; bottom: 0; left: 0; right: 0; padding: 3px 6px;
                background: rgba(0,0,0,.55); color: #fff; font-size: 10px;
                white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
            }
            .upload-disclaimer {
                margin-top: 10px; margin-bottom: 0;
                font-size: 13px; font-weight: 600; color: #e73b3b;
            }
        </style>

        <script>
        (function () {
            var form = document.getElementById('incidentForm');
            if (!form) return;

            var MAX_PHOTO_KB  = {{ (int) config('uploads.image_max_kb') }};   // backend limit (KB)
            var MAX_PHOTOS    = 6;
            var ALLOWED_TYPES = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

            // Show/clear the message under a field. Returns true when the field is valid.
            function setError(field, msg) {
                var box   = document.querySelector('[data-error-for="' + field + '"]');
                var input = document.getElementById(field);
                if (box)   { box.textContent = msg || ''; }
                if (input) { input.classList.toggle('is-invalid', !!msg); }
                return !msg;
            }

            var val = function (id) { return (document.getElementById(id).value || '').trim(); };

            var validators = {
                incident_date: function () {
                    var v = val('incident_date');
                    if (!v) return setError('incident_date', 'Please select the incident date.');
                    var d = new Date(v + 'T00:00:00');
                    if (isNaN(d.getTime())) return setError('incident_date', 'Please enter a valid date.');
                    var today = new Date(); today.setHours(0, 0, 0, 0);
                    if (d > today) return setError('incident_date', 'The incident date cannot be in the future.');
                    return setError('incident_date', '');
                },
                incident_time: function () {
                    // Optional — only flag if the browser gives us a partial/invalid value.
                    var el = document.getElementById('incident_time');
                    if (el.value && !el.checkValidity()) return setError('incident_time', 'Please enter a valid time.');
                    return setError('incident_time', '');
                },
                incident_location: function () {
                    var v = val('incident_location');
                    if (!v) return setError('incident_location', 'Please enter the location or site.');
                    if (v.length < 3)   return setError('incident_location', 'Location must be at least 3 characters.');
                    if (v.length > 255) return setError('incident_location', 'Location may not exceed 255 characters.');
                    return setError('incident_location', '');
                },
                category: function () {
                    if (!document.getElementById('category').value) return setError('category', 'Please select a category.');
                    return setError('category', '');
                },
                severity: function () {
                    if (!document.getElementById('severity').value) return setError('severity', 'Please select a severity level.');
                    return setError('severity', '');
                },
                description: function () {
                    var v = val('description');
                    if (!v) return setError('description', 'Please describe the incident.');
                    if (v.length < 10) return setError('description', 'Description must be at least 10 characters.');
                    return setError('description', '');
                },
                witnesses: function () {
                    if (val('witnesses').length > 255) return setError('witnesses', 'Witnesses may not exceed 255 characters.');
                    return setError('witnesses', '');
                },
                immediate_action: function () {
                    return setError('immediate_action', ''); // optional, no constraints
                },
                incident_photos: function () {
                    var files = document.getElementById('incident_photos').files;
                    if (!files || !files.length) return setError('incident_photos', '');
                    if (files.length > MAX_PHOTOS) return setError('incident_photos', 'You can upload up to ' + MAX_PHOTOS + ' photos at a time.');
                    for (var i = 0; i < files.length; i++) {
                        var f = files[i];
                        var okType = ALLOWED_TYPES.indexOf(f.type) !== -1 || /\.(jpe?g|png|gif|webp)$/i.test(f.name);
                        if (!okType) return setError('incident_photos', '"' + f.name + '" is not a supported image (JPG, PNG, GIF or WEBP).');
                        if (f.size > MAX_PHOTO_KB * 1024) return setError('incident_photos', '"' + f.name + '" is larger than ' + (MAX_PHOTO_KB / 1024) + ' MB.');
                    }
                    return setError('incident_photos', '');
                }
            };

            function validateAll() {
                var ok = true;
                for (var key in validators) {
                    if (validators.hasOwnProperty(key) && !validators[key]()) ok = false;
                }
                return ok;
            }

            // Re-validate each field as the user edits/leaves it.
            Object.keys(validators).forEach(function (key) {
                var el = document.getElementById(key);
                if (!el) return;
                var ev = (el.tagName === 'SELECT' || el.type === 'file' || el.type === 'date' || el.type === 'time') ? 'change' : 'input';
                el.addEventListener(ev, validators[key]);
                el.addEventListener('blur', validators[key]);
            });

            // ---- Photo previews with removable thumbnails ----
            var photoInput  = document.getElementById('incident_photos');
            var previewGrid = document.getElementById('photoPreview');
            var selectedFiles = [];

            function syncInput() {
                var dt = new DataTransfer();
                selectedFiles.forEach(function (f) { dt.items.add(f); });
                photoInput.files = dt.files; // setting .files does NOT re-fire change
            }

            function renderPreviews() {
                previewGrid.innerHTML = '';
                selectedFiles.forEach(function (file, index) {
                    var item = document.createElement('div');
                    item.className = 'photo-preview-item';

                    var img = document.createElement('img');
                    var url = URL.createObjectURL(file);
                    img.src = url;
                    img.onload = function () { URL.revokeObjectURL(url); };
                    item.appendChild(img);

                    var name = document.createElement('span');
                    name.className = 'photo-preview-name';
                    name.textContent = file.name;
                    item.appendChild(name);

                    var remove = document.createElement('button');
                    remove.type = 'button';
                    remove.className = 'photo-preview-remove';
                    remove.setAttribute('aria-label', 'Remove photo');
                    remove.innerHTML = '&times;';
                    remove.addEventListener('click', function () {
                        selectedFiles.splice(index, 1);
                        syncInput();
                        renderPreviews();
                        validators.incident_photos();
                    });
                    item.appendChild(remove);

                    previewGrid.appendChild(item);
                });
            }

            if (photoInput) {
                photoInput.addEventListener('change', function () {
                    // Append newly picked files (skip duplicates), then sync back to the input.
                    Array.prototype.forEach.call(photoInput.files, function (f) {
                        var dup = selectedFiles.some(function (s) { return s.name === f.name && s.size === f.size; });
                        if (!dup) selectedFiles.push(f);
                    });
                    syncInput();
                    renderPreviews();
                    validators.incident_photos();
                });
            }

            form.addEventListener('submit', function (e) {
                if (!validateAll()) {
                    e.preventDefault();
                    var firstInvalid = form.querySelector('.is-invalid');
                    if (firstInvalid) {
                        firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        try { firstInvalid.focus({ preventScroll: true }); } catch (err) { firstInvalid.focus(); }
                    }
                    return;
                }
                // Valid — block double submission and show progress.
                var btn = form.querySelector('.btn-submit');
                if (btn) {
                    btn.disabled = true;
                    btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Submitting...';
                }
            });
        })();
        </script>

    </body>

</html>
