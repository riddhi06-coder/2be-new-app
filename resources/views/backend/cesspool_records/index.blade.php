<!doctype html>
<html lang="en">

<head>
    @include('components.backend.head')
    <style>
        .dt-action-btns { display: flex; gap: 4px; align-items: center; justify-content: center; flex-wrap: nowrap; }
        .dt-action-btns .btn { white-space: nowrap; font-size: 12px; padding: 3px 9px; }
        table.dataTable thead th { vertical-align: middle; white-space: nowrap; background: #f8f9fa; }
        table.dataTable tbody td { vertical-align: middle; }
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter { margin-bottom: 10px; }
        .dataTables_wrapper .dataTables_filter input { border: 1px solid #dee2e6; border-radius: 6px; padding: 4px 10px; }
        .dataTables_wrapper .dataTables_length select { border: 1px solid #dee2e6; border-radius: 6px; padding: 3px 6px; }
        .badge { font-size: 12px; padding: 4px 10px; }
        .filter-card { background: #f8f9fa; border-radius: 8px; padding: 14px 18px; margin-bottom: 20px; }
    </style>
</head>

@include('components.backend.header')
@include('components.backend.sidebar')

<div class="page-body">
    <div class="container-fluid">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mt-2">
                {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header pb-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <h5 class="mb-1 fw-semibold">Cesspool Inspection Records</h5>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0 small">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                    <li class="breadcrumb-item active">Cesspool Records</li>
                                </ol>
                            </nav>
                        </div>
                    </div>

                    <div class="card-body">

                        <!-- Filter Bar -->
                        <div class="filter-card">
                            <form method="GET" action="{{ route('cesspool-records.index') }}" class="row g-2 align-items-end">
                                <div class="col-auto">
                                    <label class="form-label small fw-semibold mb-1">From Date</label>
                                    <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control form-control-sm">
                                </div>
                                <div class="col-auto">
                                    <label class="form-label small fw-semibold mb-1">To Date</label>
                                    <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control form-control-sm">
                                </div>
                                <div class="col-auto">
                                    <label class="form-label small fw-semibold mb-1">Status</label>
                                    <select name="status" class="form-select form-select-sm" style="min-width:120px;">
                                        <option value="">All</option>
                                        <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Submitted</option>
                                        <option value="draft"     {{ request('status') === 'draft'     ? 'selected' : '' }}>Draft</option>
                                    </select>
                                </div>
                                <div class="col-auto d-flex gap-2">
                                    <button type="submit" class="btn btn-sm btn-primary px-3">Filter</button>
                                    <a href="{{ route('cesspool-records.index') }}" class="btn btn-sm btn-secondary px-3">Reset</a>
                                </div>
                            </form>
                        </div>

                        <!-- DataTable -->
                        <div class="table-responsive">
                            <table id="cesspool-table" class="display table table-hover table-bordered w-100" style="width:100%">
                                <thead>
                                    <tr>
                                        <th style="width:4%;">#</th>
                                        <th style="width:11%;">Date of Inspection</th>
                                        <th style="width:16%;">Inspector / Company</th>
                                        <th style="width:20%;">Site Address</th>
                                        <th style="width:13%;">Type of System</th>
                                        <th style="width:8%;  text-align:center;">Status</th>
                                        <th style="width:12%;">Submitted At</th>
                                        <th style="width:16%; text-align:center;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($records as $index => $record)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $record->date_of_pickup ? \Carbon\Carbon::parse($record->date_of_pickup)->format('d/m/Y') : '—' }}</td>
                                            <td>{{ $record->inspector_name_company ?? '—' }}</td>
                                            <td>{{ $record->site_address ?? '—' }}</td>
                                            <td>{{ $record->type_of_system ?? '—' }}</td>
                                            <td class="text-center">
                                                @if($record->is_draft)
                                                    <span class="badge bg-warning text-dark">Draft</span>
                                                @else
                                                    <span class="badge bg-success">Submitted</span>
                                                @endif
                                            </td>
                                            <td>{{ $record->inserted_at ? \Carbon\Carbon::parse($record->inserted_at)->format('m/d/Y H:i') : '—' }}</td>
                                            <td>
                                                <div class="dt-action-btns">
                                                    <a href="{{ route('cesspool-records.edit', $record->id) }}"
                                                       class="btn btn-sm btn-primary" title="Edit Record">
                                                        <i class="fa-solid fa-pen-to-square"></i> Edit
                                                    </a>
                                                    <!-- <a href="{{ route('cesspool-records.pdf', $record->id) }}"
                                                       class="btn btn-sm btn-danger" title="Download PDF" target="_blank">
                                                        <i class="fa-solid fa-file-pdf"></i> PDF
                                                    </a> -->
                                                    <!-- <button type="button"
                                                            class="btn btn-sm btn-info text-white btn-send-report"
                                                            data-id="{{ $record->id }}"
                                                            data-address="{{ addslashes($record->site_address) }}"
                                                            title="Send Report">
                                                        <i class="fa-solid fa-paper-plane"></i> Send
                                                    </button> -->
                                                    <form method="POST" action="{{ route('cesspool-records.destroy', $record->id) }}"
                                                          onsubmit="return confirm('Are you sure you want to delete this record?');" style="margin:0;">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                            <i class="fa-solid fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Send Report Modal -->
<div class="modal fade" id="sendReportModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-semibold">
                    <i class="fa-solid fa-envelope me-2 text-info"></i>Send Cesspool Inspection Report
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small mb-3">
                    The PDF report for the selected record will be generated and sent as an email attachment.
                </p>
                <input type="hidden" id="modal_record_id">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Recipient Email Address <span class="text-danger">*</span></label>
                    <input type="email" id="modal_to_email" class="form-control" placeholder="e.g. client@example.com">
                    <div id="modal_email_error" class="text-danger small mt-1" style="display:none;"></div>
                </div>
                <div class="alert alert-light border small py-2 mb-0" id="modal_record_info"></div>
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-info text-white px-4" id="btnSendReport">
                    <span id="sendBtnText"><i class="fa-solid fa-paper-plane me-1"></i>Send Report</span>
                    <span id="sendBtnSpinner" style="display:none;">
                        <span class="spinner-border spinner-border-sm me-1"></span>Sending...
                    </span>
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
$(document).ready(function () {
    $('#cesspool-table').DataTable({
        autoWidth : false,
        pageLength: 25,
        order     : [[1, 'desc']],
        columnDefs: [
            { targets: 0, orderable: true,  className: 'text-center', width: '4%'  },
            { targets: 1, width: '11%' },
            { targets: 2, width: '16%' },
            { targets: 3, width: '20%' },
            { targets: 4, width: '13%' },
            { targets: 5, orderable: false, className: 'text-center', width: '8%'  },
            { targets: 6, width: '12%' },
            { targets: 7, orderable: false, className: 'text-center', width: '16%' },
        ],
        language: {
            search         : 'Search records:',
            lengthMenu     : 'Show _MENU_ records',
            info           : 'Showing _START_ to _END_ of _TOTAL_ records',
            infoEmpty      : 'No records found',
            emptyTable     : 'No cesspool records available',
            zeroRecords    : 'No matching records found',
        },
    });
});

// Open send-report modal
document.querySelectorAll('.btn-send-report').forEach(function (btn) {
    btn.addEventListener('click', function () {
        document.getElementById('modal_record_id').value = this.dataset.id;
        document.getElementById('modal_record_info').textContent = 'Site: ' + this.dataset.address;
        document.getElementById('modal_to_email').value = '';
        document.getElementById('modal_email_error').style.display = 'none';
        new bootstrap.Modal(document.getElementById('sendReportModal')).show();
    });
});

document.getElementById('btnSendReport').addEventListener('click', function () {
    const email    = document.getElementById('modal_to_email').value.trim();
    const recordId = document.getElementById('modal_record_id').value;
    const errDiv   = document.getElementById('modal_email_error');

    if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        errDiv.textContent = 'Please enter a valid email address.';
        errDiv.style.display = 'block';
        return;
    }
    errDiv.style.display = 'none';

    const btn = document.getElementById('btnSendReport');
    document.getElementById('sendBtnText').style.display    = 'none';
    document.getElementById('sendBtnSpinner').style.display = 'inline';
    btn.disabled = true;

    fetch('{{ route("cesspool-records.send-report") }}', {
        method : 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body   : JSON.stringify({ record_id: recordId, to_email: email })
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById('sendBtnText').style.display    = 'inline';
        document.getElementById('sendBtnSpinner').style.display = 'none';
        btn.disabled = false;
        bootstrap.Modal.getInstance(document.getElementById('sendReportModal')).hide();

        const cls   = data.success ? 'alert-success' : 'alert-danger';
        const toast = document.createElement('div');
        toast.className = 'alert ' + cls + ' alert-dismissible fade show mt-2';
        toast.innerHTML = data.message + '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
        document.querySelector('.container-fluid').prepend(toast);
    })
    .catch(() => {
        document.getElementById('sendBtnText').style.display    = 'inline';
        document.getElementById('sendBtnSpinner').style.display = 'none';
        btn.disabled = false;
        errDiv.textContent = 'Something went wrong. Please try again.';
        errDiv.style.display = 'block';
    });
});
</script>

</body>
</html>
