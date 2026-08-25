<!doctype html>
<html lang="en">
<head>
    @include('components.backend.head')
</head>
<body>
@include('components.backend.header')
@include('components.backend.sidebar')

@php
    $eventCls = [
        'created'  => 'ev-created',
        'updated'  => 'ev-updated',
        'deleted'  => 'ev-deleted',
        'restored' => 'ev-restored',
        'login'    => 'ev-login',
        'logout'   => 'ev-logout',
    ];
@endphp

<div class="page-body">
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6"><h3>Activity Log</h3></div>
                <div class="col-6">
                    <ol class="breadcrumb justify-content-end">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa fa-home"></i></a></li>
                        <li class="breadcrumb-item active">Activity Log</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        {{-- Filters --}}
                        <form method="GET" action="{{ route('admin.activity-logs.index') }}" class="actlog-filterbar">
                            <div class="row g-3">
                                <div class="col-md-2 col-sm-6">
                                    <label class="actlog-lbl">Search</label>
                                    <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Search...">
                                </div>
                                <div class="col-md-2 col-sm-6">
                                    <label class="actlog-lbl">User</label>
                                    <select name="user_id" class="form-control">
                                        <option value="">All Users</option>
                                        @foreach($users as $u)
                                            <option value="{{ $u->id }}" {{ (string) request('user_id') === (string) $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 col-sm-6">
                                    <label class="actlog-lbl">Module</label>
                                    <select name="module" class="form-control">
                                        <option value="">All Modules</option>
                                        @foreach($modules as $m)
                                            <option value="{{ $m }}" {{ request('module') === $m ? 'selected' : '' }}>{{ $m }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 col-sm-6">
                                    <label class="actlog-lbl">Event</label>
                                    <select name="event" class="form-control">
                                        <option value="">All Events</option>
                                        @foreach($events as $e)
                                            <option value="{{ $e }}" {{ request('event') === $e ? 'selected' : '' }}>{{ ucfirst($e) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <label class="actlog-lbl">Date Range</label>
                                    <div class="d-flex gap-2">
                                        <input type="date" name="from" value="{{ request('from') }}" class="form-control" title="From">
                                        <input type="date" name="to" value="{{ request('to') }}" class="form-control" title="To">
                                    </div>
                                </div>
                            </div>
                            <div class="actlog-filterbar__actions">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-outline-danger">Reset</a>
                            </div>
                        </form>

                        {{-- Table --}}
                        <div class="table-responsive actlog-table-wrap">
                            <table class="table table-hover align-middle actlog-table">
                                <thead>
                                    <tr>
                                        <th style="min-width:170px;">Date &amp; Time</th>
                                        <th>User</th>
                                        <th>Event</th>
                                        <th>Module</th>
                                        <th>Description</th>
                                        <th class="text-end">Details</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($logs as $log)
                                        @php $cls = $eventCls[$log->event] ?? 'ev-default'; $who = $log->user->name ?? $log->user_name ?? 'System'; @endphp
                                        <tr>
                                            <td>{{ $log->created_at->format('d M Y, h:i A') }}</td>
                                            <td>{{ $who }}</td>
                                            <td><span class="actlog-badge {{ $cls }}">{{ ucfirst($log->event) }}</span></td>
                                            <td>{{ $log->module ?: '—' }}</td>
                                            <td>{{ $log->description }}</td>
                                            <td class="text-end">
                                                <button type="button" class="btn btn-sm btn-outline-primary actlog-view"
                                                        data-when="{{ $log->created_at->format('d M Y, h:i:s A') }}"
                                                        data-user="{{ $who }}"
                                                        data-event="{{ ucfirst($log->event) }}"
                                                        data-eventcls="{{ $cls }}"
                                                        data-module="{{ $log->module ?: '—' }}"
                                                        data-desc="{{ $log->description }}"
                                                        data-changed="{{ !empty($log->properties['changed']) ? implode(', ', $log->properties['changed']) : '' }}"
                                                        data-ip="{{ $log->ip_address ?: '—' }}"
                                                        data-url="{{ $log->method ? $log->method.' /'.$log->url : ($log->url ?: '—') }}">
                                                    View
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="6" class="text-center text-muted py-4">No activity recorded yet.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between align-items-center flex-wrap mt-3">
                            <small class="text-muted">Showing {{ $logs->firstItem() ?? 0 }}–{{ $logs->lastItem() ?? 0 }} of {{ $logs->total() }} entries</small>
                            @if($logs->hasPages())
                                <div>{{ $logs->links('pagination::bootstrap-5') }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Details modal --}}
<div class="modal fade" id="actlogModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Activity Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <dl class="actlog-detail">
                    <dt>Date &amp; Time</dt><dd id="d-when"></dd>
                    <dt>User</dt><dd id="d-user"></dd>
                    <dt>Event</dt><dd><span id="d-event" class="actlog-badge"></span></dd>
                    <dt>Module</dt><dd id="d-module"></dd>
                    <dt>Description</dt><dd id="d-desc"></dd>
                    <dt id="d-changed-lbl" class="d-none">Changed Fields</dt><dd id="d-changed" class="d-none"></dd>
                    <dt>IP Address</dt><dd id="d-ip"></dd>
                    <dt>Request</dt><dd id="d-url"></dd>
                </dl>
            </div>
        </div>
    </div>
</div>

@include('components.backend.footer')
</div>
</div>

@include('components.backend.main-js')
<script>
    document.querySelectorAll('.actlog-view').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var d = this.dataset;
            document.getElementById('d-when').textContent   = d.when;
            document.getElementById('d-user').textContent   = d.user;
            document.getElementById('d-module').textContent = d.module;
            document.getElementById('d-desc').textContent   = d.desc;
            document.getElementById('d-ip').textContent     = d.ip;
            document.getElementById('d-url').textContent    = d.url;

            var ev = document.getElementById('d-event');
            ev.textContent = d.event;
            ev.className = 'actlog-badge ' + (d.eventcls || '');

            var changed = (d.changed || '').trim();
            document.getElementById('d-changed').textContent = changed;
            document.getElementById('d-changed').classList.toggle('d-none', !changed);
            document.getElementById('d-changed-lbl').classList.toggle('d-none', !changed);

            if (window.bootstrap && bootstrap.Modal) {
                bootstrap.Modal.getOrCreateInstance(document.getElementById('actlogModal')).show();
            }
        });
    });
</script>
</body>
</html>
