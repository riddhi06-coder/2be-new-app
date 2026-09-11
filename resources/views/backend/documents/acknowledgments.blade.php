<!doctype html>
<html lang="en">
<head>
    @include('components.backend.head')
    <style>
        .so-card { border: 1px solid #e8ebf1; border-radius: 14px; box-shadow: 0 8px 26px rgba(20,28,48,.05); }
        .so-head { display: flex; align-items: center; justify-content: space-between; gap: 20px; flex-wrap: wrap; }
        .so-doc { display: flex; align-items: center; gap: 16px; min-width: 0; }
        .so-doc__ic { flex-shrink: 0; width: 54px; height: 54px; border-radius: 12px; background: #fdecec; color: #e62029;
            display: flex; align-items: center; justify-content: center; font-size: 24px; }
        .so-doc__title { font-size: 20px; font-weight: 700; color: #1a2230; margin: 0; line-height: 1.2; }
        .so-doc__meta { font-size: 12.5px; color: #8a94a3; margin-top: 3px; }
        .so-doc__meta .due { color: #c98a00; font-weight: 600; }
        .so-stats { display: flex; gap: 10px; flex-wrap: wrap; }
        .so-stat { min-width: 92px; text-align: center; border-radius: 11px; padding: 10px 14px; border: 1px solid transparent; }
        .so-stat b { display: block; font-size: 22px; font-weight: 800; line-height: 1; }
        .so-stat span { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: .04em; }
        .so-stat.signed  { background: #e6f6ec; color: #1a7f43; }
        .so-stat.pending { background: #fff2ec; color: #d1600f; }
        .so-stat.total   { background: #eef1f6; color: #5a6576; }

        .so-progress { height: 8px; border-radius: 20px; background: #eef0f4; overflow: hidden; margin-top: 20px; }
        .so-progress__bar { height: 100%; background: #1a7f43; border-radius: 20px; }
        .so-progress__lbl { font-size: 12px; color: #8a94a3; margin-top: 7px; }

        .so-table { width: 100%; border-collapse: separate; border-spacing: 0; }
        .so-table thead th { font-size: 11px; text-transform: uppercase; letter-spacing: .05em; color: #9aa2b1;
            font-weight: 700; padding: 0 14px 12px; border-bottom: 1px solid #eef0f4; text-align: left; }
        .so-table tbody td { padding: 14px; border-bottom: 1px solid #f3f5f8; vertical-align: middle; }
        .so-table tbody tr:last-child td { border-bottom: none; }
        .so-emp { display: flex; align-items: center; gap: 12px; }
        .so-avatar { flex-shrink: 0; width: 40px; height: 40px; border-radius: 50%; background: #1a7f43; color: #fff;
            display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 15px; }
        .so-avatar.is-pending { background: #c3cadb; }
        .so-emp__name { font-weight: 600; color: #1a2230; }
        .so-emp__mail { font-size: 12px; color: #8a94a3; }
        .so-pill { display: inline-flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 700; padding: 5px 12px; border-radius: 30px; }
        .so-pill.signed  { background: #e6f6ec; color: #1a7f43; }
        .so-pill.pending { background: #eef1f6; color: #8a94a3; }
        .so-when { font-weight: 600; color: #2b3240; font-size: 13.5px; }
        .so-ip { font-size: 12.5px; color: #9aa2b1; }
        .so-cert { display: inline-flex; align-items: center; gap: 7px; font-size: 13px; font-weight: 600;
            color: #1a7f43; border: 1px solid #bfe4cd; background: #f4fbf6; padding: 7px 15px; border-radius: 9px; text-decoration: none; }
        .so-cert:hover { background: #1a7f43; color: #fff; border-color: #1a7f43; }
        .so-muted { color: #c3cadb; }
        @media (max-width: 575px) { .so-doc__ic { width: 44px; height: 44px; font-size: 20px; } .so-doc__title { font-size: 17px; } }
    </style>
</head>
<body>
@include('components.backend.header')
@include('components.backend.sidebar')

<div class="page-body">
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6"><h3>Sign-Off Status</h3></div>
                <div class="col-6">
                    <ol class="breadcrumb justify-content-end">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa fa-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.documents.index') }}">Documents</a></li>
                        <li class="breadcrumb-item active">Sign-Off Status</li>
                    </ol>
                </div>
            </div>
        </div>

        @php
            $total   = $document->assignees->count();
            $signed  = $signedByUser->count();
            $pending = max($total - $signed, 0);
            $pct     = $total > 0 ? round($signed / $total * 100) : 0;
        @endphp

        <div class="row">
            <div class="col-12">
                <div class="card so-card">
                    <div class="card-body p-4">
                        <div class="so-head">
                            <div class="so-doc">
                                <div class="so-doc__ic"><i class="fa fa-file-pdf-o"></i></div>
                                <div>
                                    <h5 class="so-doc__title">{{ $document->title }}</h5>
                                    <div class="so-doc__meta">
                                        {{ $document->original_name }}
                                        @if($document->acknowledgment_due)
                                            &middot; <span class="due"><i class="fa fa-clock-o"></i> Sign by {{ $document->acknowledgment_due->format('M j, Y') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="so-stats">
                                <div class="so-stat signed"><b>{{ $signed }}</b><span>Signed</span></div>
                                <div class="so-stat pending"><b>{{ $pending }}</b><span>Pending</span></div>
                                <div class="so-stat total"><b>{{ $total }}</b><span>Assigned</span></div>
                            </div>
                        </div>

                        <div class="so-progress"><div class="so-progress__bar" style="width: {{ $pct }}%"></div></div>
                        <div class="so-progress__lbl">{{ $pct }}% acknowledged &middot; {{ $signed }} of {{ $total }} employees have signed</div>
                    </div>
                </div>

                <div class="card so-card mt-4">
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="so-table">
                                <thead>
                                    <tr>
                                        <th>Employee</th>
                                        <th>Status</th>
                                        <th>Signed as</th>
                                        <th>When</th>
                                        <th>IP</th>
                                        <th class="text-end" style="text-align:right;">Certificate</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($document->assignees as $emp)
                                        @php $ack = $signedByUser->get($emp->id); @endphp
                                        <tr>
                                            <td>
                                                <div class="so-emp">
                                                    <div class="so-avatar {{ $ack ? '' : 'is-pending' }}">{{ strtoupper(mb_substr($emp->name, 0, 1)) }}</div>
                                                    <div>
                                                        <div class="so-emp__name">{{ $emp->name }}</div>
                                                        <div class="so-emp__mail">{{ $emp->email }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if($ack)
                                                    <span class="so-pill signed"><i class="fa fa-check"></i> Signed</span>
                                                @else
                                                    <span class="so-pill pending"><i class="fa fa-clock-o"></i> Pending</span>
                                                @endif
                                            </td>
                                            <td>{{ $ack->signed_name ?? '—' }}</td>
                                            <td>
                                                @if($ack)
                                                    <span class="so-when">{{ $ack->acknowledged_at->format('M j, Y') }}</span>
                                                    <div class="so-ip">{{ $ack->acknowledged_at->format('g:i A') }}</div>
                                                @else
                                                    <span class="so-muted">—</span>
                                                @endif
                                            </td>
                                            <td><span class="so-ip">{{ $ack->ip_address ?? '—' }}</span></td>
                                            <td style="text-align:right;">
                                                @if($ack && $ack->signed_pdf_path)
                                                    <a href="{{ asset($ack->signed_pdf_path) }}" target="_blank" rel="noopener" class="so-cert">
                                                        <i class="fa fa-certificate"></i> Certificate
                                                    </a>
                                                @else
                                                    <span class="so-muted">—</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="6" class="text-center text-muted py-4">This document isn't assigned to any employees.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <a href="{{ route('admin.documents.index') }}" class="btn btn-light mt-3">&larr; Back to Documents</a>
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
</body>
</html>
