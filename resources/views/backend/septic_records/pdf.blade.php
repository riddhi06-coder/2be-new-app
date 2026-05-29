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
  <title>Septic Inspection Report</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      font-family: DejaVu Sans, Arial, sans-serif;
      font-size: 9.5pt;
      color: #1a1a1a;
      background: #fff;
    }

    .page {
      width: 700px;
      margin: 0 auto;
      padding: 36px 44px 44px;
    }

    /* ── LOGO ── */
    .logo-wrap { text-align: center; margin-bottom: 14px; }
    .logo-wrap img { max-height: 95px; }

    /* ── HEADER ── */
    .header { border-top: 3px solid #0d3a17; padding: 14px 0 10px; }
    .header-title {
      font-size: 14pt;
      font-weight: bold;
      letter-spacing: 0.5px;
      text-transform: uppercase;
      color: #0d3a17;
    }
    .header-sub { font-size: 8pt; color: #777; margin-top: 3px; }
    .header-rule { border-bottom: 1px solid #ddd; margin-bottom: 14px; }

    /* ── META STRIP ── */
    .meta {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 22px;
      background: #f7faf8;
      border: 1px solid #e6ede9;
    }
    .meta td {
      padding: 8px 14px;
      font-size: 9pt;
      color: #444;
      vertical-align: middle;
      border-right: 1px solid #e6ede9;
    }
    .meta td:last-child { border-right: 0; }
    .meta-key {
      font-size: 7pt;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      color: #0d3a17;
      font-weight: bold;
      display: block;
      margin-bottom: 3px;
    }
    .badge {
      display: inline-block;
      padding: 2px 9px;
      border-radius: 2px;
      font-size: 7.5pt;
      font-weight: bold;
      letter-spacing: 0.4px;
      text-transform: uppercase;
    }
    .badge-draft     { background: #fff4d6; color: #7a5a00; border: 1px solid #e6c97a; }
    .badge-submitted { background: #e8f3ec; color: #0d3a17; border: 1px solid #b4d4bf; }

    /* ── FIELDS (text inputs displayed as filled lines) ── */
    .frow {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 12px;
    }
    .frow > tbody > tr > td {
      vertical-align: top;
      padding: 0 18px 0 0;
    }
    .frow > tbody > tr > td:last-child { padding-right: 0; }
    .field-lbl {
      font-size: 7pt;
      font-weight: bold;
      text-transform: uppercase;
      letter-spacing: 0.6px;
      color: #6b7d72;
      margin-bottom: 4px;
    }
    .field-val {
      border-bottom: 1px solid #d4dad6;
      min-height: 18px;
      font-size: 9.5pt;
      color: #1a1a1a;
      padding: 2px 0 4px;
      word-wrap: break-word;
    }
    .field-val.bold { font-weight: bold; }

    /* ── SECTION TITLES ── */
    .section {
      margin: 26px 0 12px;
      border-top: 2px solid #0d3a17;
      padding-top: 8px;
      page-break-after: avoid;
    }
    .section-title {
      font-size: 9.5pt;
      font-weight: bold;
      text-transform: uppercase;
      letter-spacing: 1.4px;
      color: #0d3a17;
    }
    .subsection {
      font-size: 8.5pt;
      font-weight: bold;
      text-transform: uppercase;
      letter-spacing: 1px;
      color: #6b7d72;
      border-bottom: 1px solid #e6ede9;
      padding: 4px 0 6px;
      margin: 16px 0 10px;
      page-break-after: avoid;
    }

    /* ── QUESTION BLOCK ── */
    .q {
      margin-bottom: 16px;
      page-break-inside: avoid;
    }
    .q-lbl {
      font-size: 8.5pt;
      color: #1a1a1a;
      font-weight: bold;
      margin-bottom: 5px;
    }
    .q-note {
      font-size: 7.5pt;
      color: #888;
      font-weight: normal;
      margin-top: 1px;
    }
    .q-sub {
      font-size: 7pt;
      text-transform: uppercase;
      letter-spacing: 0.6px;
      color: #6b7d72;
      font-weight: bold;
      margin: 8px 0 4px;
    }

    /* ── CHECKBOX ── */
    .cb {
      display: inline-block;
      width: 12px;
      height: 12px;
      border: 1.3px solid #555;
      vertical-align: -2px;
      background: #fff;
      text-align: center;
      line-height: 10px;
      font-size: 13pt;
      font-weight: bold;
      color: #0d3a17;
      font-family: DejaVu Sans, sans-serif;
      margin-right: 6px;
    }
    .cb-on { border-color: #0d3a17; background: #e8f3ec; }

    /* ── OPTION GRID — ONE CELL PER OPTION ── */
    .opts {
      width: 100%;
      border-collapse: collapse;
      table-layout: fixed;
    }
    .opts td {
      padding: 6px 8px 6px 0;
      font-size: 9pt;
      vertical-align: middle;
      color: #1a1a1a;
    }
    .opts tr + tr td { border-top: 1px solid #f4f6f5; }

    /* ── CHECKLIST — ONE OPTION PER ROW ── */
    .checklist { width: 100%; border-collapse: collapse; }
    .checklist td {
      padding: 7px 0;
      font-size: 9pt;
      vertical-align: middle;
      border-bottom: 1px solid #f4f6f5;
      color: #1a1a1a;
    }
    .checklist tr:last-child td { border-bottom: 0; }
    .checklist td.indent { padding-left: 24px; }

    /* ── TWO-COL TANK SECTION ── */
    .tank { width: 100%; border-collapse: collapse; }
    .tank > tbody > tr > td.tcol-left {
      width: 50%;
      vertical-align: top;
      padding-right: 22px;
      border-right: 1px solid #eee;
    }
    .tank > tbody > tr > td.tcol-right {
      width: 50%;
      vertical-align: top;
      padding-left: 22px;
    }

    /* ── COMMENTS / MEDIA ── */
    .comments-box {
      border: 1px solid #d4dad6;
      min-height: 58px;
      padding: 10px 12px;
      font-size: 9pt;
      color: #1a1a1a;
      background: #fafbfa;
      margin-top: 4px;
    }
    .media-section { margin-top: 20px; page-break-inside: avoid; }
    .media-link { color: #0d3a17; word-break: break-all; text-decoration: underline; font-size: 9pt; }
    .media-note { font-size: 8.5pt; color: #888; margin-top: 4px; }

    /* ── FOOTER ── */
    .footer-rule { border-top: 1px solid #e0e0e0; margin: 26px 0 16px; }
    .disclaimer {
      font-size: 7.5pt;
      font-style: italic;
      color: #888;
      line-height: 1.7;
      padding: 12px 0;
      border-top: 1px solid #eee;
      border-bottom: 1px solid #eee;
      margin: 16px 0 10px;
    }
    .legend {
      width: 100%;
      border-collapse: collapse;
      font-size: 8pt;
      color: #777;
    }
    .legend td { padding: 4px 0; }

    /* ══════════ PAGE 2 ══════════ */
    .page2 {
      width: 700px;
      margin: 0 auto;
      padding: 40px 44px 44px;
      page-break-before: always;
    }
    .sketch-header { border-top: 3px solid #0d3a17; padding: 14px 0 10px; }
    .sketch-header-title {
      font-size: 12pt; font-weight: bold;
      text-transform: uppercase; letter-spacing: 0.5px;
      color: #0d3a17;
    }
    .sketch-header-sub { font-size: 8pt; color: #888; margin-top: 4px; }
    .sketch-rule { border-bottom: 1px solid #ddd; margin-bottom: 16px; }

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
      width: 100%;
      border-collapse: collapse;
      margin-top: 22px;
      border-top: 1px solid #e6ede9;
      padding-top: 14px;
    }
    .knrow td { vertical-align: top; }
    .kn-key   { width: 200px; padding-right: 24px; padding-top: 14px; }
    .kn-notes { padding-left: 24px; padding-top: 14px; border-left: 1px solid #eee; }
    .kn-title {
      font-size: 7pt; font-weight: bold;
      text-transform: uppercase; letter-spacing: 0.6px;
      color: #6b7d72; margin-bottom: 10px;
    }
    .key-row { font-size: 8.5pt; color: #444; margin-bottom: 8px; }
    .key-name { display: inline-block; width: 90px; color: #666; }
    .key-sq {
      display: inline-block; width: 12px; height: 12px;
      border: 1.4px solid #0d3a17; vertical-align: middle;
    }
    .note-line {
      border-bottom: 1px solid #ddd;
      min-height: 26px;
      margin-bottom: 8px;
      font-size: 9pt;
      color: #1a1a1a;
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

  <!-- META STRIP -->
  <table class="meta">
    <tr>
      <td style="width:33%;">
        <span class="meta-key">Record ID</span>#{{ $record->id }}
      </td>
      <td style="width:34%;">
        <span class="meta-key">Status</span>
        @if($record->is_draft)
          <span class="badge badge-draft">Draft</span>
        @else
          <span class="badge badge-submitted">Submitted</span>
        @endif
      </td>
      <td style="width:33%;">
        <span class="meta-key">Generated</span>{{ \Carbon\Carbon::now()->format('m/d/Y H:i') }}
      </td>
    </tr>
  </table>

  <!-- TYPE OF INSPECTION -->
  <div class="q">
    <div class="q-lbl">Type of Inspection</div>
    <table class="opts">
      <colgroup><col style="width:34%;"><col style="width:33%;"><col style="width:33%;"></colgroup>
      <tr>
        <td>{!! $cb($record->inspection_type, 'Home Inspector') !!}Home Inspector</td>
        <td>{!! $cb($record->inspection_type, 'Realtor') !!}Realtor</td>
        <td>{!! $cb($record->inspection_type, 'Routine Maintenance') !!}Routine Maintenance</td>
      </tr>
    </table>
  </div>

  <!-- DATE / TIME / WEATHER -->
  <table class="frow">
    <tr>
      <td style="width:32%;">
        <div class="field-lbl">Date of Inspection</div>
        <div class="field-val">{{ $dateFmt }}</div>
      </td>
      <td style="width:22%;">
        <div class="field-lbl">Time</div>
        <div class="field-val">{{ $record->time }}</div>
      </td>
      <td style="width:46%;">
        <div class="field-lbl">Weather Conditions</div>
        <div class="field-val">{{ $record->weather }}</div>
      </td>
    </tr>
  </table>

  <!-- INSPECTOR -->
  <table class="frow">
    <tr><td>
      <div class="field-lbl">Inspector Name &amp; Company</div>
      <div class="field-val bold">{{ $record->inspector_name_company }}</div>
    </td></tr>
  </table>

  <!-- SITE ADDRESS -->
  <table class="frow">
    <tr><td>
      <div class="field-lbl">Site Address</div>
      <div class="field-val">{{ $record->site_address }}</div>
    </td></tr>
  </table>

  <!-- TAX MAP / SYSTEM TYPE -->
  <table class="frow">
    <tr>
      <td style="width:50%;">
        <div class="field-lbl">Tax Map Number</div>
        <div class="field-val">{{ $record->tax_map_number }}</div>
      </td>
      <td style="width:50%;">
        <div class="field-lbl">Type of System (DOH Code)</div>
        <div class="field-val">{{ $record->type_of_system }}</div>
      </td>
    </tr>
  </table>


  <!-- ═══ SITE OBSERVATIONS ═══ -->
  <div class="section">
    <div class="section-title">Site Observations</div>
  </div>

  <!-- Property in Use -->
  <div class="q">
    <div class="q-lbl">Property in Use</div>
    <table class="opts">
      <colgroup><col style="width:25%;"><col style="width:25%;"><col style="width:25%;"><col style="width:25%;"></colgroup>
      <tr>
        <td>{!! $cb($record->property_in_use, 'Yes') !!}Yes</td>
        <td>{!! $cb($record->property_in_use, 'No') !!}No</td>
        <td>{!! $cb($record->property_in_use, 'Full time') !!}Full Time</td>
        <td>{!! $cb($record->property_in_use, 'Vacation Rental') !!}Vacation Rental</td>
      </tr>
      <tr>
        <td>{!! $cb($record->property_in_use, 'Vacant') !!}Vacant</td>
        <td>{!! $cb($record->property_in_use, 'Other') !!}Other</td>
        <td>{!! $cb($record->property_in_use, 'Unknown') !!}Unknown</td>
        <td></td>
      </tr>
    </table>
  </div>

  <!-- General Site Conditions -->
  <div class="q">
    <div class="q-lbl">General Site Conditions</div>

    <div class="q-sub">Vegetation</div>
    <table class="opts">
      <colgroup><col style="width:50%;"><col style="width:50%;"></colgroup>
      <tr>
        <td>{!! $cb($record->site_conditions, 'Grass cover/vegetation condition') !!}Grass Cover / Vegetation</td>
        <td>{!! $cb($record->site_conditions, 'Cinder/rocks') !!}Cinder / Rocks</td>
      </tr>
    </table>

    <div class="q-sub">Surface Ponding</div>
    <table class="opts">
      <colgroup><col style="width:34%;"><col style="width:33%;"><col style="width:33%;"></colgroup>
      <tr>
        <td>{!! $cb($record->site_conditions, 'Surface Ponding') !!}Surface Ponding</td>
        <td>{!! $cb($record->site_conditions, 'System area') !!}System Area</td>
        <td>{!! $cb($record->site_conditions, 'Other areas') !!}Other Areas</td>
      </tr>
    </table>

    <div class="q-sub">Protective Barriers</div>
    <table class="opts">
      <colgroup><col style="width:40%;"><col style="width:30%;"><col style="width:30%;"></colgroup>
      <tr>
        <td>{!! $cb($record->site_conditions, 'Protective Barriers Present') !!}Present</td>
        <td>{!! $cb($record->site_conditions, 'Effective') !!}Effective</td>
        <td>{!! $cb($record->site_conditions, 'Not effective') !!}Not Effective</td>
      </tr>
    </table>
  </div>

  <!-- Surface Runoff -->
  <div class="q">
    <div class="q-lbl">Surface Runoff / Gutters Directed Away from System</div>
    <table class="opts">
      <colgroup><col style="width:25%;"><col style="width:25%;"><col style="width:25%;"><col style="width:25%;"></colgroup>
      <tr>
        <td>{!! $cb($record->surface_runoff, 'Yes') !!}Yes</td>
        <td>{!! $cb($record->surface_runoff, 'No') !!}No</td>
        <td>{!! $cb($record->surface_runoff, 'N/A') !!}N/A</td>
        <td></td>
      </tr>
    </table>
  </div>

  <!-- Malfunction -->
  <div class="q">
    <div class="q-lbl">Malfunction at Time of Inspection</div>
    <table class="opts">
      <colgroup><col style="width:25%;"><col style="width:25%;"><col style="width:25%;"><col style="width:25%;"></colgroup>
      <tr>
        <td>{!! $cb($record->malfunction, 'Yes') !!}Yes</td>
        <td>{!! $cb($record->malfunction, 'No') !!}No</td>
        <td></td>
        <td></td>
      </tr>
    </table>

    <div class="q-sub">Discharge Details</div>
    <table class="checklist">
      <tr><td>{!! $cb($record->malfunction, 'Surface discharge via plumbing') !!}<strong>Surface Discharge via Straight-Pipe or Damaged Plumbing</strong></td></tr>
    </table>
    <table class="opts" style="margin: 4px 0 4px 24px; width: calc(100% - 24px);">
      <colgroup><col style="width:34%;"><col style="width:33%;"><col style="width:33%;"></colgroup>
      <tr>
        <td>{!! $cb($record->malfunction, 'Grey water') !!}Grey Water</td>
        <td>{!! $cb($record->malfunction, 'Black water') !!}Black Water</td>
        <td>{!! $cb($record->malfunction, 'Unknown') !!}Unknown</td>
      </tr>
    </table>
    <table class="checklist">
      <tr><td>{!! $cb($record->malfunction, 'Surface discharge in area of tank') !!}Surface Discharge in Area of Tank</td></tr>
      <tr><td>{!! $cb($record->malfunction, 'Surface discharge within tile field area') !!}Surface Discharge within Tile Field Area</td></tr>
      <tr><td>{!! $cb($record->malfunction, 'Surface discharge at edge of tile field') !!}Surface Discharge at Edge of Tile Field Area</td></tr>
      <tr><td>{!! $cb($record->malfunction, 'Surface discharge bleed-out away from system') !!}Surface Discharge &mdash; Bleed-Out Away from System Location</td></tr>
      <tr><td>{!! $cb($record->malfunction, 'Evidence of past failure') !!}Evidence of Past Failure</td></tr>
    </table>
  </div>


  <!-- ═══ SYSTEM EVALUATION ═══ -->
  <div class="section">
    <div class="section-title">System Evaluation</div>
  </div>

  <div class="subsection">Tank</div>

  <table class="tank">
    <tr>
      <!-- LEFT COLUMN -->
      <td class="tcol-left">

        <div class="q">
          <div class="q-lbl">Accessible</div>
          <table class="opts">
            <colgroup><col style="width:50%;"><col style="width:50%;"></colgroup>
            <tr>
              <td>{!! $cb($record->manhole_accessible, 'Yes') !!}Yes</td>
              <td>{!! $cb($record->manhole_accessible, 'No') !!}No</td>
            </tr>
          </table>
        </div>

        <div class="q">
          <div class="q-lbl">Lid(s) Need Repair</div>
          <table class="opts">
            <colgroup><col style="width:50%;"><col style="width:50%;"></colgroup>
            <tr>
              <td>{!! $cb($record->lid_needs_repair, 'Yes') !!}Yes</td>
              <td>{!! $cb($record->lid_needs_repair, 'No') !!}No</td>
            </tr>
          </table>
        </div>

        <div class="q">
          <div class="q-lbl">Liquid Operating Level</div>
          <table class="checklist">
            <tr><td>{!! $cb($record->liquid_operating_level, 'At outlet invert') !!}At Outlet Invert</td></tr>
            <tr><td>{!! $cb($record->liquid_operating_level, 'Above outlet invert') !!}Above Outlet Invert</td></tr>
            <tr><td>{!! $cb($record->liquid_operating_level, 'Below outlet invert') !!}Below Outlet Invert</td></tr>
          </table>
        </div>

        <table class="frow">
          <tr>
            <td style="width:55%;">
              <div class="field-lbl">Scum Layer (in.)</div>
              <div class="field-val">{{ $record->scum_layer_thickness }}</div>
            </td>
            <td style="width:45%;">
              <div class="field-lbl">Sludge Layer (in.)</div>
              <div class="field-val">{{ $record->sludge_layer_thickness }}</div>
            </td>
          </tr>
        </table>

        <div class="q">
          <div class="q-lbl">
            Tank Pumping Recommended
            <div class="q-note">Sludge + Scum &ge; 25% of tank volume</div>
          </div>
          <table class="opts">
            <colgroup><col style="width:50%;"><col style="width:50%;"></colgroup>
            <tr>
              <td>{!! $cb($record->tank_pumping_recommended, 'Yes') !!}Yes</td>
              <td>{!! $cb($record->tank_pumping_recommended, 'No') !!}No</td>
            </tr>
          </table>
        </div>

        <div class="q">
          <div class="q-lbl">Tank Pumped of All Liquids &amp; Solids</div>
          <table class="opts">
            <colgroup><col style="width:34%;"><col style="width:33%;"><col style="width:33%;"></colgroup>
            <tr>
              <td>{!! $cb($record->tank_pumped, 'Yes') !!}Yes</td>
              <td>{!! $cb($record->tank_pumped, 'No') !!}No</td>
              <td>{!! $cb($record->tank_pumped, 'N/A') !!}N/A</td>
            </tr>
          </table>
        </div>

        <table class="frow">
          <tr><td>
            <div class="field-lbl">Approx. Volume Pumped (gals)</div>
            <div class="field-val">{{ $record->approx_volume_pumped }}</div>
          </td></tr>
        </table>

      </td>

      <!-- RIGHT COLUMN -->
      <td class="tcol-right">

        <div class="q">
          <div class="q-lbl">Water Flow from House into Tank</div>
          <table class="opts">
            <colgroup><col style="width:25%;"><col style="width:30%;"><col style="width:45%;"></colgroup>
            <tr>
              <td>{!! $cb($record->water_stream_from_house, 'Yes') !!}Yes</td>
              <td>{!! $cb($record->water_stream_from_house, 'Trickle') !!}Trickle</td>
              <td>{!! $cb($record->water_stream_from_house, 'Steady flow') !!}Steady Flow</td>
            </tr>
            <tr>
              <td>{!! $cb($record->water_stream_from_house, 'No') !!}No</td>
              <td>{!! $cb($record->water_stream_from_house, 'N/A') !!}N/A</td>
              <td></td>
            </tr>
          </table>
        </div>

        <div class="q">
          <div class="q-lbl">Water Flow from Drain Field into Tank</div>
          <table class="opts">
            <colgroup><col style="width:25%;"><col style="width:30%;"><col style="width:45%;"></colgroup>
            <tr>
              <td>{!! $cb($record->water_stream_from_drain, 'Yes') !!}Yes</td>
              <td>{!! $cb($record->water_stream_from_drain, 'Trickle') !!}Trickle</td>
              <td>{!! $cb($record->water_stream_from_drain, 'Steady flow') !!}Steady Flow</td>
            </tr>
            <tr>
              <td>{!! $cb($record->water_stream_from_drain, 'No') !!}No</td>
              <td>{!! $cb($record->water_stream_from_drain, 'N/A') !!}N/A</td>
              <td></td>
            </tr>
          </table>
        </div>

        <div class="q">
          <div class="q-lbl">Inlet Tee Needs Repair</div>
          <table class="opts">
            <colgroup><col style="width:50%;"><col style="width:50%;"></colgroup>
            <tr>
              <td>{!! $cb($record->inlet_tee_needs_repair, 'Yes') !!}Yes</td>
              <td>{!! $cb($record->inlet_tee_needs_repair, 'N/D') !!}N/D</td>
            </tr>
          </table>
        </div>

        <div class="q">
          <div class="q-lbl">Outlet Tee Needs Repair</div>
          <table class="opts">
            <colgroup><col style="width:50%;"><col style="width:50%;"></colgroup>
            <tr>
              <td>{!! $cb($record->outlet_tee_needs_repair, 'Yes') !!}Yes</td>
              <td>{!! $cb($record->outlet_tee_needs_repair, 'N/D') !!}N/D</td>
            </tr>
          </table>
        </div>

        <table class="frow">
          <tr><td>
            <div class="field-lbl">Tank Composition</div>
            <div class="field-val">{{ $record->tank_composition }}</div>
          </td></tr>
        </table>
        <table class="frow">
          <tr><td>
            <div class="field-lbl">Approx. Tank Size (gals)</div>
            <div class="field-val">{{ $record->approx_tank_size }}</div>
          </td></tr>
        </table>

        <div class="q">
          <div class="q-lbl">Service Recommended</div>
          <table class="opts">
            <colgroup><col style="width:34%;"><col style="width:33%;"><col style="width:33%;"></colgroup>
            <tr>
              <td>{!! $cb($record->service_recommended, 'Yes') !!}Yes</td>
              <td>{!! $cb($record->service_recommended, 'No') !!}No</td>
              <td>{!! $cb($record->service_recommended, 'N/D') !!}N/D</td>
            </tr>
          </table>
        </div>

      </td>
    </tr>
  </table>

  <!-- COMMENTS -->
  <div style="margin-top:20px;">
    <div class="field-lbl">Comments</div>
    <div class="comments-box">{{ $record->comments }}</div>
  </div>

  <!-- VIDEO LINK -->
  @if($videoUrl)
    <div class="media-section">
      <div class="field-lbl">Site Video</div>
      <a class="media-link" href="{{ $videoUrl }}" target="_blank" rel="noopener">{{ $videoUrl }}</a>
      <div class="media-note">Click the link above (opens in a new tab) to view the uploaded video.</div>
    </div>
  @endif

  <!-- SIGNATURE -->
  <div class="footer-rule"></div>
  <table class="frow">
    <tr>
      <td style="width:60%;">
        <div class="field-lbl">Inspector Signature</div>
        <div class="field-val">{{ $record->inspector_signature }}</div>
      </td>
      <td style="width:40%;">
        <div class="field-lbl">Date</div>
        <div class="field-val">{{ $dateFmt }}</div>
      </td>
    </tr>
  </table>

  <div class="disclaimer">
    <strong>Disclaimer:</strong>&ensp;The above information indicates the condition of the septic system at the time of inspection.
    This is not a guarantee or warranty of future system performance.
  </div>

  <table class="legend">
    <tr>
      <td style="width:50%;"><strong>N/A</strong> &mdash; Not Applicable</td>
      <td><strong>N/D</strong> &mdash; Not Determined</td>
    </tr>
  </table>

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

  <table class="knrow">
    <tr>
      <td class="kn-key">
        <div class="kn-title">Map Key</div>
        <div class="key-row"><span class="key-name">Building</span><span class="key-sq"></span></div>
        <div class="key-row"><span class="key-name">Drainfield</span>&mdash;&mdash; DF &mdash;&mdash;</div>
        <div class="key-row"><span class="key-name">Septic Tank</span><strong>ST</strong></div>
        <div class="key-row"><span class="key-name">Well</span><strong>W</strong></div>
      </td>
      <td class="kn-notes">
        <div class="kn-title">Notes</div>
        <div class="note-line">{{ $record->notes }}</div>
        <div class="note-line"></div>
        <div class="note-line"></div>
        <div class="note-line"></div>
        <div class="note-line"></div>
      </td>
    </tr>
  </table>

</div><!-- /page2 -->

</body>
</html>
