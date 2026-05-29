@php
    $isOn = function ($val, $opt) {
        if (!$val) return false;
        $parts = array_map('trim', explode(',', $val));
        return in_array($opt, $parts, true);
    };
    $cb = function ($val, $opt) use ($isOn) {
        return $isOn($val, $opt)
            ? '<span class="cb cb-on">&#10003;</span>'
            : '<span class="cb"></span>';
    };

    $logoPath     = public_path('admin/assets/images/logo/logo.webp');
    $imageAbsPath = $record->image_path ? storage_path('app/public/' . $record->image_path) : null;
    $imageExists  = $imageAbsPath && file_exists($imageAbsPath);
    $videoUrl     = $record->video_path ? asset('storage/' . $record->video_path) : null;

    $dateFmt = $record->date_of_pickup
        ? \Carbon\Carbon::parse($record->date_of_pickup)->format('m/d/Y')
        : '';
@endphp
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Inspection Form for Operating Septic Systems</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      font-family: DejaVu Sans, Arial, sans-serif;
      font-size: 9.5pt;
      color: #111;
      background: #fff;
    }

    .page {
      width: 700px;
      margin: 0 auto;
      padding: 48px 52px;
      background: #fff;
    }

    /* ── LOGO ── */
    .logo-wrap { text-align: center; margin-bottom: 18px; }
    .logo-wrap img { max-height: 95px; }

    /* ── HEADER ── */
    .header {
      border-top: 3px solid #0d3a17;
      padding: 18px 0 14px;
      margin-bottom: 6px;
    }
    .header-title {
      font-size: 15pt;
      font-weight: bold;
      letter-spacing: 0.6px;
      text-transform: uppercase;
      color: #0d3a17;
    }
    .header-sub {
      font-size: 8pt;
      color: #777;
      margin-top: 5px;
      letter-spacing: 0.3px;
    }
    .header-rule {
      border-bottom: 1px solid #ddd;
      margin-bottom: 20px;
    }
    .meta-row {
      display: table;
      width: 100%;
      margin-bottom: 26px;
      font-size: 8pt;
      color: #555;
    }
    .meta-cell {
      display: table-cell;
      padding: 4px 0;
    }
    .meta-cell strong { color: #0d3a17; letter-spacing: 0.4px; text-transform: uppercase; font-size: 7.5pt; }
    .badge {
      display: inline-block;
      padding: 2px 8px;
      border-radius: 2px;
      font-size: 7.5pt;
      font-weight: bold;
      letter-spacing: 0.4px;
      text-transform: uppercase;
    }
    .badge-draft     { background: #fff4d6; color: #7a5a00; border: 1px solid #e6c97a; }
    .badge-submitted { background: #e8f3ec; color: #0d3a17; border: 1px solid #b4d4bf; }

    /* ── INSPECTION TYPE ── */
    .type-row {
      display: table;
      width: 100%;
      margin-bottom: 32px;
    }
    .type-lbl {
      display: table-cell;
      font-size: 7.5pt;
      font-weight: bold;
      text-transform: uppercase;
      letter-spacing: 0.6px;
      color: #999;
      width: 120px;
      vertical-align: middle;
    }
    .type-opts {
      display: table-cell;
      vertical-align: middle;
    }
    .cb {
      display: inline-block;
      width: 12px;
      height: 12px;
      border: 1.3px solid #555;
      vertical-align: -2px;
      margin-right: 5px;
      background: #fff;
      text-align: center;
      line-height: 10px;
      font-size: 13pt;
      font-weight: bold;
      color: #0d3a17;
      font-family: DejaVu Sans, sans-serif;
    }
    .cb-on {
      border-color: #0d3a17;
      background: #e8f3ec;
    }
    .opt {
      display: inline-block;
      margin-right: 30px;
      font-size: 9pt;
      color: #111;
    }

    /* ── FIELD ── */
    .field {
      margin-bottom: 22px;
    }
    .field-lbl {
      font-size: 7pt;
      font-weight: bold;
      text-transform: uppercase;
      letter-spacing: 0.7px;
      color: #6b7d72;
      margin-bottom: 7px;
    }
    .field-line {
      border-bottom: 1px solid #ccc;
      height: 20px;
      font-size: 9pt;
      color: #111;
      padding-bottom: 3px;
    }
    .field-line.bold {
      font-weight: bold;
      color: #111;
    }

    /* ── THREE-COL META ── */
    .three-col {
      display: table;
      width: 100%;
      margin-bottom: 30px;
    }
    .three-col-cell {
      display: table-cell;
      padding-right: 32px;
      vertical-align: bottom;
    }
    .three-col-cell:last-child { padding-right: 0; }

    /* ── TWO-COL ── */
    .two-col {
      display: table;
      width: 100%;
      margin-bottom: 22px;
    }
    .col-half {
      display: table-cell;
      width: 50%;
      padding-right: 32px;
      vertical-align: bottom;
    }
    .col-half:last-child { padding-right: 0; }

    /* ── SECTION HEADER ── */
    .section {
      margin: 34px 0 22px;
      border-top: 2px solid #0d3a17;
      padding-top: 10px;
    }
    .section-title {
      font-size: 9pt;
      font-weight: bold;
      text-transform: uppercase;
      letter-spacing: 1.4px;
      color: #0d3a17;
    }

    /* ── SUBSECTION ── */
    .subsection {
      font-size: 8pt;
      font-weight: bold;
      text-transform: uppercase;
      letter-spacing: 0.8px;
      color: #555;
      border-bottom: 1px solid #e0e0e0;
      padding-bottom: 6px;
      margin: 28px 0 20px;
    }

    /* ── QUESTION BLOCK ── */
    .qb {
      margin-bottom: 22px;
      page-break-inside: avoid;
    }
    .field, .ifield, .checklist, .three-col, .two-col, .sig-row, .type-row, .meta-row {
      page-break-inside: avoid;
    }
    .section {
      page-break-after: avoid;
    }
    .ci {
      page-break-inside: avoid;
    }
    .qlbl {
      font-size: 8.5pt;
      color: #111;
      margin-bottom: 10px;
      font-weight: bold;
    }
    .qlbl-note {
      font-size: 7.5pt;
      color: #888;
      font-weight: normal;
      margin-top: 2px;
    }
    .qopts {
      display: table;
      width: 100%;
    }
    .qopt {
      display: table-cell;
      font-size: 9pt;
      color: #111;
      vertical-align: middle;
    }

    /* ── CHECKLIST ── */
    .checklist {
      margin-top: 4px;
    }
    .ci {
      display: block;
      font-size: 9pt;
      color: #111;
      padding: 8px 0;
      border-bottom: 1px solid #f0f0f0;
    }
    .ci:last-child { border-bottom: none; }
    .ci-sub {
      display: block;
      font-size: 8.5pt;
      color: #555;
      padding: 5px 0 5px 26px;
    }

    /* ── BODY TWO-COL (Tank section) ── */
    .tank-cols {
      display: table;
      width: 100%;
    }
    .tank-left {
      display: table-cell;
      width: 50%;
      padding-right: 36px;
      vertical-align: top;
    }
    .tank-right {
      display: table-cell;
      width: 50%;
      vertical-align: top;
      border-left: 1px solid #eee;
      padding-left: 32px;
    }

    /* ── INLINE FIELD ── */
    .ifield {
      display: table;
      width: 100%;
      margin-bottom: 18px;
    }
    .ifield-lbl {
      display: table-cell;
      font-size: 8pt;
      color: #888;
      width: 165px;
      vertical-align: bottom;
      padding-bottom: 3px;
    }
    .ifield-val {
      display: table-cell;
      border-bottom: 1px solid #ccc;
      height: 20px;
      vertical-align: bottom;
      font-size: 9pt;
      color: #111;
      padding-bottom: 3px;
    }

    /* ── COMMENTS ── */
    .comments-box {
      border: 1px solid #e0e0e0;
      min-height: 56px;
      margin-top: 8px;
      border-radius: 2px;
      padding: 8px 10px;
      font-size: 9pt;
      color: #111;
    }

    /* ── MEDIA ── */
    .media-section { margin-top: 28px; }
    .media-img { max-width: 100%; max-height: 280px; border: 1px solid #ddd; padding: 4px; background: #fff; }
    .media-link { color: #111; word-break: break-all; text-decoration: underline; }
    .media-note { font-size: 8.5pt; color: #888; margin-top: 4px; }
    .media-block { margin-bottom: 16px; }

    /* ── FOOTER ── */
    .footer-rule { border-top: 1px solid #e0e0e0; margin: 36px 0 24px; }
    .sig-row {
      display: table;
      width: 100%;
      margin-bottom: 24px;
    }
    .sig-cell {
      display: table-cell;
      padding-right: 32px;
      vertical-align: bottom;
    }
    .sig-cell:last-child { padding-right: 0; }

    .disclaimer {
      font-size: 7.5pt;
      font-style: italic;
      color: #999;
      line-height: 1.7;
      padding: 14px 0;
      border-top: 1px solid #eee;
      border-bottom: 1px solid #eee;
      margin-bottom: 14px;
    }
    .legend {
      display: table;
      width: 100%;
    }
    .legend-cell {
      display: table-cell;
      font-size: 8pt;
      color: #888;
    }

    /* ══════════ PAGE 2 ══════════ */
    .page2 {
      width: 700px;
      margin: 0 auto;
      padding: 48px 52px;
      background: #fff;
      page-break-before: always;
    }

    .sketch-header {
      border-top: 3px solid #111;
      padding: 18px 0 14px;
      margin-bottom: 6px;
    }
    .sketch-header-title {
      font-size: 12pt;
      font-weight: bold;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    .sketch-header-sub {
      font-size: 8pt;
      color: #999;
      margin-top: 4px;
    }
    .sketch-rule { border-bottom: 1px solid #ddd; margin-bottom: 24px; }

    .sketch-wrap {
      width: 100%;
      height: 550px;
      background-repeat: no-repeat;
      background-position: center center;
      background-size: contain;
      border: 1px solid #c8d1cc;
    }
    .sketch-grid {
      border-collapse: collapse;
      width: 100%;
      height: 100%;
      table-layout: fixed;
    }
    .sketch-grid td {
      border: 1px solid #c8d1cc;
      height: 22px;
      background: transparent;
    }

    .knrow {
      display: table;
      width: 100%;
      margin-top: 28px;
      border-top: 1px solid #e0e0e0;
      padding-top: 22px;
    }
    .kncol-key {
      display: table-cell;
      width: 200px;
      vertical-align: top;
    }
    .kncol-notes {
      display: table-cell;
      vertical-align: top;
      padding-left: 36px;
      border-left: 1px solid #eee;
    }
    .kn-title {
      font-size: 7pt;
      font-weight: bold;
      text-transform: uppercase;
      letter-spacing: 0.6px;
      color: #999;
      margin-bottom: 12px;
    }
    .key-row {
      display: table;
      width: 100%;
      margin-bottom: 10px;
    }
    .key-name { display: table-cell; font-size: 8.5pt; color: #555; width: 90px; }
    .key-sym  { display: table-cell; font-size: 8.5pt; font-weight: bold; color: #111; }
    .key-sq {
      display: inline-block;
      width: 13px; height: 13px;
      border: 1.5px solid #111;
      vertical-align: middle;
    }
    .note-line {
      border-bottom: 1px solid #ddd;
      min-height: 28px;
      margin-bottom: 10px;
      font-size: 9pt;
      color: #111;
      padding: 4px 0;
    }
  </style>
</head>
<body>

<!-- ═══════ PAGE 1 ═══════ -->
<div class="page">

  @if(file_exists($logoPath))
    <div class="logo-wrap">
      <img src="{{ $logoPath }}" alt="Logo">
    </div>
  @endif

  <!-- HEADER -->
  <div class="header">
    <div class="header-title">Inspection Form for Operating Septic Systems</div>
    <div class="header-sub">Official Condition Report &mdash; Licensed Inspector Use Only</div>
  </div>
  <div class="header-rule"></div>

  <!-- META -->
  <div class="meta-row">
    <div class="meta-cell" style="width:34%;">
      <strong>Record ID</strong> &nbsp; #{{ $record->id }}
    </div>
    <div class="meta-cell" style="width:33%;">
      <strong>Status</strong> &nbsp;
      @if($record->is_draft)
        <span class="badge badge-draft">Draft</span>
      @else
        <span class="badge badge-submitted">Submitted</span>
      @endif
    </div>
    <div class="meta-cell" style="width:33%;">
      <strong>Generated</strong> &nbsp; {{ \Carbon\Carbon::now()->format('m/d/Y H:i') }}
    </div>
  </div>

  <!-- TYPE OF INSPECTION -->
  <div class="type-row">
    <div class="type-lbl">Type of Inspection</div>
    <div class="type-opts">
      <span class="opt">{!! $cb($record->inspection_type, 'Home Inspector') !!}Home Inspector</span>
      <span class="opt">{!! $cb($record->inspection_type, 'Realtor') !!}Realtor</span>
      <span class="opt">{!! $cb($record->inspection_type, 'Routine Maintenance') !!}Routine Maintenance</span>
    </div>
  </div>

  <!-- DATE / TIME / WEATHER -->
  <div class="three-col">
    <div class="three-col-cell" style="width:30%;">
      <div class="field-lbl">Date of Inspection</div>
      <div class="field-line">{{ $dateFmt }}</div>
    </div>
    <div class="three-col-cell" style="width:22%;">
      <div class="field-lbl">Time</div>
      <div class="field-line">{{ $record->time }}</div>
    </div>
    <div class="three-col-cell" style="width:48%;">
      <div class="field-lbl">Weather Conditions</div>
      <div class="field-line">{{ $record->weather }}</div>
    </div>
  </div>

  <!-- INSPECTOR -->
  <div class="field">
    <div class="field-lbl">Inspector Name &amp; Company</div>
    <div class="field-line bold">{{ $record->inspector_name_company }}</div>
  </div>

  <!-- SITE ADDRESS -->
  <div class="field">
    <div class="field-lbl">Site Address</div>
    <div class="field-line">{{ $record->site_address }}</div>
  </div>

  <!-- TAX MAP / SYSTEM TYPE -->
  <div class="two-col">
    <div class="col-half">
      <div class="field-lbl">Tax Map Number</div>
      <div class="field-line">{{ $record->tax_map_number }}</div>
    </div>
    <div class="col-half">
      <div class="field-lbl">Type of System (DOH Code)</div>
      <div class="field-line">{{ $record->type_of_system }}</div>
    </div>
  </div>


  <!-- ═══ SITE OBSERVATIONS ═══ -->
  <div class="section">
    <div class="section-title">Site Observations</div>
  </div>

  <!-- Property in Use -->
  <div class="qb">
    <div class="qlbl">Property in Use</div>
    <div class="qopts">
      <div class="qopt" style="width:25%;">{!! $cb($record->property_in_use, 'Yes') !!} Yes</div>
      <div class="qopt" style="width:25%;">{!! $cb($record->property_in_use, 'No') !!} No</div>
      <div class="qopt" style="width:25%;">{!! $cb($record->property_in_use, 'Full time') !!} Full Time</div>
      <div class="qopt" style="width:25%;">{!! $cb($record->property_in_use, 'Vacation Rental') !!} Vacation Rental</div>
    </div>
    <div class="qopts" style="margin-top:10px;">
      <div class="qopt" style="width:25%;">{!! $cb($record->property_in_use, 'Vacant') !!} Vacant</div>
      <div class="qopt" style="width:25%;">{!! $cb($record->property_in_use, 'Other') !!} Other</div>
      <div class="qopt" style="width:25%;">{!! $cb($record->property_in_use, 'Unknown') !!} Unknown</div>
      <div class="qopt" style="width:25%;"></div>
    </div>
  </div>

  <!-- General Site Conditions -->
  <div class="qb">
    <div class="qlbl">General Site Conditions</div>
    <div class="checklist">
      <span class="ci">
        {!! $cb($record->site_conditions, 'Grass cover/vegetation condition') !!}&ensp;Grass Cover / Vegetation Condition
        &emsp;{!! $cb($record->site_conditions, 'Cinder/rocks') !!} Cinder / Rocks
      </span>
      <span class="ci">
        {!! $cb($record->site_conditions, 'Surface Ponding') !!}&ensp;Surface Ponding
        &emsp;{!! $cb($record->site_conditions, 'System area') !!} System Area
        &emsp;{!! $cb($record->site_conditions, 'Other areas') !!} Other Areas
      </span>
      <span class="ci">
        {!! $cb($record->site_conditions, 'Protective Barriers Present') !!}&ensp;Protective Barriers Present
        &emsp;{!! $cb($record->site_conditions, 'Effective') !!} Effective
        &emsp;{!! $cb($record->site_conditions, 'Not effective') !!} Not Effective
      </span>
    </div>
  </div>

  <!-- Surface Runoff -->
  <div class="qb">
    <div class="qlbl">Surface Runoff / Gutters Directed Away from System</div>
    <div class="qopts">
      <div class="qopt" style="width:20%;">{!! $cb($record->surface_runoff, 'Yes') !!} Yes</div>
      <div class="qopt" style="width:20%;">{!! $cb($record->surface_runoff, 'No') !!} No</div>
      <div class="qopt" style="width:20%;">{!! $cb($record->surface_runoff, 'N/A') !!} N/A</div>
      <div class="qopt" style="width:40%;"></div>
    </div>
  </div>

  <!-- Malfunction -->
  <div class="qb">
    <div class="qlbl">Malfunction at Time of Inspection</div>
    <div class="qopts" style="margin-bottom:14px;">
      <div class="qopt" style="width:20%;">{!! $cb($record->malfunction, 'Yes') !!} Yes</div>
      <div class="qopt" style="width:20%;">{!! $cb($record->malfunction, 'No') !!} No</div>
      <div class="qopt" style="width:60%;"></div>
    </div>
    <div class="checklist">
      <span class="ci">{!! $cb($record->malfunction, 'Surface discharge via plumbing') !!}&ensp;<strong>Surface Discharge via Straight-Pipe or Damaged Plumbing</strong></span>
      <span class="ci-sub">
        {!! $cb($record->malfunction, 'Grey water') !!} Grey Water &emsp;
        {!! $cb($record->malfunction, 'Black water') !!} Black Water &emsp;
        {!! $cb($record->malfunction, 'Unknown') !!} Unknown
      </span>
      <span class="ci">{!! $cb($record->malfunction, 'Surface discharge in area of tank') !!}&ensp;Surface Discharge in Area of Tank</span>
      <span class="ci">{!! $cb($record->malfunction, 'Surface discharge within tile field area') !!}&ensp;Surface Discharge within Tile Field Area</span>
      <span class="ci">{!! $cb($record->malfunction, 'Surface discharge at edge of tile field') !!}&ensp;Surface Discharge at Edge of Tile Field Area</span>
      <span class="ci">{!! $cb($record->malfunction, 'Surface discharge bleed-out away from system') !!}&ensp;Surface Discharge &mdash; Bleed-Out Away from System Location</span>
      <span class="ci">{!! $cb($record->malfunction, 'Evidence of past failure') !!}&ensp;Evidence of Past Failure</span>
    </div>
  </div>


  <!-- ═══ SYSTEM EVALUATION ═══ -->
  <div class="section">
    <div class="section-title">System Evaluation</div>
  </div>

  <div class="subsection">Tank</div>

  <div class="tank-cols">

    <!-- LEFT -->
    <div class="tank-left">

      <div class="qb">
        <div class="qlbl">Accessible</div>
        <div class="qopts">
          <div class="qopt" style="width:45%;">{!! $cb($record->manhole_accessible, 'Yes') !!} Yes</div>
          <div class="qopt">{!! $cb($record->manhole_accessible, 'No') !!} No</div>
        </div>
      </div>

      <div class="qb">
        <div class="qlbl">Lid(s) Need Repair</div>
        <div class="qopts">
          <div class="qopt" style="width:45%;">{!! $cb($record->lid_needs_repair, 'Yes') !!} Yes</div>
          <div class="qopt">{!! $cb($record->lid_needs_repair, 'No') !!} No</div>
        </div>
      </div>

      <div class="qb">
        <div class="qlbl">Liquid Operating Level</div>
        <div style="margin-top:4px;">
          <div style="padding:6px 0; border-bottom:1px solid #f0f0f0;">{!! $cb($record->liquid_operating_level, 'At outlet invert') !!}&ensp;At Outlet Invert</div>
          <div style="padding:6px 0; border-bottom:1px solid #f0f0f0;">{!! $cb($record->liquid_operating_level, 'Above outlet invert') !!}&ensp;Above Outlet Invert</div>
          <div style="padding:6px 0;">{!! $cb($record->liquid_operating_level, 'Below outlet invert') !!}&ensp;Below Outlet Invert</div>
        </div>
      </div>

      <div class="ifield" style="margin-top:8px;">
        <div class="ifield-lbl">Scum Layer Thickness (in.)</div>
        <div class="ifield-val">{{ $record->scum_layer_thickness }}</div>
      </div>
      <div class="ifield">
        <div class="ifield-lbl">Sludge Layer Thickness (in.)</div>
        <div class="ifield-val">{{ $record->sludge_layer_thickness }}</div>
      </div>

      <div class="qb">
        <div class="qlbl">Tank Pumping Recommended
          <div class="qlbl-note">(Sludge + Scum &ge; 25% of tank volume)</div>
        </div>
        <div class="qopts">
          <div class="qopt" style="width:45%;">{!! $cb($record->tank_pumping_recommended, 'Yes') !!} Yes</div>
          <div class="qopt">{!! $cb($record->tank_pumping_recommended, 'No') !!} No</div>
        </div>
      </div>

      <div class="qb">
        <div class="qlbl">Tank Pumped of All Liquids &amp; Solids</div>
        <div class="qopts">
          <div class="qopt" style="width:33%;">{!! $cb($record->tank_pumped, 'Yes') !!} Yes</div>
          <div class="qopt" style="width:33%;">{!! $cb($record->tank_pumped, 'No') !!} No</div>
          <div class="qopt">{!! $cb($record->tank_pumped, 'N/A') !!} N/A</div>
        </div>
      </div>

      <div class="ifield">
        <div class="ifield-lbl">Approx. Volume Pumped (gals)</div>
        <div class="ifield-val">{{ $record->approx_volume_pumped }}</div>
      </div>

    </div>

    <!-- RIGHT -->
    <div class="tank-right">

      <div class="qb">
        <div class="qlbl">Water Flow from House into Tank</div>
        <div style="margin-top:4px;">
          <div style="padding:6px 0; border-bottom:1px solid #f0f0f0;">
            {!! $cb($record->water_stream_from_house, 'Yes') !!} Yes &ensp;&ensp;
            {!! $cb($record->water_stream_from_house, 'Trickle') !!} Trickle &ensp;&ensp;
            {!! $cb($record->water_stream_from_house, 'Steady flow') !!} Steady Flow
          </div>
          <div style="padding:6px 0;">
            {!! $cb($record->water_stream_from_house, 'No') !!} No &ensp;&ensp;
            {!! $cb($record->water_stream_from_house, 'N/A') !!} N/A
          </div>
        </div>
      </div>

      <div class="qb">
        <div class="qlbl">Water Flow from Drain Field into Tank</div>
        <div style="margin-top:4px;">
          <div style="padding:6px 0; border-bottom:1px solid #f0f0f0;">
            {!! $cb($record->water_stream_from_drain, 'Yes') !!} Yes &ensp;&ensp;
            {!! $cb($record->water_stream_from_drain, 'Trickle') !!} Trickle &ensp;&ensp;
            {!! $cb($record->water_stream_from_drain, 'Steady flow') !!} Steady Flow
          </div>
          <div style="padding:6px 0;">
            {!! $cb($record->water_stream_from_drain, 'No') !!} No &ensp;&ensp;
            {!! $cb($record->water_stream_from_drain, 'N/A') !!} N/A
          </div>
        </div>
      </div>

      <div class="qb">
        <div class="qlbl">Inlet Tee Needs Repair</div>
        <div class="qopts">
          <div class="qopt" style="width:45%;">{!! $cb($record->inlet_tee_needs_repair, 'Yes') !!} Yes</div>
          <div class="qopt">{!! $cb($record->inlet_tee_needs_repair, 'N/D') !!} N/D</div>
        </div>
      </div>

      <div class="qb">
        <div class="qlbl">Outlet Tee Needs Repair</div>
        <div class="qopts">
          <div class="qopt" style="width:45%;">{!! $cb($record->outlet_tee_needs_repair, 'Yes') !!} Yes</div>
          <div class="qopt">{!! $cb($record->outlet_tee_needs_repair, 'N/D') !!} N/D</div>
        </div>
      </div>

      <div class="ifield" style="margin-top:8px;">
        <div class="ifield-lbl">Tank Composition</div>
        <div class="ifield-val">{{ $record->tank_composition }}</div>
      </div>
      <div class="ifield">
        <div class="ifield-lbl">Approx. Tank Size (gals)</div>
        <div class="ifield-val">{{ $record->approx_tank_size }}</div>
      </div>

      <div class="qb">
        <div class="qlbl">Service Recommended</div>
        <div class="qopts">
          <div class="qopt" style="width:33%;">{!! $cb($record->service_recommended, 'Yes') !!} Yes</div>
          <div class="qopt" style="width:33%;">{!! $cb($record->service_recommended, 'No') !!} No</div>
          <div class="qopt">{!! $cb($record->service_recommended, 'N/D') !!} N/D</div>
        </div>
      </div>

    </div>
  </div><!-- /tank-cols -->

  <!-- COMMENTS -->
  <div style="margin-top:28px;">
    <div class="field-lbl">Comments</div>
    <div class="comments-box">{{ $record->comments }}</div>
  </div>

  <!-- VIDEO LINK (image is shown on page 2 grid) -->
  @if($videoUrl)
    <div class="media-section">
      <div class="field-lbl">Site Video</div>
      <div class="media-block">
        <a class="media-link" href="{{ $videoUrl }}" target="_blank" rel="noopener">{{ $videoUrl }}</a>
        <div class="media-note">Click the link above (opens in a new tab) to view the uploaded video.</div>
      </div>
    </div>
  @endif

  <!-- SIGNATURE -->
  <div class="footer-rule"></div>
  <div class="sig-row">
    <div class="sig-cell" style="width:55%;">
      <div class="field-lbl">Inspector Signature</div>
      <div class="field-line">{{ $record->inspector_signature }}</div>
    </div>
    <div class="sig-cell" style="width:45%;">
      <div class="field-lbl">Date</div>
      <div class="field-line">{{ $dateFmt }}</div>
    </div>
  </div>

  <div class="disclaimer">
    <strong>Disclaimer:</strong>&ensp;The above information indicates the condition of the septic system at the time of inspection.
    This is not a guarantee or warranty of future system performance.
  </div>

  <div class="legend">
    <div class="legend-cell" style="width:50%;"><strong>N/A</strong> &mdash; Not Applicable</div>
    <div class="legend-cell"><strong>N/D</strong> &mdash; Not Determined</div>
  </div>

</div><!-- /page -->


<!-- ═══════ PAGE 2 ═══════ -->
<div class="page2">

  <div class="sketch-header">
    <div class="sketch-header-title">Site Diagram &mdash; Septic System Layout</div>
    <div class="sketch-header-sub">
      @if($imageExists)
        Site photo uploaded by inspector &mdash; overlaid on reference grid
      @else
        Sketch System Layout if Permit Sketch Not Available &mdash; Include House, Out Buildings &amp; All Pertinent Features
      @endif
    </div>
  </div>
  <div class="sketch-rule"></div>

  <!-- GRID — always visible. Image (if any) is the background, constrained to the grid box. -->
  <div class="sketch-wrap" @if($imageExists) style="background-image: url('{{ $imageAbsPath }}');" @endif>
    <table class="sketch-grid">
      <colgroup>
        <col style="width:3.33%;"><col style="width:3.33%;"><col style="width:3.33%;">
        <col style="width:3.33%;"><col style="width:3.33%;"><col style="width:3.33%;">
        <col style="width:3.33%;"><col style="width:3.33%;"><col style="width:3.33%;">
        <col style="width:3.33%;"><col style="width:3.33%;"><col style="width:3.33%;">
        <col style="width:3.33%;"><col style="width:3.33%;"><col style="width:3.33%;">
        <col style="width:3.33%;"><col style="width:3.33%;"><col style="width:3.33%;">
        <col style="width:3.33%;"><col style="width:3.33%;"><col style="width:3.33%;">
        <col style="width:3.33%;"><col style="width:3.33%;"><col style="width:3.33%;">
        <col style="width:3.33%;"><col style="width:3.33%;"><col style="width:3.33%;">
        <col style="width:3.33%;"><col style="width:3.33%;"><col style="width:3.43%;">
      </colgroup>
      <tbody>
        @for($r=0; $r<25; $r++)
          <tr>
            @for($c=0; $c<30; $c++)<td></td>@endfor
          </tr>
        @endfor
      </tbody>
    </table>
  </div>
  @if($imageExists)
    <div class="media-note" style="margin-top:6px; text-align:right;">{{ basename($record->image_path) }}</div>
  @endif

  <!-- KEY + NOTES -->
  <div class="knrow">
    <div class="kncol-key">
      <div class="kn-title">Map Key</div>
      <div class="key-row">
        <div class="key-name">Building</div>
        <div class="key-sym"><span class="key-sq"></span></div>
      </div>
      <div class="key-row">
        <div class="key-name">Drainfield</div>
        <div class="key-sym">&mdash;&mdash; DF &mdash;&mdash;</div>
      </div>
      <div class="key-row">
        <div class="key-name">Septic Tank</div>
        <div class="key-sym">ST</div>
      </div>
      <div class="key-row">
        <div class="key-name">Well</div>
        <div class="key-sym">W</div>
      </div>
    </div>
    <div class="kncol-notes">
      <div class="kn-title">Notes</div>
      <div class="note-line">{{ $record->notes }}</div>
      <div class="note-line"></div>
      <div class="note-line"></div>
      <div class="note-line"></div>
      <div class="note-line"></div>
    </div>
  </div>

</div><!-- /page2 -->

</body>
</html>
