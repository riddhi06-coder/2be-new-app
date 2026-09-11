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
                <div class="col-6"><h3>{{ $canManage ? 'Incident Reports' : 'My Incident Reports' }}</h3></div>
                <div class="col-6 text-end">
                    @if(auth()->user()->hasPermission('incident-reports.create'))
                        <a href="{{ route('admin.incident-reports.create') }}" class="btn btn-primary">+ New Incident Report</a>
                    @endif
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        {{-- Filter bar (instant client-side filtering — no page reload) --}}
                        <div class="d-flex align-items-end gap-2 flex-wrap mb-4" id="incidentFilters">
                            <div class="d-flex flex-column">
                                <label class="small fw-semibold text-muted mb-1">From Date</label>
                                <input type="date" id="f_from" class="form-control form-control-sm" style="min-width:150px;">
                            </div>
                            <div class="d-flex flex-column">
                                <label class="small fw-semibold text-muted mb-1">To Date</label>
                                <input type="date" id="f_to" class="form-control form-control-sm" style="min-width:150px;">
                            </div>
                            <div class="d-flex flex-column">
                                <label class="small fw-semibold text-muted mb-1">Year</label>
                                <select id="f_year" class="form-select form-select-sm" style="min-width:110px;">
                                    <option value="">All</option>
                                    @foreach($years as $y)
                                        <option value="{{ $y }}">{{ $y }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="d-flex flex-column">
                                <label class="small fw-semibold text-muted mb-1">Category</label>
                                <select id="f_cat" class="form-select form-select-sm" style="min-width:150px;">
                                    <option value="">All</option>
                                    @foreach($categories as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="d-flex flex-column">
                                <label class="small fw-semibold text-muted mb-1">Status</label>
                                <select id="f_status" class="form-select form-select-sm" style="min-width:130px;">
                                    <option value="">All</option>
                                    @foreach($statuses as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="button" id="f_reset" class="btn btn-sm btn-secondary">Reset</button>
                            <a href="{{ route('admin.incident-reports.export-csv') }}" id="f_export" class="btn btn-sm btn-success ms-auto">
                                <i class="fa fa-file-excel-o me-1"></i>Export CSV
                            </a>
                        </div>

                        <div class="table-responsive">
                            <table id="incidentReportsTable" class="display table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Ref #</th>
                                        <th>Date</th>
                                        <th>Reported By</th>
                                        <th>Status</th>
                                        <th>Source</th>
                                        <th class="text-end" style="min-width:180px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reports as $r)
                                        <tr data-date="{{ optional($r->incident_date)->format('Y-m-d') }}" data-category="{{ $r->category }}" data-status="{{ $r->status }}">
                                            <td>{{ $r->reference_no }}</td>
                                            <td>{{ optional($r->incident_date)->format('d M Y') }}</td>
                                            <td>{{ $r->reporter_name ?: ($r->reporter->name ?? '—') }}</td>
                                            <td><span class="badge {{ $r->status_badge }}">{{ $r->status_label }}</span></td>
                                            <td data-order="{{ $r->source_label }}"><span class="badge {{ $r->source_badge }}">{{ $r->source_label }}</span></td>
                                            <td class="text-end">
                                                <div class="d-flex gap-1 justify-content-end">
                                                    @if(auth()->user()->hasPermission('incident-reports.edit'))
                                                        <a href="{{ route('admin.incident-reports.edit', $r) }}" class="btn btn-sm btn-primary">Edit</a>
                                                    @else
                                                        {{-- Employees are view-only, so they still need a way to open their own report --}}
                                                        <a href="{{ route('admin.incident-reports.show', $r) }}" class="btn btn-sm btn-outline-secondary">View</a>
                                                    @endif
                                                    @if(auth()->user()->hasPermission('incident-reports.delete'))
                                                        <form action="{{ route('admin.incident-reports.destroy', $r) }}" method="POST" class="m-0" onsubmit="return confirm('Delete this report?')">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                        </form>
                                                    @endif
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
@include('components.backend.footer')
</div>
</div>

@include('components.backend.main-js')
<script>
jQuery(function ($) {
    var $table = $('#incidentReportsTable');
    if (!$table.length || !$.fn.DataTable) { return; }

    var SOURCE_COL = 4; // 0-based index of the Source column

    var dt = $table.DataTable({
        order: [[SOURCE_COL, 'asc']],
        columnDefs: [
            { targets: SOURCE_COL, visible: false }, // hidden — shown as a group header instead
            { targets: -1, orderable: false }         // Actions column
        ],
        language: { emptyTable: 'No incident reports yet.' },
        // Insert a header row above each new source group.
        drawCallback: function () {
            var api     = this.api();
            var rows    = api.rows({ page: 'current' }).nodes();
            var colspan = api.columns(':visible').count();
            var last    = null;

            api.column(SOURCE_COL, { page: 'current' }).data().each(function (group, i) {
                var label = $('<div>').html(group).text().trim(); // strip the badge markup
                if (last !== label) {
                    $(rows).eq(i).before(
                        '<tr class="dt-group-row"><td colspan="' + colspan + '">' +
                            '<i class="fa fa-folder-open me-1"></i>' + label +
                        '</td></tr>'
                    );
                    last = label;
                }
            });
        }
    });

    // ---- Instant client-side filtering (no page reload) ----
    function val(id) { return (document.getElementById(id).value || '').trim(); }

    $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
        if (settings.nTable.id !== 'incidentReportsTable') { return true; }
        var row  = dt.row(dataIndex).node();
        var d    = row.getAttribute('data-date') || '';       // YYYY-MM-DD
        var cat  = row.getAttribute('data-category') || '';
        var st   = row.getAttribute('data-status') || '';
        var from = val('f_from'), to = val('f_to'), yr = val('f_year'), fc = val('f_cat'), fs = val('f_status');

        if (from && (!d || d < from)) { return false; }
        if (to   && (!d || d > to))   { return false; }
        if (yr   && (!d || d.substring(0, 4) !== yr)) { return false; }
        if (fc   && cat !== fc) { return false; }
        if (fs   && st  !== fs) { return false; }
        return true;
    });

    $('#f_from, #f_to, #f_year, #f_cat, #f_status').on('change keyup', function () { dt.draw(); });

    $('#f_reset').on('click', function () {
        ['f_from', 'f_to', 'f_year', 'f_cat', 'f_status'].forEach(function (id) { document.getElementById(id).value = ''; });
        dt.draw();
    });

    // Export CSV reflects the current filter selections
    var exportBase = '{{ route('admin.incident-reports.export-csv') }}';
    $('#f_export').on('click', function () {
        var q = $.param({ from_date: val('f_from'), to_date: val('f_to'), year: val('f_year'), category: val('f_cat'), status: val('f_status') });
        this.href = exportBase + (q ? '?' + q : '');
    });
});
</script>
</body>
</html>
