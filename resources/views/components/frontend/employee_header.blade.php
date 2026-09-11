        <style>
            .emp-nb { position: relative; margin-right: 6px; }
            .emp-nb__bell { position: relative; display: inline-flex; align-items: center; justify-content: center;
                width: 42px; height: 42px; border-radius: 50%; color: #1a2230; text-decoration: none; transition: background .15s ease; }
            .emp-nb__bell:hover { background: rgba(0,0,0,.05); color: #0004fe; }
            .emp-nb__bell > i { font-size: 19px; }
            .emp-nb__badge { position: absolute; top: 4px; right: 4px; min-width: 17px; height: 17px; padding: 0 4px;
                background: #e62029; color: #fff; border-radius: 20px; font-size: 10px; font-weight: 700;
                display: flex; align-items: center; justify-content: center; box-shadow: 0 0 0 2px #fff; }
            .emp-nb__menu { width: 350px; max-width: 92vw; padding: 0; border: 1px solid #eef0f4; border-radius: 12px;
                box-shadow: 0 16px 44px rgba(20,28,48,.18); overflow: hidden; }
            .emp-nb__head { display: flex; align-items: center; justify-content: space-between; padding: 13px 16px;
                border-bottom: 1px solid #eef0f4; font-weight: 700; color: #1a2230; font-size: 15px; }
            .emp-nb__count { background: #fdecec; color: #e62029; font-size: 11px; font-weight: 700; padding: 3px 9px; border-radius: 20px; }
            .emp-nb__list { list-style: none; margin: 0; padding: 0; max-height: 340px; overflow-y: auto; }
            .emp-nb__list > li { border-bottom: 1px solid #f4f5f8; }
            .emp-nb__list > li.is-unread { background: #f5f8ff; }
            .emp-nb__list > li > a { display: flex; gap: 11px; padding: 12px 15px; text-decoration: none; align-items: flex-start; }
            .emp-nb__list > li > a:hover { background: #f7f8fb; }
            .emp-nb__ic { flex-shrink: 0; width: 34px; height: 34px; border-radius: 9px; display: flex; align-items: center;
                justify-content: center; font-size: 15px; background: #eef2ff; color: #0004fe; }
            .emp-nb__ic--incident { background: #fdecec; color: #e62029; }
            .emp-nb__ic--document { background: #eef2ff; color: #0004fe; }
            .emp-nb__body { display: flex; flex-direction: column; min-width: 0; }
            .emp-nb__title { font-weight: 600; color: #1a2230; font-size: 13.5px; }
            .emp-nb__msg { color: #5a6576; font-size: 12.5px; line-height: 1.4; margin-top: 1px; }
            .emp-nb__time { color: #9aa2b1; font-size: 11px; margin-top: 3px; }
            .emp-nb__empty { text-align: center; color: #9aa2b1; padding: 26px 10px; font-size: 13px; }
            .emp-nb__foot { display: flex; align-items: center; justify-content: space-between; padding: 10px 16px; border-top: 1px solid #eef0f4; }
            .emp-nb__link { background: none; border: none; color: #0004fe; font-weight: 600; font-size: 12.5px; cursor: pointer; text-decoration: none; padding: 0; }
            .emp-nb__link:hover { text-decoration: underline; }

            .emp-toast-wrap { position: fixed; top: 18px; right: 18px; z-index: 12000; display: flex; flex-direction: column; gap: 12px; width: 350px; max-width: 92vw; }
            .emp-toast { display: flex; gap: 12px; align-items: flex-start; background: #fff; border: 1px solid #eef0f4;
                border-left: 4px solid #0004fe; border-radius: 12px; padding: 14px 16px; box-shadow: 0 16px 44px rgba(20,28,48,.20);
                text-decoration: none; transform: translateX(120%); transition: transform .35s cubic-bezier(.2,.8,.2,1); }
            .emp-toast.show { transform: translateX(0); }
            .emp-toast.hide { transform: translateX(120%); }
            .emp-toast--incident { border-left-color: #e62029; }
            .emp-toast__ic { flex-shrink: 0; width: 36px; height: 36px; border-radius: 9px; display: flex; align-items: center;
                justify-content: center; font-size: 15px; background: #eef2ff; color: #0004fe; }
            .emp-toast--incident .emp-toast__ic { background: #fdecec; color: #e62029; }
            .emp-toast__body { min-width: 0; flex: 1; }
            .emp-toast__title { font-weight: 700; color: #1a2230; font-size: 13.5px; }
            .emp-toast__msg { color: #5a6576; font-size: 12.5px; line-height: 1.4; margin-top: 2px; }
            .emp-toast__x { flex-shrink: 0; border: none; background: none; color: #b0b8c5; font-size: 16px; cursor: pointer; line-height: 1; padding: 0; }
        </style>

        <section class="navigation">
            <div class="container-fluid">
                <nav class="navbar navbar-expand-lg">
                <!-- Logo -->
                <div class="logo">
                    <a href="{{ route('frontend.employee_portal') }}">
                        <img src="{{ asset('frontend/assets/images/logo.webp') }}" class="img-responsive mb-2" alt="2BE Pumping Log">
                    </a>
                </div>
                <!-- Navigation Menu -->
                <div class="collapse navbar-collapse nav-menu" id="mainNavbar">
                    <ul class="navbar-nav mx-auto">
                    <!-- Home -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('frontend.employee_portal') }}"> HOME </a>
                    </li>
                    @auth
                    <!-- Dashboard (logged-in only) -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('frontend.employee_dashboard') }}"> DASHBOARD </a>
                    </li>
                    @endauth

                    <!-- Documents -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('frontend.employee_documents') }}"> DOCUMENT LIBRARY </a>
                    </li>
                 
                    @auth
                    <!-- Incident Report (logged-in only) -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('frontend.employee_incident_report') }}"> INCIDENT REPORT </a>
                    </li>
                    @endauth


                    <!-- Announcements -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('frontend.employee_announcements') }}"> ANNOUNCEMENTS </a>
                    </li>
                  
                    <!-- Community Calendar -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('frontend.employee_calendar') }}"> COMMUNITY CALENDAR </a>
                    </li>
                    </ul>
                </div>
                <!-- Right Side -->
                <div class="header-right">
                    @auth
                    <!-- Notification bell -->
                    <div class="emp-nb dropdown" id="empNbWrap"
                         data-last-id="{{ optional(($empNotifications ?? collect())->first())->id ?? 0 }}"
                         data-feed="{{ route('frontend.employee_notifications_feed') }}">
                        <a href="#" class="emp-nb__bell" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-bell"></i>
                            <span class="emp-nb__badge" id="empNbBadge" style="{{ ($empUnread ?? 0) > 0 ? '' : 'display:none;' }}">{{ ($empUnread ?? 0) > 99 ? '99+' : ($empUnread ?? 0) }}</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end emp-nb__menu">
                            <div class="emp-nb__head">
                                <span>Notifications</span>
                                @if(($empUnread ?? 0) > 0)<span class="emp-nb__count">{{ $empUnread }} new</span>@endif
                            </div>
                            <ul class="emp-nb__list">
                                @forelse(($empNotifications ?? []) as $n)
                                    <li class="{{ $n->read_at ? '' : 'is-unread' }}">
                                        <a href="{{ route('frontend.employee_notification_read', $n) }}">
                                            <span class="emp-nb__ic emp-nb__ic--{{ $n->type }}"><i class="{{ $n->icon ?: 'fa fa-bell' }}"></i></span>
                                            <span class="emp-nb__body">
                                                <span class="emp-nb__title">{{ $n->title }}</span>
                                                <span class="emp-nb__msg">{{ $n->message }}</span>
                                                <span class="emp-nb__time">{{ $n->ago }}</span>
                                            </span>
                                        </a>
                                    </li>
                                @empty
                                    <li class="emp-nb__empty"><i class="fa fa-bell-slash-o"></i> No notifications yet.</li>
                                @endforelse
                            </ul>
                            <div class="emp-nb__foot">
                                @if(($empUnread ?? 0) > 0)
                                    <form method="POST" action="{{ route('frontend.employee_notifications_read_all') }}" class="m-0">@csrf
                                        <button type="submit" class="emp-nb__link">Mark all read</button>
                                    </form>
                                @else <span></span> @endif
                                <a href="{{ route('frontend.employee_notifications') }}" class="emp-nb__link">View all</a>
                            </div>
                        </div>
                    </div>
                    <!-- Logged-in: account dropdown -->
                    <div class="employee-dropdown dropdown">
                    <a
                        href="#"
                        class="employee-link dropdown-toggle"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                    >
                        <span class="user-icon">
                        @if(auth()->user()->avatar_url)
                            <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}">
                        @else
                            <i class="fa fa-user"> </i>
                        @endif
                        </span>
                        <span class="employee-text">
                        <small> Welcome, </small>
                        <strong> {{ auth()->user()->name }} </strong>
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                        <a class="dropdown-item" href="{{ route('frontend.employee_dashboard') }}">
                            <i class="fa fa-th-large"></i>
                            My Dashboard
                        </a>
                        </li>
                        <li>
                        <a class="dropdown-item" href="{{ route('frontend.employee_dashboard') }}#profile">
                            <i class="fa fa-user"></i>
                            My Profile
                        </a>
                        </li>
                        <li>
                        <hr class="dropdown-divider" />
                        </li>
                        <li>
                        <a class="dropdown-item" href="{{ route('frontend.employee_logout') }}">
                            <i class="fa fa-sign-out"></i>
                            Logout
                        </a>
                        </li>
                    </ul>
                    </div>
                    @else
                    <!-- Logged-out: login button -->
                    <a href="{{ route('frontend.employee_login') }}" class="employee-link employee-login-btn">
                        <span class="user-icon"><i class="fa fa-sign-in"></i></span>
                        <span class="employee-text"><strong>Employee Login</strong></span>
                    </a>
                    @endauth
                    <!-- Mobile Toggle -->
                    <button
                    class="navbar-toggler"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#mainNavbar"
                    aria-controls="mainNavbar"
                    aria-expanded="false"
                    aria-label="Toggle navigation"
                    >
                    <i class="fa fa-bars"></i>
                    </button>
                </div>
                </nav>
            </div>
        </section>

        @auth
        <script>
        (function () {
            var wrap = document.getElementById('empNbWrap');
            if (!wrap || wrap.dataset.nbInit) { return; }
            wrap.dataset.nbInit = '1';

            var feedUrl = wrap.getAttribute('data-feed');
            var lastId  = parseInt(wrap.getAttribute('data-last-id') || '0', 10);
            var badge   = document.getElementById('empNbBadge');
            var audioCtx = null;

            function primeAudio() { try { audioCtx = audioCtx || new (window.AudioContext || window.webkitAudioContext)(); if (audioCtx.state === 'suspended') { audioCtx.resume(); } } catch (e) {} }
            ['click', 'keydown'].forEach(function (ev) { document.addEventListener(ev, function u() { primeAudio(); document.removeEventListener(ev, u); }); });
            function ding() {
                try {
                    primeAudio(); if (!audioCtx) { return; }
                    var t = audioCtx.currentTime, g = audioCtx.createGain();
                    g.gain.setValueAtTime(0.0001, t); g.gain.exponentialRampToValueAtTime(0.14, t + 0.02); g.gain.exponentialRampToValueAtTime(0.0001, t + 0.45);
                    g.connect(audioCtx.destination);
                    var o = audioCtx.createOscillator(); o.type = 'sine';
                    o.frequency.setValueAtTime(880, t); o.frequency.setValueAtTime(1174, t + 0.09);
                    o.connect(g); o.start(t); o.stop(t + 0.46);
                } catch (e) {}
            }
            function wrapEl() { var w = document.getElementById('empToastWrap'); if (!w) { w = document.createElement('div'); w.id = 'empToastWrap'; w.className = 'emp-toast-wrap'; document.body.appendChild(w); } return w; }
            function setBadge(n) { if (!badge) { return; } if (n > 0) { badge.textContent = n > 99 ? '99+' : n; badge.style.display = ''; } else { badge.style.display = 'none'; } }
            function toast(item) {
                var w = wrapEl(), a = document.createElement('a');
                a.href = item.url || '#'; a.className = 'emp-toast emp-toast--' + (item.type || 'general');
                a.innerHTML = '<span class="emp-toast__ic"><i class="' + (item.icon || 'fa fa-bell') + '"></i></span>' +
                    '<span class="emp-toast__body"><span class="emp-toast__title"></span><span class="emp-toast__msg"></span></span>' +
                    '<button type="button" class="emp-toast__x">&times;</button>';
                a.querySelector('.emp-toast__title').textContent = item.title || 'Notification';
                a.querySelector('.emp-toast__msg').textContent = item.message || '';
                var close = function (e) { if (e) { e.preventDefault(); e.stopPropagation(); } a.classList.add('hide'); setTimeout(function () { a.remove(); }, 350); };
                a.querySelector('.emp-toast__x').addEventListener('click', close);
                w.appendChild(a); requestAnimationFrame(function () { a.classList.add('show'); }); setTimeout(close, 9000);
            }
            function poll() {
                fetch(feedUrl + '?after=' + lastId, { headers: { 'X-Requested-With': 'XMLHttpRequest' }, credentials: 'same-origin' })
                    .then(function (r) { return r.ok ? r.json() : null; })
                    .then(function (d) {
                        if (!d) { return; }
                        setBadge(d.unread);
                        if (d.items && d.items.length) { ding(); d.items.slice().reverse().forEach(toast); }
                        if (d.latestId && d.latestId > lastId) { lastId = d.latestId; }
                    }).catch(function () {});
            }
            setTimeout(poll, 3000); setInterval(poll, 10000);
        })();
        </script>
        @endauth