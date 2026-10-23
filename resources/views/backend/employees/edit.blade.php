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
                <div class="col-6"><h3>Edit Employee</h3></div>
                <div class="col-6">
                    <ol class="breadcrumb justify-content-end">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa fa-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.employees.index') }}">Employees</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        @if(is_null($employee->welcome_email_sent_at))
                            <div class="alert alert-warning">
                                <strong>Welcome email pending.</strong> This employee has not received their credentials yet.
                                Enter a new password below and save to send the welcome email now.
                            </div>
                        @else
                            <div class="alert alert-success py-2">
                                Welcome email was sent on {{ $employee->welcome_email_sent_at->format('d M Y, h:i A') }}. It will not be sent again.
                            </div>
                        @endif
                        <form action="{{ route('admin.employees.update', $employee) }}" method="POST" class="theme-form">
                            @csrf @method('PUT')
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name', $employee->name) }}" required placeholder="e.g. John Doe">
                                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                           value="{{ old('email', $employee->email) }}" required placeholder="name@example.com">
                                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Start Date <small class="text-muted">(used for the 90-day quarterly assessment)</small></label>
                                    <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror"
                                           value="{{ old('start_date', optional($employee->start_date)->format('Y-m-d')) }}">
                                    @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Status</label>
                                    <div class="form-check form-switch mt-2">
                                        <input type="hidden" name="is_active" value="0">
                                        <input class="form-check-input" type="checkbox" id="emp-active" name="is_active" value="1" {{ old('is_active', $employee->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="emp-active">Active <small class="text-muted">(can log in)</small></label>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Accrues PTO</label>
                                    <div class="form-check form-switch mt-2">
                                        <input type="hidden" name="accrues_pto" value="0">
                                        <input class="form-check-input" type="checkbox" id="emp-pto" name="accrues_pto" value="1" {{ old('accrues_pto', $employee->accrues_pto) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="emp-pto">Eligible <small class="text-muted">(full-time)</small></label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">New Password <small class="text-muted">(leave blank to keep current)</small></label>
                                    <div class="position-relative">
                                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror"
                                               placeholder="Minimum 8 characters" style="padding-right: 2.5rem;" autocomplete="new-password">
                                        <span class="toggle-password" data-target="password" title="Show/Hide password"
                                              style="position:absolute; top:50%; right:.9rem; transform:translateY(-50%); cursor:pointer; color:#6c757d; z-index:5;">
                                            <i class="fa fa-eye"></i>
                                        </span>
                                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Confirm New Password</label>
                                    <div class="position-relative">
                                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control"
                                               placeholder="Re-enter the new password" style="padding-right: 2.5rem;" autocomplete="new-password">
                                        <span class="toggle-password" data-target="password_confirmation" title="Show/Hide password"
                                              style="position:absolute; top:50%; right:.9rem; transform:translateY(-50%); cursor:pointer; color:#6c757d; z-index:5;">
                                            <i class="fa fa-eye"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                            <a href="{{ route('admin.employees.index') }}" class="btn btn-light">Cancel</a>
                        </form>
                    </div>
                </div>

                {{-- Quarterly (90-day) assessment tracking --}}
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-3"><i class="fa fa-clipboard me-2"></i>Quarterly Assessment</h5>

                        @if(! $employee->start_date)
                            <p class="text-muted mb-0">Set a <strong>Start Date</strong> above and save to enable 90-day quarterly assessment tracking.</p>
                        @else
                            @php
                                $nextDue = $employee->nextAssessmentDueDate();
                                $isDue   = $employee->isAssessmentDue();
                            @endphp
                            <div class="row g-3 align-items-center">
                                <div class="col-md-3">
                                    <div class="small text-muted text-uppercase">Status</div>
                                    @if($isDue)
                                        <span class="badge bg-danger">Due now</span>
                                    @else
                                        <span class="badge bg-success">On track</span>
                                    @endif
                                </div>
                                <div class="col-md-3">
                                    <div class="small text-muted text-uppercase">Last completed</div>
                                    <div>{{ $employee->last_assessment_at ? $employee->last_assessment_at->format('M j, Y') : 'Not yet' }}</div>
                                </div>
                                <div class="col-md-3">
                                    <div class="small text-muted text-uppercase">Next due</div>
                                    <div class="{{ $isDue ? 'text-danger fw-semibold' : '' }}">
                                        {{ optional($nextDue)->format('M j, Y') }}
                                        @if($nextDue)<small class="text-muted">({{ $nextDue->diffForHumans() }})</small>@endif
                                    </div>
                                </div>
                                <div class="col-md-3 text-md-end">
                                    @if(auth()->user()->hasPermission('employees.edit'))
                                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="collapse" data-bs-target="#completeAssessmentForm">
                                            <i class="fa fa-check me-1"></i> Mark Completed
                                        </button>
                                    @endif
                                </div>
                            </div>

                            @if(auth()->user()->hasPermission('employees.edit'))
                                <div class="collapse mt-3" id="completeAssessmentForm">
                                    <form action="{{ route('admin.employees.complete-assessment', $employee) }}" method="POST"
                                          onsubmit="return confirm('Record a completed quarterly assessment for this employee, dated today?');">
                                        @csrf
                                        <label class="form-label">Notes <small class="text-muted">(optional)</small></label>
                                        <textarea name="notes" class="form-control mb-2" rows="2" placeholder="Any notes about this assessment..."></textarea>
                                        <button type="submit" class="btn btn-success btn-sm">Record as completed today</button>
                                    </form>
                                </div>
                            @endif

                            @if($employee->quarterlyAssessments->isNotEmpty())
                                <hr>
                                <div class="small text-muted text-uppercase mb-2">History</div>
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover mb-0">
                                        <thead>
                                            <tr><th>Date</th><th>Recorded by</th><th>Notes</th></tr>
                                        </thead>
                                        <tbody>
                                            @foreach($employee->quarterlyAssessments as $a)
                                                <tr>
                                                    <td>{{ $a->assessed_on->format('M j, Y') }}</td>
                                                    <td>{{ optional($a->assessor)->name ?? '—' }}</td>
                                                    <td>{{ $a->notes ?: '—' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

                {{-- Paid Time Off (PTO) --}}
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-3"><i class="fa fa-calendar-o me-2"></i>Paid Time Off (PTO)</h5>

                        @if(! $employee->start_date)
                            <p class="text-muted mb-0">Set a <strong>Start Date</strong> above and save to enable PTO tracking (the accrual tier is based on years of service).</p>
                        @elseif(! $employee->accrues_pto)
                            <p class="text-muted mb-0">This employee is marked <strong>not eligible</strong> for PTO. Turn on “Accrues PTO” above and save to track a balance.</p>
                        @else
                            @php
                                $tier    = $employee->ptoTier();
                                $balance = $employee->ptoBalance();
                                $cap     = $employee->ptoCap();
                            @endphp
                            <div class="pto-summary">
                                <div class="pto-stat">
                                    <div class="pto-stat__n">{{ number_format($balance, 2) }}</div>
                                    <div class="pto-stat__l">Current balance (hrs)</div>
                                </div>
                                <div class="pto-stat">
                                    <div class="pto-stat__n">{{ $tier ? number_format($tier['per_pay_period'], 2) : '—' }}</div>
                                    <div class="pto-stat__l">Accrual / pay period</div>
                                </div>
                                <div class="pto-stat">
                                    <div class="pto-stat__n">{{ $cap ? number_format($cap, 0) : '—' }}</div>
                                    <div class="pto-stat__l">Max / cap (hrs)</div>
                                </div>
                                <div class="pto-stat">
                                    <div class="pto-stat__n">{{ $employee->ptoYearsOfService() }}</div>
                                    <div class="pto-stat__l">Years of service</div>
                                </div>
                            </div>
                            <p class="text-muted small mb-3">
                                Tier: <strong>{{ $tier['label'] ?? '—' }}</strong>
                                &middot; Annual accrual {{ $tier['annual'] ?? '—' }} hrs
                                @if($cap && $balance >= $cap)<span class="text-danger ms-2"><i class="fa fa-exclamation-triangle"></i> At cap — accrual pauses until PTO is used.</span>@endif
                            </p>

                            @if(auth()->user()->hasPermission('employees.edit'))
                                <button type="button" class="btn btn-primary btn-sm mb-3" data-bs-toggle="collapse" data-bs-target="#ptoEntryForm">
                                    <i class="fa fa-plus me-1"></i> Add PTO entry
                                </button>
                                <div class="collapse" id="ptoEntryForm">
                                    <form action="{{ route('admin.employees.pto.store', $employee) }}" method="POST" class="border rounded p-3 mb-3 bg-light">
                                        @csrf
                                        <div class="row g-2 align-items-end">
                                            <div class="col-md-3">
                                                <label class="form-label mb-1">Type</label>
                                                <select name="type" id="ptoType" class="form-select form-select-sm">
                                                    @foreach(\App\Models\PtoLedgerEntry::TYPES as $k => $label)
                                                        <option value="{{ $k }}">{{ $label }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2" id="ptoDirectionWrap" style="display:none;">
                                                <label class="form-label mb-1">Direction</label>
                                                <select name="direction" class="form-select form-select-sm">
                                                    <option value="add">Add (+)</option>
                                                    <option value="subtract">Subtract (−)</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label mb-1">Hours</label>
                                                <input type="number" step="0.01" min="0.01" name="hours" class="form-control form-control-sm" placeholder="e.g. 8" required>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label mb-1">Effective date</label>
                                                <input type="date" name="effective_date" class="form-control form-control-sm" value="{{ now()->toDateString() }}" required>
                                            </div>
                                            <div class="col-md-2">
                                                <button type="submit" class="btn btn-success btn-sm w-100">Save</button>
                                            </div>
                                            <div class="col-12">
                                                <input type="text" name="note" class="form-control form-control-sm mt-2" placeholder="Note (optional) — e.g. seeded from paystub, vacation 10/20–10/21">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            @endif

                            @if($employee->ptoLedgerEntries->isNotEmpty())
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover align-middle mb-0">
                                        <thead>
                                            <tr><th>Date</th><th>Type</th><th class="text-end">Hours</th><th>Note</th><th>By</th></tr>
                                        </thead>
                                        <tbody>
                                            @foreach($employee->ptoLedgerEntries as $e)
                                                <tr>
                                                    <td>{{ $e->effective_date->format('M j, Y') }}</td>
                                                    <td><span class="badge bg-light text-dark">{{ $e->type_label }}</span></td>
                                                    <td class="text-end fw-semibold {{ $e->hours < 0 ? 'text-danger' : 'text-success' }}">
                                                        {{ $e->hours > 0 ? '+' : '' }}{{ number_format($e->hours, 2) }}
                                                    </td>
                                                    <td class="text-muted">{{ $e->note ?: '—' }}</td>
                                                    <td class="text-muted">{{ optional($e->creator)->name ?? '—' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted mb-0">No PTO entries yet. Use <strong>Add PTO entry</strong> → <em>Opening balance</em> to seed the current balance from their paystub.</p>
                            @endif
                        @endif
                    </div>
                </div>

                {{-- Signed documents filed under this employee --}}
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-3"><i class="fa fa-file-text-o me-2"></i>Signed Documents</h5>
                        @if($signedDocuments->isEmpty())
                            <p class="text-muted mb-0">This employee hasn't signed any documents yet.</p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>Document</th>
                                            <th>Signed as</th>
                                            <th>When</th>
                                            <th class="text-end">Certificate</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($signedDocuments as $ack)
                                            <tr>
                                                <td><i class="fa fa-file-pdf-o text-danger me-1"></i>{{ optional($ack->document)->title ?? '(deleted document)' }}</td>
                                                <td>{{ $ack->signed_name }}</td>
                                                <td>{{ $ack->acknowledged_at->format('M j, Y g:i A') }}</td>
                                                <td class="text-end">
                                                    @if($ack->signed_pdf_path)
                                                        <a href="{{ asset($ack->signed_pdf_path) }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary"><i class="fa fa-certificate"></i> Certificate</a>
                                                    @else
                                                        <span class="text-muted">—</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
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

@include('components.backend.main-js')
<script>
    document.querySelectorAll('.toggle-password').forEach(function (toggle) {
        toggle.addEventListener('click', function () {
            var input = document.getElementById(this.getAttribute('data-target'));
            if (!input) return;
            var icon = this.querySelector('i');
            var show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            icon.classList.toggle('fa-eye', !show);
            icon.classList.toggle('fa-eye-slash', show);
        });
    });

    // PTO: show the +/- direction selector only for "adjustment" entries.
    (function () {
        var typeSel = document.getElementById('ptoType');
        var dirWrap = document.getElementById('ptoDirectionWrap');
        if (!typeSel || !dirWrap) return;
        function sync() { dirWrap.style.display = typeSel.value === 'adjustment' ? '' : 'none'; }
        typeSel.addEventListener('change', sync);
        sync();
    })();
</script>
</body>
</html>
