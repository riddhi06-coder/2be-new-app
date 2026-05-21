<!doctype html>
<html lang="en">

<head>
    @include('components.backend.head')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .page-header-card {
            background: linear-gradient(135deg, #ffffff 0%, #f6f8fb 100%);
            border: 1px solid #e6eaf0;
            border-radius: 12px;
            padding: 20px 26px;
            margin-bottom: 22px;
            box-shadow: 0 1px 2px rgba(16, 24, 40, 0.04);
        }
        .page-header-card .page-title {
            font-size: 1.35rem;
            font-weight: 600;
            color: #1f2937;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .page-header-card .page-title .title-icon {
            width: 38px; height: 38px;
            display: inline-flex; align-items: center; justify-content: center;
            background: #fff5e6; color: #d97706;
            border-radius: 10px; font-size: 1rem;
        }
        .page-header-card .breadcrumb {
            margin: 6px 0 0 50px;
            font-size: 0.82rem;
        }
        .page-header-card .breadcrumb-item + .breadcrumb-item::before {
            content: "›"; color: #9aa3b2;
        }
        .page-header-card .breadcrumb-item a { color: #6b7280; text-decoration: none; }
        .page-header-card .breadcrumb-item a:hover { color: #0d6efd; }
        .page-header-card .breadcrumb-item.active { color: #1f2937; font-weight: 500; }
        .record-count-badge {
            background: #fff5e6; color: #d97706;
            font-size: 0.8rem; font-weight: 600;
            padding: 6px 12px; border-radius: 20px;
        }

        .filter-card {
            background: #f8fafc;
            border: 1px solid #eef0f4;
            border-radius: 10px;
            padding: 16px 20px;
            margin-bottom: 18px;
        }

        .dt-action-btns { display: inline-flex; gap: 6px; align-items: center; justify-content: center; flex-wrap: nowrap; }
        .dt-action-btns .btn { white-space: nowrap; font-size: 12px; padding: 5px 10px; line-height: 1; }
        .dt-action-btns .btn i { font-size: 13px; }
        .dt-action-btns .btn-delete { background: #fff; color: #dc3545; border: 1px solid #f1c1c5; }
        .dt-action-btns .btn-delete:hover { background: #dc3545; color: #fff; }

        table.dataTable thead th {
            vertical-align: middle; white-space: nowrap;
            background: #f6f8fb !important; color: #344054; font-weight: 600;
            border-bottom: 1px solid #e6eaf0 !important;
        }
        table.dataTable tbody td { vertical-align: middle; }
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter { margin-bottom: 12px; }
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #dee2e6; border-radius: 6px; padding: 5px 10px;
        }
        .dataTables_wrapper .dataTables_length select {
            border: 1px solid #dee2e6; border-radius: 6px; padding: 3px 6px;
        }
        .badge { font-size: 12px; padding: 5px 11px; font-weight: 500; }

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

        <!-- Professional Page Header -->
        <div class="page-header-card d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h5 class="page-title">
                    <span class="title-icon"><i class="fa-solid fa-water"></i></span>
                    Cesspool Inspection Records
                </h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-house-chimney me-1"></i>Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Cesspool Records</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="record-count-badge">
                    <i class="fa-regular fa-file-lines me-1"></i>
                    {{ count($records) }} {{ count($records) === 1 ? 'Record' : 'Records' }}
                </span>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <!-- Filter Bar (client-side, no reload) -->
                        <div class="filter-card">
                            <form id="filterForm" class="row g-2 align-items-end" onsubmit="return false;">
                                <div class="col-md-3 col-sm-6">
                                    <label class="form-label small fw-semibold mb-1">From Date</label>
                                    <input type="date" id="filter_from_date" class="form-control form-control-sm">
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <label class="form-label small fw-semibold mb-1">To Date</label>
                                    <input type="date" id="filter_to_date" class="form-control form-control-sm">
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <label class="form-label small fw-semibold mb-1">Status</label>
                                    <select id="filter_status" class="form-select form-select-sm">
                                        <option value="">All</option>
                                        <option value="submitted">Submitted</option>
                                        <option value="draft">Draft</option>
                                    </select>
                                </div>
                                <div class="col-md-3 col-sm-6 d-flex gap-2">
                                    <button type="button" id="btnApplyFilter" class="btn btn-sm btn-primary px-3">
                                        <i class="fa-solid fa-filter me-1"></i>Filter
                                    </button>
                                    <button type="button" id="btnResetFilter" class="btn btn-sm btn-secondary px-3">
                                        <i class="fa-solid fa-rotate-left me-1"></i>Reset
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- DataTable -->
                        <div class="table-responsive">
                            <table id="cesspool-table" class="display table table-hover table-bordered w-100" style="width:100%">
                                <thead>
                                    <tr>
                                        <th style="width:4%;">#</th>
                                        <th style="width:14%;">Date of Inspection</th>
                                        <th style="width:22%;">Inspector / Company</th>
                                        <th style="width:26%;">Site Address</th>
                                        <th style="width:10%; text-align:center;">Status</th>
                                        <th style="width:12%;">Submitted At</th>
                                        <th style="width:12%; text-align:center;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($records as $index => $record)
                                        @php
                                            $pickup    = $record->date_of_pickup ? \Carbon\Carbon::parse($record->date_of_pickup) : null;
                                            $submitted = $record->inserted_at    ? \Carbon\Carbon::parse($record->inserted_at)    : null;
                                        @endphp
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td data-order="{{ $pickup ? $pickup->format('Y-m-d') : '' }}">
                                                {{ $pickup ? $pickup->format('d-m-Y') : '' }}
                                            </td>
                                            <td>{{ $record->inspector_name_company ?: '' }}</td>
                                            <td>{{ $record->site_address ?: '' }}</td>
                                            <td class="text-center" data-status="{{ $record->is_draft ? 'draft' : 'submitted' }}">
                                                @if($record->is_draft)
                                                    <span class="badge bg-warning text-dark">Draft</span>
                                                @else
                                                    <span class="badge bg-success">Submitted</span>
                                                @endif
                                            </td>
                                            <td data-order="{{ $submitted ? $submitted->format('Y-m-d H:i:s') : '' }}">
                                                {{ $submitted ? $submitted->format('d-m-Y H:i') : '' }}
                                            </td>
                                            <td>
                                                <div class="dt-action-btns">
                                                    <a href="{{ route('cesspool-records.edit', $record->id) }}"
                                                       class="btn btn-sm btn-primary" title="Edit Record">
                                                        <i class="fa-solid fa-pen-to-square"></i> Edit
                                                    </a>
                                                    <form method="POST" action="{{ route('cesspool-records.destroy', $record->id) }}"
                                                          onsubmit="return confirm('Are you sure you want to delete this record? This cannot be undone.');"
                                                          style="display:inline-block; margin:0;">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-delete" title="Delete Record">
                                                            <i class="fa-solid fa-trash-can"></i> Delete
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

/* ── DataTable + client-side filter (no reload) ──────────────────────────── */
let cesspoolTable;

$.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
    if (settings.nTable.id !== 'cesspool-table') return true;

    const fromVal = document.getElementById('filter_from_date').value;
    const toVal   = document.getElementById('filter_to_date').value;
    const statVal = document.getElementById('filter_status').value;

    const row       = settings.aoData[dataIndex].nTr;
    const dateIso   = row.children[1].getAttribute('data-order') || '';
    const rowStatus = row.children[4].getAttribute('data-status') || '';

    if (fromVal && (!dateIso || dateIso < fromVal)) return false;
    if (toVal   && (!dateIso || dateIso > toVal))   return false;
    if (statVal && rowStatus !== statVal)           return false;

    return true;
});

$(document).ready(function () {
    cesspoolTable = $('#cesspool-table').DataTable({
        autoWidth : false,
        pageLength: 25,
        order     : [[1, 'desc']],
        columnDefs: [
            { targets: 0, orderable: true,  className: 'text-center', width: '4%'  },
            { targets: 1, width: '14%' },
            { targets: 2, width: '22%' },
            { targets: 3, width: '26%' },
            { targets: 4, orderable: false, className: 'text-center', width: '10%' },
            { targets: 5, width: '12%' },
            { targets: 6, orderable: false, className: 'text-center', width: '12%' },
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

    document.getElementById('btnApplyFilter').addEventListener('click', function () {
        cesspoolTable.draw();
    });

    document.getElementById('btnResetFilter').addEventListener('click', function () {
        document.getElementById('filter_from_date').value = '';
        document.getElementById('filter_to_date').value   = '';
        document.getElementById('filter_status').value    = '';
        cesspoolTable.draw();
    });

    ['filter_from_date', 'filter_to_date', 'filter_status'].forEach(function (id) {
        document.getElementById(id).addEventListener('change', function () { cesspoolTable.draw(); });
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

(function () {
    var btnSend = document.getElementById('btnSendReport');
    if (!btnSend) return;

    btnSend.addEventListener('click', function () {
        const email    = document.getElementById('modal_to_email').value.trim();
        const recordId = document.getElementById('modal_record_id').value;
        const errDiv   = document.getElementById('modal_email_error');

        if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            errDiv.textContent = 'Please enter a valid email address.';
            errDiv.style.display = 'block';
            return;
        }
        errDiv.style.display = 'none';

        document.getElementById('sendBtnText').style.display    = 'none';
        document.getElementById('sendBtnSpinner').style.display = 'inline';
        btnSend.disabled = true;

        fetch('{{ route("cesspool-records.send-report") }}', {
            method : 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body   : JSON.stringify({ record_id: recordId, to_email: email })
        })
        .then(res => res.json())
        .then(data => {
            document.getElementById('sendBtnText').style.display    = 'inline';
            document.getElementById('sendBtnSpinner').style.display = 'none';
            btnSend.disabled = false;
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
            btnSend.disabled = false;
            errDiv.textContent = 'Something went wrong. Please try again.';
            errDiv.style.display = 'block';
        });
    });
})();
</script>

</body>
</html>
