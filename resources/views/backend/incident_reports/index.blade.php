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
                        <a href="{{ route('admin.incident-reports.create') }}" class="btn btn-primary">+ New Report</a>
                    @endif
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="incidentReportsTable" class="display table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Ref #</th>
                                        <th>Date</th>
                                        <th>Category</th>
                                        <th>Severity</th>
                                        <th>Status</th>
                                        <th>Source</th>
                                        <th class="text-end" style="min-width:180px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reports as $r)
                                        <tr>
                                            <td>{{ $r->reference_no }}</td>
                                            <td>{{ optional($r->incident_date)->format('d M Y') }}</td>
                                            <td>{{ $r->category_label }}</td>
                                            <td><span class="badge {{ $r->severity_badge }}">{{ $r->severity_label }}</span></td>
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

    var SOURCE_COL = 5; // 0-based index of the Source column

    $table.DataTable({
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
});
</script>
</body>
</html>
