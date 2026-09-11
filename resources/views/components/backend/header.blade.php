    <style>
        .nb-wrap { position: relative; }
        .nb-bell { position: relative; cursor: pointer; width: 40px; height: 40px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center; transition: background .15s ease; }
        .nb-bell:hover { background: rgba(0,0,0,.05); }
        .nb-bell > i { font-size: 19px; color: #52526c; }
        .nb-badge { position: absolute; top: 2px; right: 2px; min-width: 17px; height: 17px; padding: 0 4px;
            background: #e62029; color: #fff; border-radius: 20px; font-size: 10px; font-weight: 700;
            display: flex; align-items: center; justify-content: center; box-shadow: 0 0 0 2px #fff; }
        .nb-dropdown { width: 360px; max-width: 92vw; padding: 0 !important; right: 0; left: auto;
            border-radius: 12px; overflow: hidden; box-shadow: 0 16px 44px rgba(20,28,48,.18); }
        .nb-head { display: flex; align-items: center; justify-content: space-between; padding: 14px 16px;
            border-bottom: 1px solid #eef0f4; font-weight: 700; color: #1a2230; font-size: 15px; }
        .nb-head__count { background: #fdecec; color: #e62029; font-size: 11px; font-weight: 700; padding: 3px 9px; border-radius: 20px; }
        .nb-list { list-style: none; margin: 0; padding: 0; max-height: 360px; overflow-y: auto; }
        .nb-list > li { border-bottom: 1px solid #f4f5f8; }
        .nb-list > li.is-unread { background: #f5f8ff; }
        .nb-list > li > a { display: flex; gap: 12px; padding: 12px 16px; text-decoration: none; align-items: flex-start; }
        .nb-list > li > a:hover { background: #f7f8fb; }
        .nb-ic { flex-shrink: 0; width: 34px; height: 34px; border-radius: 9px; display: flex; align-items: center;
            justify-content: center; font-size: 15px; background: #eef2ff; color: #0004fe; }
        .nb-ic--incident { background: #fdecec; color: #e62029; }
        .nb-ic--document { background: #eef2ff; color: #0004fe; }
        .nb-ic--cesspool, .nb-ic--septic { background: #e6f6ec; color: #1a7f43; }
        .nb-ic--disposal { background: #fff2ec; color: #d1600f; }
        .nb-body { display: flex; flex-direction: column; min-width: 0; }
        .nb-title { font-weight: 600; color: #1a2230; font-size: 13.5px; }
        .nb-msg { color: #5a6576; font-size: 12.5px; line-height: 1.4; margin-top: 1px; }
        .nb-time { color: #9aa2b1; font-size: 11px; margin-top: 3px; }
        .nb-empty { text-align: center; color: #9aa2b1; padding: 26px 10px; font-size: 13px; }
        .nb-foot { display: flex; align-items: center; justify-content: space-between; padding: 10px 16px; border-top: 1px solid #eef0f4; }
        .nb-link { background: none; border: none; color: #0004fe; font-weight: 600; font-size: 12.5px; cursor: pointer; text-decoration: none; padding: 0; }
        .nb-link:hover { text-decoration: underline; color: #0037d6; }

        /* Live toast pop-ups */
        .nb-toast-wrap { position: fixed; top: 20px; right: 20px; z-index: 12000; display: flex; flex-direction: column; gap: 12px; width: 360px; max-width: 92vw; }
        .nb-toast { display: flex; gap: 12px; align-items: flex-start; background: #fff; border: 1px solid #eef0f4;
            border-left: 4px solid #0004fe; border-radius: 12px; padding: 14px 16px; box-shadow: 0 16px 44px rgba(20,28,48,.20);
            text-decoration: none; transform: translateX(120%); transition: transform .35s cubic-bezier(.2,.8,.2,1); }
        .nb-toast.show { transform: translateX(0); }
        .nb-toast.hide { transform: translateX(120%); }
        .nb-toast--incident { border-left-color: #e62029; }
        .nb-toast--document { border-left-color: #0004fe; }
        .nb-toast--cesspool, .nb-toast--septic { border-left-color: #1a7f43; }
        .nb-toast--disposal { border-left-color: #d1600f; }
        .nb-toast__ic { flex-shrink: 0; width: 36px; height: 36px; border-radius: 9px; display: flex; align-items: center;
            justify-content: center; font-size: 15px; background: #eef2ff; color: #0004fe; }
        .nb-toast--incident .nb-toast__ic { background: #fdecec; color: #e62029; }
        .nb-toast--cesspool .nb-toast__ic, .nb-toast--septic .nb-toast__ic { background: #e6f6ec; color: #1a7f43; }
        .nb-toast--disposal .nb-toast__ic { background: #fff2ec; color: #d1600f; }
        .nb-toast__body { min-width: 0; flex: 1; }
        .nb-toast__title { font-weight: 700; color: #1a2230; font-size: 13.5px; }
        .nb-toast__msg { color: #5a6576; font-size: 12.5px; line-height: 1.4; margin-top: 2px; }
        .nb-toast__x { flex-shrink: 0; border: none; background: none; color: #b0b8c5; font-size: 16px; cursor: pointer; line-height: 1; padding: 0; }
        .nb-toast__x:hover { color: #1a2230; }
    </style>
    <!-- loader starts-->
    <div class="loader-wrapper">
      <div class="loader"> 
        <div class="loader4"></div>
      </div>
    </div>
    <!-- loader ends-->
    <!-- tap on top starts-->
    <div class="tap-top"><i data-feather="chevrons-up"></i></div>
    <!-- tap on tap ends-->
    <!-- page-wrapper Start-->
    <div class="page-wrapper compact-wrapper" id="pageWrapper">
      <!-- Page Header Start-->
      <div class="page-header">
        <div class="header-wrapper row m-0">
          <form class="form-inline search-full col" action="#" method="get">
            <div class="form-group w-100">
              <div class="Typeahead Typeahead--twitterUsers">
                <div class="u-posRelative"> 
                  <input class="demo-input Typeahead-input form-control-plaintext w-100" type="text" placeholder="Search Riho .." name="q" title="" autofocus>
                  <div class="spinner-border Typeahead-spinner" role="status"><span class="sr-only">Loading... </span></div><i class="close-search" data-feather="x"></i>
                </div>
                <div class="Typeahead-menu"> </div>
              </div>
            </div>
          </form>
          <div class="header-logo-wrapper col-auto p-0">  
            <div class="logo-wrapper"> <a href="index.html"><img class="img-fluid for-light" src="{{ asset('admin/assets/images/logo/logo_dark.png') }}" alt="logo-light"><img class="img-fluid for-dark" src="{{ asset('admin/assets/images/logo/logo_dark.png') }}" alt="logo-dark"></a></div>
            <div class="toggle-sidebar"> <i class="status_toggle middle sidebar-toggle" data-feather="align-center"></i></div>
          </div>
          <div class="left-header col-xxl-5 col-xl-6 col-lg-5 col-md-4 col-sm-3 p-0">
            <div> <a class="toggle-sidebar" href="#"> <i class="iconly-Category icli"> </i></a>
              <div class="d-flex align-items-center gap-2 ">
              <h4 class="f-w-600">Welcome, {{ Auth::user()->name }}</h4><img class="mt-0" src="{{ asset('admin/assets/images/hand.gif') }}" alt="hand-gif">
              </div>
            </div>
            <div class="welcome-content d-xl-block d-none"><span class="text-truncate col-12">Here’s what’s happening with your store today. </span></div>
          </div>
          <div class="nav-right col-xxl-7 col-xl-6 col-md-7 col-8 pull-right right-header p-0 ms-auto">
            <ul class="nav-menus"> 
              
       
              <li>
                <div class="mode"><i class="moon" data-feather="moon"> </i></div>
              </li>

              {{-- Notification bell --}}
              <li class="onhover-dropdown nb-wrap" id="nbWrap" data-last-id="{{ optional(($navNotifications ?? collect())->first())->id ?? 0 }}" data-feed="{{ route('admin.notifications.feed') }}">
                <div class="nb-bell">
                  <i class="fa fa-bell"></i>
                  <span class="nb-badge" id="nbBadge" style="{{ ($navUnread ?? 0) > 0 ? '' : 'display:none;' }}">{{ ($navUnread ?? 0) > 99 ? '99+' : ($navUnread ?? 0) }}</span>
                </div>
                <div class="onhover-show-div nb-dropdown">
                  <div class="nb-head">
                    <span>Notifications</span>
                    @if(($navUnread ?? 0) > 0)<span class="nb-head__count">{{ $navUnread }} new</span>@endif
                  </div>
                  <ul class="nb-list">
                    @forelse(($navNotifications ?? []) as $n)
                      <li class="{{ $n->read_at ? '' : 'is-unread' }}">
                        <a href="{{ route('admin.notifications.read', $n) }}">
                          <span class="nb-ic nb-ic--{{ $n->type }}"><i class="{{ $n->icon ?: 'fa fa-bell' }}"></i></span>
                          <span class="nb-body">
                            <span class="nb-title">{{ $n->title }}</span>
                            <span class="nb-msg">{{ $n->message }}</span>
                            <span class="nb-time">{{ $n->ago }}</span>
                          </span>
                        </a>
                      </li>
                    @empty
                      <li class="nb-empty"><i class="fa fa-bell-slash-o"></i> No notifications yet.</li>
                    @endforelse
                  </ul>
                  <div class="nb-foot">
                    @if(($navUnread ?? 0) > 0)
                      <form method="POST" action="{{ route('admin.notifications.read-all') }}" class="m-0">@csrf
                        <button type="submit" class="nb-link">Mark all read</button>
                      </form>
                    @else <span></span> @endif
                    <a href="{{ route('admin.notifications.index') }}" class="nb-link">View all</a>
                  </div>
                </div>
              </li>

              @auth
              <script>
              (function () {
                  var wrap = document.getElementById('nbWrap');
                  if (!wrap || wrap.dataset.nbInit) { return; }
                  wrap.dataset.nbInit = '1';

                  var feedUrl = wrap.getAttribute('data-feed');
                  var lastId  = parseInt(wrap.getAttribute('data-last-id') || '0', 10);
                  var badge   = document.getElementById('nbBadge');

                  // Subtle two-note chime via Web Audio (no sound file needed).
                  var audioCtx = null;
                  function primeAudio() {
                      try {
                          audioCtx = audioCtx || new (window.AudioContext || window.webkitAudioContext)();
                          if (audioCtx.state === 'suspended') { audioCtx.resume(); }
                      } catch (e) {}
                  }
                  ['click', 'keydown'].forEach(function (ev) {
                      document.addEventListener(ev, function unlock() { primeAudio(); document.removeEventListener(ev, unlock); });
                  });
                  function ding() {
                      try {
                          primeAudio();
                          if (!audioCtx) { return; }
                          var t = audioCtx.currentTime;
                          var g = audioCtx.createGain();
                          g.gain.setValueAtTime(0.0001, t);
                          g.gain.exponentialRampToValueAtTime(0.14, t + 0.02);
                          g.gain.exponentialRampToValueAtTime(0.0001, t + 0.45);
                          g.connect(audioCtx.destination);
                          var o = audioCtx.createOscillator();
                          o.type = 'sine';
                          o.frequency.setValueAtTime(880, t);
                          o.frequency.setValueAtTime(1174, t + 0.09);
                          o.connect(g);
                          o.start(t);
                          o.stop(t + 0.46);
                      } catch (e) {}
                  }

                  function ensureToastWrap() {
                      var w = document.getElementById('nbToastWrap');
                      if (!w) { w = document.createElement('div'); w.id = 'nbToastWrap'; w.className = 'nb-toast-wrap'; document.body.appendChild(w); }
                      return w;
                  }
                  function setBadge(n) {
                      if (!badge) { return; }
                      if (n > 0) { badge.textContent = n > 99 ? '99+' : n; badge.style.display = ''; }
                      else { badge.style.display = 'none'; }
                  }
                  function showToast(item) {
                      var w = ensureToastWrap();
                      var a = document.createElement('a');
                      a.href = item.url || '#';
                      a.className = 'nb-toast nb-toast--' + (item.type || 'general');
                      a.innerHTML =
                          '<span class="nb-toast__ic"><i class="' + (item.icon || 'fa fa-bell') + '"></i></span>' +
                          '<span class="nb-toast__body"><span class="nb-toast__title"></span><span class="nb-toast__msg"></span></span>' +
                          '<button type="button" class="nb-toast__x">&times;</button>';
                      a.querySelector('.nb-toast__title').textContent = item.title || 'Notification';
                      a.querySelector('.nb-toast__msg').textContent = item.message || '';
                      var close = function (e) { if (e) { e.preventDefault(); e.stopPropagation(); } a.classList.add('hide'); setTimeout(function () { a.remove(); }, 350); };
                      a.querySelector('.nb-toast__x').addEventListener('click', close);
                      w.appendChild(a);
                      requestAnimationFrame(function () { a.classList.add('show'); });
                      setTimeout(close, 9000);
                  }
                  function poll() {
                      fetch(feedUrl + '?after=' + lastId, { headers: { 'X-Requested-With': 'XMLHttpRequest' }, credentials: 'same-origin' })
                          .then(function (r) { return r.ok ? r.json() : null; })
                          .then(function (d) {
                              if (!d) { return; }
                              setBadge(d.unread);
                              if (d.items && d.items.length) {
                                  ding(); // one subtle chime per batch of new notifications
                                  d.items.slice().reverse().forEach(showToast); // oldest first → newest ends on top
                              }
                              if (d.latestId && d.latestId > lastId) { lastId = d.latestId; }
                          })
                          .catch(function () {});
                  }
                  setTimeout(poll, 3000);
                  setInterval(poll, 10000);
              })();
              </script>
              @endauth

              <li class="profile-nav onhover-dropdown">
                <div class="media profile-media"><img class="b-r-10" src="{{ asset('admin/assets/images/user/user.png') }}" alt="">
                  <div class="media-body d-xxl-block d-none box-col-none">
                    <div class="d-flex align-items-center gap-2"> <span>{{ Auth::user()->name }} </span><i class="middle fa fa-angle-down"> </i></div>
                    <!-- <p class="mb-0 font-roboto">{{ Auth::user()->name }}</p> -->
                  </div>
                </div>
                <ul class="profile-dropdown onhover-show-div">
                  <!-- <li><a href="user-profile.html"><i data-feather="user"></i><span>My Profile</span></a></li> -->
                  <!-- <li><a href="letter-box.html"><i data-feather="mail"></i><span>Inbox</span></a></li>
                  <li> <a href="edit-profile.html"> <i data-feather="settings"></i><span>Settings</span></a></li> -->
                  <li><a class="btn btn-pill btn-outline-primary btn-sm" href="{{ route('admin.logout') }}">Log Out</a></li>
                </ul>
              </li>
            </ul>
          </div>
          <script class="result-template" type="text/x-handlebars-template">
            <div class="ProfileCard u-cf">                        
            <div class="ProfileCard-avatar"><svg xmlns="http://www.w3.org/2000/svg') }}" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-airplay m-0"><path d="M5 17H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-1"></path><polygon points="12 15 17 21 7 21 12 15"></polygon></svg></div>
            <div class="ProfileCard-details"> 
            <div class="ProfileCard-realName"></div>
            </div> 
            </div>
          </script>
          <script class="empty-template" type="text/x-handlebars-template"><div class="EmptyMessage">Your search turned up 0 results. This most likely means the backend is down, yikes!</div></script>
        </div>
      </div>
      <!-- Page Header Ends  