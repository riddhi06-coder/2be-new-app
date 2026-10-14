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
                <div class="col-6"><h3>Signed Documents</h3></div>
                <div class="col-6">
                    <ol class="breadcrumb justify-content-end">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa fa-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.documents.index') }}">Documents</a></li>
                        <li class="breadcrumb-item active">Signed Documents</li>
                    </ol>
                </div>
            </div>
        </div>

        {{-- Summary tiles --}}
        <div class="row">
            <div class="col-12 mb-4">
                <div class="sg-tiles">
                    <div class="sg-tile docs"><b>{{ $total }}</b><span>Require Signature</span></div>
                    <div class="sg-tile done"><b>{{ $complete }}</b><span>Fully Signed</span></div>
                    <div class="sg-tile await"><b>{{ $pending }}</b><span>Awaiting Signatures</span></div>
                    <div class="sg-tile sig"><b>{{ $totalSigned }}/{{ $totalSigners }}</b><span>Signatures Collected</span></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card sg-card">
                    <div class="card-body p-4">
                        <div class="sg-filters">
                            <div class="sg-search">
                                <i class="fa fa-search"></i>
                                <input type="text" id="sgSearch" placeholder="Search documents...">
                            </div>
                            <select id="sgStatus" class="form-select form-select-sm" style="width:auto; min-width:180px;">
                                <option value="">All statuses</option>
                                <option value="await">Awaiting signatures</option>
                                <option value="done">Fully signed</option>
                            </select>
                            <span class="text-muted small ms-auto" id="sgCount"></span>
                        </div>

                        <div class="table-responsive">
                            <table class="sg-table" id="sgTable">
                                <thead>
                                    <tr>
                                        <th>Document</th>
                                        <th>Progress</th>
                                        <th>Status</th>
                                        <th>Sign by</th>
                                        <th style="text-align:right;">Who signed</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($documents as $doc)
                                        @php
                                            $y = $doc->assignees_count; $x = $doc->acknowledgments_count;
                                            $pct = $y > 0 ? min(round($x / $y * 100), 100) : 0;
                                            $isDone = $y > 0 && $x >= $y;
                                            $state = $y === 0 ? 'none' : ($isDone ? 'done' : 'await');
                                        @endphp
                                        <tr data-title="{{ strtolower($doc->title) }}" data-state="{{ $state }}">
                                            <td>
                                                <div class="sg-doc">
                                                    <div class="sg-doc__ic"><i class="fa fa-file-pdf-o"></i></div>
                                                    <div>
                                                        <div class="sg-doc__t">{{ $doc->title }}</div>
                                                        <div class="sg-doc__m">{{ $doc->category->name ?? '—' }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="sg-bar"><div class="sg-bar__f {{ $isDone ? '' : 'part' }}" style="width: {{ $pct }}%"></div></div>
                                                <div class="sg-count">{{ $x }} of {{ $y }} signed &middot; {{ $pct }}%</div>
                                            </td>
                                            <td>
                                                @if($state === 'done')
                                                    <span class="sg-pill done"><i class="fa fa-check"></i> Complete</span>
                                                @elseif($state === 'await')
                                                    <span class="sg-pill await"><i class="fa fa-clock-o"></i> {{ $y - $x }} pending</span>
                                                @else
                                                    <span class="sg-pill none">No employees</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($doc->acknowledgment_due)
                                                    <span class="sg-due">{{ $doc->acknowledgment_due->format('M j, Y') }}</span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td style="text-align:right;">
                                                <a href="{{ route('admin.documents.acknowledgments', $doc) }}" class="sg-view">
                                                    <i class="fa fa-users"></i> View details
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5"><div class="sg-empty"><i class="fa fa-check-circle fa-2x mb-2 d-block"></i>No documents require a signature yet.<br><a href="{{ route('admin.documents.create') }}">Upload one</a> and turn on “Require read &amp; sign”.</div></td></tr>
                                    @endforelse
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
    var $rows = $('#sgTable tbody tr[data-title]');
    function apply() {
        var q = ($('#sgSearch').val() || '').toLowerCase().trim();
        var st = $('#sgStatus').val();
        var shown = 0;
        $rows.each(function () {
            var okText = !q || $(this).data('title').indexOf(q) !== -1;
            var okState = !st || $(this).data('state') === st;
            var show = okText && okState;
            $(this).toggle(show);
            if (show) { shown++; }
        });
        $('#sgCount').text(shown + ' of ' + $rows.length + ' documents');
    }
    $('#sgSearch').on('keyup', apply);
    $('#sgStatus').on('change', apply);
    apply();
});
</script>
</body>
</html>
