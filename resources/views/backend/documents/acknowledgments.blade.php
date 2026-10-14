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
