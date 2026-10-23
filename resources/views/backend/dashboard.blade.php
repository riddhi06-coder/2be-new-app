<!doctype html>
<html lang="en">

<head>
    @include('components.backend.head')
</head>

@include('components.backend.header')

<!--start sidebar wrapper-->
@include('components.backend.sidebar')
<!--end sidebar wrapper-->

<div class="page-body dashboard-page">

    @php
    $cesspoolTotal = \App\Models\CesspoolSystemDetails::count();
    $cesspoolSubmitted = \App\Models\CesspoolSystemDetails::where('is_draft', false)->count();
    $septicTotal = \App\Models\SepticSystemDetails::count();
    $septicSubmitted = \App\Models\SepticSystemDetails::where('is_draft', false)->count();
    $disposalTotal = \App\Models\WasteDisposalDetails::count();
    $disposalThisMonth = \App\Models\WasteDisposalDetails::whereMonth('date_of_pickup', now()->month)
    ->whereYear('date_of_pickup', now()->year)->count();

    $opsCards = [
    [
    'route' => route('cesspool-records.index'),
    'icon' => 'fa-tint', 'color' => '#0ea5e9', 'label' => 'Cesspool Inspections',
    'count' => $cesspoolTotal,
    'stats' => [
    ['label' => 'Submitted', 'value' => $cesspoolSubmitted],
    ['label' => 'Drafts', 'value' => $cesspoolTotal - $cesspoolSubmitted],
    ],
    ],
    [
    'route' => route('septic-records.index'),
    'icon' => 'fa-shower', 'color' => '#0d9488', 'label' => 'Septic Tank Inspections',
    'count' => $septicTotal,
    'stats' => [
    ['label' => 'Submitted', 'value' => $septicSubmitted],
    ['label' => 'Drafts', 'value' => $septicTotal - $septicSubmitted],
    ],
    ],
    [
    'route' => route('manage-disposal-details.index'),
    'icon' => 'fa-recycle', 'color' => '#16a34a', 'label' => 'Wastewater Pumping & Hauling',
    'count' => $disposalTotal,
    'stats' => [
    ['label' => 'This Month', 'value' => $disposalThisMonth],
    ['label' => 'All Time', 'value' => $disposalTotal],
    ],
    ],
    ];
    @endphp

    <!-- ═══ OPERATIONS ═══ -->
    <div class="container stats-wrap">
        <div class="hr-head">
            <span class="bar"></span>
            <div>
                <h5>Operations</h5>
                <p>Inspections &amp; disposal records</p>
            </div>
        </div>
        <div class="row g-3">
            @foreach($opsCards as $card)
            @php
            $hex = ltrim($card['color'], '#');
            [$r, $g, $b] = sscanf($hex, "%02x%02x%02x");
            @endphp
            <div class="col-lg-4 col-md-6">
                <a href="{{ $card['route'] }}" class="hrc"
                    style="--c: {{ $card['color'] }}; --soft: rgba({{ $r }}, {{ $g }}, {{ $b }}, 0.09);">
                    <div class="hrc-top">
                        <div class="hrc-ic"><i class="fa {{ $card['icon'] }}" aria-hidden="true"></i></div>
                        <div class="hrc-id">
                            <div class="hrc-title">{{ $card['label'] }}</div>
                            <div class="hrc-num">{{ $card['count'] }}</div>
                        </div>
                        <i class="fa fa-arrow-right hrc-arrow" aria-hidden="true"></i>
                    </div>
                    <div class="hrc-foot">
                        @foreach($card['stats'] as $i => $s)
                        <div class="hrc-chip {{ $i === 0 ? 'hot' : '' }}">
                            <div class="n">{{ $s['value'] }}</div>
                            <div class="l">{{ $s['label'] }}</div>
                        </div>
                        @endforeach
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>

    <!-- ═══ HR PORTAL MODULES ═══ -->
    @php
    $u = auth()->user();
    $hrCards = [];

    if ($u->hasPermission('employees.view')) {
    $empBase = \App\Models\User::whereHas('role', fn($q) => $q->where('slug', 'employee'));
    $empTotal = (clone $empBase)->count();
    $empActive = (clone $empBase)->where('is_active', true)->count();
    $hrCards[] = [
    'route' => route('admin.employees.index'),
    'icon' => 'fa-users', 'color' => '#2563eb', 'label' => 'Employees',
    'count' => $empTotal,
    'stats' => [
    ['label' => 'Active', 'value' => $empActive],
    ['label' => 'Inactive', 'value' => $empTotal - $empActive],
    ],
    ];
    }

    if ($u->hasPermission('document-categories.view') || $u->hasPermission('documents.view')) {
    $hrCards[] = [
    'route' => route('admin.documents.index'),
    'icon' => 'fa-folder-open', 'color' => '#0d9488', 'label' => 'Documents',
    'count' => \App\Models\Document::count(),
    'stats' => [
    ['label' => 'Sign-off', 'value' => \App\Models\Document::where('requires_acknowledgment', true)->count()],
    ['label' => 'Folders', 'value' => \App\Models\DocumentCategory::count()],
    ],
    ];
    }

    if ($u->hasPermission('announcements.view')) {
    $annTotal = \App\Models\Announcement::count();
    $annActive = \App\Models\Announcement::where('is_active', true)->count();
    $hrCards[] = [
    'route' => route('admin.announcements.index'),
    'icon' => 'fa-bullhorn', 'color' => '#f59e0b', 'label' => 'Announcements',
    'count' => $annTotal,
    'stats' => [
    ['label' => 'Active', 'value' => $annActive],
    ['label' => 'Inactive', 'value' => $annTotal - $annActive],
    ],
    ];
    }

    if ($u->hasPermission('incident-reports.view')) {
    $hrCards[] = [
    'route' => route('admin.incident-reports.index'),
    'icon' => 'fa-exclamation-triangle', 'color' => '#dc3545', 'label' => 'Incident Reports',
    'count' => \App\Models\IncidentReport::count(),
    'stats' => [
    ['label' => 'Open', 'value' => \App\Models\IncidentReport::where('status', 'open')->count()],
    ['label' => 'In Review', 'value' => \App\Models\IncidentReport::where('status', 'under-review')->count()],
    ],
    ];
    }

    if ($u->hasPermission('calendar.view')) {
    $calActive = \App\Models\CalendarEvent::where('is_active', true)->count();
    $calUpcoming = \App\Models\CalendarEvent::where('is_active', true)
    ->whereDate('start_date', '>=', now()->toDateString())->count();
    $hrCards[] = [
    'route' => route('admin.community-calendar.index'),
    'icon' => 'fa-calendar', 'color' => '#7c3aed', 'label' => 'Community Calendar',
    'count' => $calActive,
    'stats' => [
    ['label' => 'Upcoming', 'value' => $calUpcoming],
    ['label' => 'Past', 'value' => max(0, $calActive - $calUpcoming)],
    ],
    ];
    }
    @endphp

    @if(count($hrCards))
    <div class="container hr-section">

        <div class="hr-head">
            <span class="bar"></span>
            <div>
                <h5>HR Portal</h5>
                <p>Team, documents &amp; workplace records</p>
            </div>
        </div>


        <div class="row g-3">
            @foreach($hrCards as $card)
            @php
            $hex = ltrim($card['color'], '#');
            [$r, $g, $b] = sscanf($hex, "%02x%02x%02x");
            @endphp
            <div class="col-lg-4 col-md-6">
                <a href="{{ $card['route'] }}" class="hrc"
                    style="--c: {{ $card['color'] }}; --soft: rgba({{ $r }}, {{ $g }}, {{ $b }}, 0.09);">
                    <div class="hrc-top">
                        <div class="hrc-ic"><i class="fa {{ $card['icon'] }}" aria-hidden="true"></i></div>
                        <div class="hrc-id">
                            <div class="hrc-title">{{ $card['label'] }}</div>
                            <div class="hrc-num">{{ $card['count'] }}</div>
                        </div>
                        <i class="fa fa-arrow-right hrc-arrow" aria-hidden="true"></i>
                    </div>
                    <div class="hrc-foot">
                        @foreach($card['stats'] as $i => $s)
                        <div class="hrc-chip {{ $i === 0 ? 'hot' : '' }}">
                            <div class="n">{{ $s['value'] }}</div>
                            <div class="l">{{ $s['label'] }}</div>
                        </div>
                        @endforeach
                    </div>
                </a>
            </div>
            @endforeach
        </div>


    </div>
    @endif

    <!-- ═══ QUICK ACTIONS ═══ -->
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">

                <div class="qa-card">

                    <div class="qa-header text-center mb-4">
                        <h4>Quick Actions</h4>
                        <p class="text-muted mb-0">Manage waste records and reports</p>
                    </div>

                    <div class="qa-actions">

                        <a href="{{ route('frontend.log_waste_disposal') }}"
                            target="_blank"
                            class="qa-item">

                            <div class="qa-icon green">
                                ♻
                            </div>

                            <div class="qa-content">
                                <div class="qa-title">Log Waste Disposal</div>
                                <div class="qa-sub">Add a new waste collection record</div>
                            </div>

                            <div class="qa-arrow">
                                →
                            </div>

                        </a>

                        <a href="#"
                            data-bs-toggle="modal"
                            data-bs-target="#monthlyReportModal"
                            class="qa-item">

                            <div class="qa-icon blue">
                                📊
                            </div>

                            <div class="qa-content">
                                <div class="qa-title">Generate Monthly Report</div>
                                <div class="qa-sub">Download disposal summary</div>
                            </div>

                            <div class="qa-arrow">
                                →
                            </div>

                        </a>

                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- Container-fluid Ends -->
</div>
<!-- footer start-->
@include('components.backend.footer')
</div>

</div>


<div class="modal fade" id="monthlyReportModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">

            <!-- Modal Header -->
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold">
                    Generate Monthly Source Report
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal Body -->
            <form method="POST" action="{{ route('generate.monthly.report') }}" id="monthlyReportForm">
                @csrf

                <div class="modal-body">
                    <div class="row g-3">


                        @php
                        $availableMonths = DB::table('waste_disposal_details')
                        ->selectRaw('YEAR(date_of_pickup) as year, MONTH(date_of_pickup) as month')
                        ->groupBy('year', 'month')
                        ->orderBy('year', 'desc')
                        ->orderBy('month', 'desc')
                        ->get();
                        @endphp

                        <!-- Year -->
                        <div class="col-md-6">
                            <label class="form-label">Year</label>
                            <select name="year" id="yearSelect" class="form-select" required>
                                <option value="">Select Year</option>

                                @foreach($availableMonths->pluck('year')->unique() as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                                @endforeach

                            </select>
                        </div>

                        <!-- Month -->
                        <div class="col-md-6">
                            <label class="form-label">Month</label>
                            <select name="month" id="monthSelect" class="form-select" required disabled>
                                <option value="">Select Month</option>

                                @foreach($availableMonths as $item)
                                <option value="{{ $item->month }}"
                                    data-year="{{ $item->year }}">
                                    {{ \Carbon\Carbon::createFromDate($item->year, $item->month, 1)->format('F') }}
                                </option>
                                @endforeach

                            </select>
                        </div>

                        <!-- SOH DOH Registration -->
                        <div class="col-md-6">
                            <label class="form-label">SOH DOH Registration Number</label>
                            <input type="text"
                                name="soh_doh_registration"
                                class="form-control"
                                placeholder="Enter SOH DOH Registration #"
                                required>
                        </div>

                        <!-- COH Permit -->
                        <div class="col-md-6">
                            <label class="form-label">COH Permit #</label>
                            <input type="text"
                                name="coh_permit"
                                class="form-control"
                                placeholder="Enter COH Permit #"
                                required>
                        </div>

                        <!-- Signed By -->
                        <div class="col-md-6">
                            <label class="form-label">Signed By</label>
                            <input type="text"
                                name="signed_by"
                                class="form-control"
                                placeholder="Full Name"
                                required>
                        </div>

                        <!-- Title -->
                        <div class="col-md-6">
                            <label class="form-label">Title</label>
                            <input type="text"
                                name="title"
                                class="form-control"
                                placeholder="Title / Position"
                                required>
                        </div>

                        <!-- Date -->
                        <div class="col-md-6">
                            <label class="form-label">Date</label>
                            <input type="date"
                                name="signed_date"
                                class="form-control"
                                value="{{ now()->toDateString() }}"
                                required>
                        </div>

                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary" id="generateBtn">
                        Generate PDF Report
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>



@include('components.backend.main-js')

<!---- SUbmit button validations---->
<script>
    document.getElementById('monthlyReportForm').addEventListener('submit', function() {

        let btn = document.getElementById('generateBtn');

        btn.disabled = true;
        btn.innerHTML = 'Generating...';

        // Reload page after 3 seconds
        setTimeout(function() {
            window.location.reload();
        }, 3000);

    });
</script>


<!---- Dynamic month fetching as per the year---->
<script>
    document.getElementById('yearSelect').addEventListener('change', function() {

        let selectedYear = this.value;
        let monthSelect = document.getElementById('monthSelect');
        let options = monthSelect.querySelectorAll('option');

        monthSelect.value = "";
        monthSelect.disabled = true;

        options.forEach(function(option) {

            if (option.value === "") return;

            if (option.getAttribute('data-year') === selectedYear) {
                option.style.display = "block";
                monthSelect.disabled = false;
            } else {
                option.style.display = "none";
            }
        });

    });
</script>


</body>

</html>