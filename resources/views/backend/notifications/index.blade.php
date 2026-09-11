<!doctype html>
<html lang="en">
<head>
    @include('components.backend.head')
    <style>
        .nfp-card { border: 1px solid #e8ebf1; border-radius: 14px; box-shadow: 0 8px 26px rgba(20,28,48,.05); }
        .nfp-list { list-style: none; margin: 0; padding: 0; }
        .nfp-list > li { border-bottom: 1px solid #f3f5f8; }
        .nfp-list > li:last-child { border-bottom: none; }
        .nfp-list > li.is-unread { background: #f5f8ff; }
        .nfp-row { display: flex; gap: 14px; padding: 16px 18px; text-decoration: none; align-items: flex-start; }
        .nfp-row:hover { background: #f7f8fb; }
        .nfp-ic { flex-shrink: 0; width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center;
            justify-content: center; font-size: 17px; background: #eef2ff; color: #0004fe; }
        .nfp-ic--incident { background: #fdecec; color: #e62029; }
        .nfp-ic--document { background: #eef2ff; color: #0004fe; }
        .nfp-ic--cesspool, .nfp-ic--septic { background: #e6f6ec; color: #1a7f43; }
        .nfp-ic--disposal { background: #fff2ec; color: #d1600f; }
        .nfp-title { font-weight: 600; color: #1a2230; }
        .nfp-msg { color: #5a6576; font-size: 13.5px; margin-top: 2px; }
        .nfp-time { color: #9aa2b1; font-size: 12px; margin-top: 4px; }
        .nfp-dot { flex-shrink: 0; width: 9px; height: 9px; border-radius: 50%; background: #0004fe; margin-top: 6px; }
        .nfp-empty { text-align: center; color: #9aa2b1; padding: 50px 0; }
    </style>
</head>
<body>
@include('components.backend.header')
@include('components.backend.sidebar')

<div class="page-body">
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6"><h3>Notifications</h3></div>
                <div class="col-6">
                    <ol class="breadcrumb justify-content-end">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa fa-home"></i></a></li>
                        <li class="breadcrumb-item active">Notifications</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card nfp-card">
                    <div class="card-body p-0">
                        <div class="d-flex justify-content-between align-items-center px-4 py-3 border-bottom">
                            <span class="fw-semibold">Employee activity</span>
                            @if($notifications->whereNull('read_at')->count())
                                <form method="POST" action="{{ route('admin.notifications.read-all') }}" class="m-0">@csrf
                                    <button type="submit" class="btn btn-sm btn-outline-primary"><i class="fa fa-check-double me-1"></i>Mark all read</button>
                                </form>
                            @endif
                        </div>

                        <ul class="nfp-list">
                            @forelse($notifications as $n)
                                <li class="{{ $n->read_at ? '' : 'is-unread' }}">
                                    <a href="{{ route('admin.notifications.read', $n) }}" class="nfp-row">
                                        <span class="nfp-ic nfp-ic--{{ $n->type }}"><i class="{{ $n->icon ?: 'fa fa-bell' }}"></i></span>
                                        <span class="flex-grow-1">
                                            <span class="nfp-title d-block">{{ $n->title }}</span>
                                            <span class="nfp-msg d-block">{{ $n->message }}</span>
                                            <span class="nfp-time d-block">{{ $n->created_at->format('M j, Y g:i A') }} &middot; {{ $n->ago }}</span>
                                        </span>
                                        @unless($n->read_at)<span class="nfp-dot"></span>@endunless
                                    </a>
                                </li>
                            @empty
                                <li><div class="nfp-empty"><i class="fa fa-bell-slash-o fa-2x mb-2 d-block"></i>No notifications yet.<br>You'll be notified when employees submit forms, sign documents, or file incident reports.</div></li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                @if($notifications->hasPages())
                    <div class="mt-3">{{ $notifications->links() }}</div>
                @endif
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
