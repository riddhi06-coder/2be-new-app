<!doctype html>
<html lang="en">

    <head>
        @include('components.frontend.head')
        <style>
            .empn-list { list-style: none; margin: 0; padding: 0; background: #fff; border: 1px solid #e8ebf1; border-radius: 16px; overflow: hidden; box-shadow: 0 12px 36px rgba(20,28,48,.06); }
            .empn-list > li { border-bottom: 1px solid #f3f5f8; }
            .empn-list > li:last-child { border-bottom: none; }
            .empn-list > li.is-unread { background: #f5f8ff; }
            .empn-row { display: flex; gap: 14px; padding: 16px 18px; text-decoration: none; align-items: flex-start; }
            .empn-row:hover { background: #f7f8fb; }
            .empn-ic { flex-shrink: 0; width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 17px; background: #eef2ff; color: #0004fe; }
            .empn-ic--incident { background: #fdecec; color: #e62029; }
            .empn-ic--document { background: #eef2ff; color: #0004fe; }
            .empn-title { font-weight: 600; color: #1a2230; }
            .empn-msg { color: #5a6576; font-size: 13.5px; margin-top: 2px; }
            .empn-time { color: #9aa2b1; font-size: 12px; margin-top: 4px; }
            .empn-dot { flex-shrink: 0; width: 9px; height: 9px; border-radius: 50%; background: #0004fe; margin-top: 6px; }
            .empn-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 10px; }
            .empn-markall { background: #eef2ff; color: #0004fe; border: none; font-weight: 600; font-size: 13px; padding: 8px 16px; border-radius: 9px; cursor: pointer; }
            .empn-markall:hover { background: #dfe6ff; }
            .empn-empty { text-align: center; color: #9aa2b1; padding: 50px 0; background: #fff; border: 1px solid #e8ebf1; border-radius: 16px; }
        </style>
    </head>

    <body>

        @include('components.frontend.employee_header')

            <section class="pumping-log">
                <div class="container">
                    <div class="col-md-12">
                    <div class="pumping-log__content">
                        <h1 class="pumping-log__title"><span class="pumping-log__brand">My</span> Notifications</h1>
                        <p class="pumping-log__description">Updates about documents assigned to you and actions on your incident reports.</p>
                    </div>
                    </div>
                </div>
                <svg class="shape-one" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none">
                    <path class="elementor-shape-fill" d="M421.9,6.5c22.6-2.5,51.5,0.4,75.5,5.3c23.6,4.9,70.9,23.5,100.5,35.7c75.8,32.2,133.7,44.5,192.6,49.7
                    c23.6,2.1,48.7,3.5,103.4-2.5c54.7-6,106.2-25.6,106.2-25.6V0H0v30.3c0,0,72,32.6,158.4,30.5c39.2-0.7,92.8-6.7,134-22.4
                    c21.2-8.1,52.2-18.2,79.7-24.2C399.3,7.9,411.6,7.5,421.9,6.5z"></path>
                </svg>
            </section>

            <section class="doclib-wrap">
            <div class="container">
                <div class="account-breadcrumb doclib-breadcrumb">
                    <a href="{{ route('frontend.employee_dashboard') }}">Dashboard</a>
                    <i class="fa fa-angle-right"></i>
                    <span class="current">Notifications</span>
                </div>

                <div class="empn-head">
                    <span class="fw-semibold">Recent activity</span>
                    @if($notifications->whereNull('read_at')->count())
                        <form method="POST" action="{{ route('frontend.employee_notifications_read_all') }}" class="m-0">@csrf
                            <button type="submit" class="empn-markall"><i class="fa fa-check"></i> Mark all read</button>
                        </form>
                    @endif
                </div>

                @if($notifications->isEmpty())
                    <div class="empn-empty">
                        <i class="fa fa-bell-slash-o fa-2x mb-2 d-block"></i>
                        You have no notifications yet.
                    </div>
                @else
                    <ul class="empn-list">
                        @foreach($notifications as $n)
                            <li class="{{ $n->read_at ? '' : 'is-unread' }}">
                                <a href="{{ route('frontend.employee_notification_read', $n) }}" class="empn-row">
                                    <span class="empn-ic empn-ic--{{ $n->type }}"><i class="{{ $n->icon ?: 'fa fa-bell' }}"></i></span>
                                    <span class="flex-grow-1">
                                        <span class="empn-title d-block">{{ $n->title }}</span>
                                        <span class="empn-msg d-block">{{ $n->message }}</span>
                                        <span class="empn-time d-block">{{ $n->created_at->format('M j, Y g:i A') }} &middot; {{ $n->ago }}</span>
                                    </span>
                                    @unless($n->read_at)<span class="empn-dot"></span>@endunless
                                </a>
                            </li>
                        @endforeach
                    </ul>

                    @if($notifications->hasPages())
                        <div class="mt-3">{{ $notifications->links() }}</div>
                    @endif
                @endif
            </div>
            </section>

        @include('components.frontend.footer')

        @include('components.frontend.main-js')

    </body>

</html>
