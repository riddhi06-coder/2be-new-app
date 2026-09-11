<!doctype html>
<html lang="en">
<head>
    @include('components.backend.head')
    <style>
        .sg-card { border: 1px solid #e8ebf1; border-radius: 14px; box-shadow: 0 8px 26px rgba(20,28,48,.05); }
        .sg-tiles { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }
        @media (max-width: 767px) { .sg-tiles { grid-template-columns: repeat(2, 1fr); } }
        .sg-tile { border-radius: 13px; padding: 18px 20px; border: 1px solid #eef0f4; }
        .sg-tile b { display: block; font-size: 28px; font-weight: 800; line-height: 1; }
        .sg-tile span { font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: .04em; color: #8a94a3; }
        .sg-tile.docs    { background: #eef2ff; } .sg-tile.docs b    { color: #0004fe; }
        .sg-tile.done    { background: #e6f6ec; } .sg-tile.done b    { color: #1a7f43; }
        .sg-tile.await   { background: #fff2ec; } .sg-tile.await b   { color: #d1600f; }
        .sg-tile.sig     { background: #f4f6fa; } .sg-tile.sig b     { color: #1a2230; }

        .sg-filters { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; margin-bottom: 18px; }
        .sg-search { position: relative; }
        .sg-search i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #b0b8c5; }
        .sg-search input { padding: 8px 14px 8px 34px; border: 1px solid #e2e6ee; border-radius: 9px; min-width: 240px; font-size: 13.5px; }

        .sg-table { width: 100%; border-collapse: separate; border-spacing: 0; }
        .sg-table thead th { font-size: 11px; text-transform: uppercase; letter-spacing: .05em; color: #9aa2b1;
            font-weight: 700; padding: 0 14px 12px; border-bottom: 1px solid #eef0f4; text-align: left; }
        .sg-table tbody td { padding: 15px 14px; border-bottom: 1px solid #f3f5f8; vertical-align: middle; }
        .sg-table tbody tr:last-child td { border-bottom: none; }
        .sg-doc { display: flex; align-items: center; gap: 12px; }
        .sg-doc__ic { flex-shrink: 0; width: 40px; height: 40px; border-radius: 10px; background: #fdecec; color: #e62029;
            display: flex; align-items: center; justify-content: center; font-size: 18px; }
        .sg-doc__t { font-weight: 600; color: #1a2230; }
        .sg-doc__m { font-size: 12px; color: #8a94a3; }
        .sg-bar { height: 8px; border-radius: 20px; background: #eef0f4; overflow: hidden; width: 160px; }
        .sg-bar__f { height: 100%; border-radius: 20px; background: #1a7f43; }
        .sg-bar__f.part { background: #f0a020; }
        .sg-count { font-size: 12.5px; color: #5a6576; font-weight: 600; margin-top: 6px; }
        .sg-pill { display: inline-flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 700; padding: 5px 12px; border-radius: 30px; }
        .sg-pill.done { background: #e6f6ec; color: #1a7f43; }
        .sg-pill.await { background: #fff2ec; color: #d1600f; }
        .sg-pill.none { background: #eef1f6; color: #8a94a3; }
        .sg-view { display: inline-flex; align-items: center; gap: 7px; font-size: 13px; font-weight: 600;
            color: #0004fe; border: 1px solid #cdd6ff; background: #f5f7ff; padding: 7px 15px; border-radius: 9px; text-decoration: none; }
        .sg-view:hover { background: #0004fe; color: #fff; border-color: #0004fe; }
        .sg-due { font-size: 12px; color: #c98a00; font-weight: 600; }
        .sg-empty { text-align: center; color: #8a94a3; padding: 40px 0; }
    </style>
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
