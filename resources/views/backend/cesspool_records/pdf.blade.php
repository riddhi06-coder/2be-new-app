@php
    $isOn = function ($val, $opt) {
        if (!$val) return false;
        $parts = array_map('trim', explode(',', $val));
        return in_array($opt, $parts, true);
    };
    // Emits an inline-table: [checkbox][label] — guaranteed to share one baseline
    $opt = function ($val, $opt, $label) use ($isOn) {
        $box = $isOn($val, $opt)
            ? '<span class="cb cb-on">&#10003;</span>'
            : '<span class="cb"></span>';
        return '<table class="opt"><tr>'
             . '<td class="opt-cell">' . $box . '</td>'
             . '<td class="opt-cell opt-lbl">' . $label . '</td>'
             . '</tr></table>';
    };
    // Legacy alias so any leftover {!! $cb(...) !!} call still renders
    $cb = function ($val, $option) use ($isOn) {
        return $isOn($val, $option)
            ? '<span class="cb cb-on">&#10003;</span>'
            : '<span class="cb"></span>';
    };

    $logoPath     = public_path('admin/assets/images/logo/logo.webp');
    $imageAbsPath = $record->image_path ? storage_path('app/public/' . $record->image_path) : null;
    $imageExists  = $imageAbsPath && file_exists($imageAbsPath);
    $videoUrl     = $record->video_path ? asset($record->video_path) : null;

    $inspDate = $record->date_of_pickup
        ? \Carbon\Carbon::parse($record->date_of_pickup)->format('m/d/Y')
        : '';
    $sigDate  = $record->date
        ? \Carbon\Carbon::parse($record->date)->format('m/d/Y')
        : '';
@endphp
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Cesspool Inspection Report</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      font-family: DejaVu Sans, Arial, sans-serif;
      font-size: 10pt;
      color: #1a1a1a;
      background: #fff;
      line-height: 1.3;
    }

    .page {
      width: 700px;
      margin: 0 auto;
      padding: 22px 40px 22px;
    }

    /* ── LOGO ── */
    .logo-wrap { text-align: center; margin-bottom: 8px; }
    .logo-wrap img { max-height: 70px; }

    /* ── HEADER ── */
    .header { border-top: 3px solid #0d3a17; padding: 10px 0 6px; }
    .header-title {
      font-size: 14pt;
      font-weight: bold;
      letter-spacing: 0.5px;
      text-transform: uppercase;
      color: #0d3a17;
    }
    .header-sub { font-size: 8pt; color: #777; margin-top: 2px; }
    .header-rule { border-bottom: 1px solid #ddd; margin-bottom: 10px; }

    /* ── META STRIP ── */
    .meta {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 10px;
      background: #f7faf8;
      border: 1px solid #e6ede9;
    }
    .meta td {
      padding: 6px 12px;
      font-size: 9.5pt;
      color: #1a1a1a;
      vertical-align: middle;
      border-right: 1px solid #e6ede9;
    }
    .meta td:last-child { border-right: 0; }
    .meta-key {
      font-size: 8pt;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      color: #0d3a17;
      font-weight: bold;
      display: block;
      margin-bottom: 4px;
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
      margin-bottom: 6px;
      page-break-inside: avoid;
    }
    .frow > tbody > tr > td {
      vertical-align: top;
      padding: 0 16px 0 0;
    }
    .frow > tbody > tr > td:last-child { padding-right: 0; }
    .field-lbl {
      font-size: 8pt;
      font-weight: bold;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      color: #0d3a17;
      margin-bottom: 2px;
    }
    .field-val {
      border-bottom: 1px solid #c4cac6;
      min-height: 16px;
      font-size: 10pt;
      color: #1a1a1a;
      padding: 1px 0 2px;
      word-wrap: break-word;
    }
    .field-val.bold { font-weight: bold; }

    /* ── SECTION TITLES ── */
    .section {
      margin: 12px 0 6px;
      border-top: 2px solid #0d3a17;
      padding-top: 6px;
      page-break-after: avoid;
    }
    .section-title {
      font-size: 11pt;
      font-weight: bold;
      text-transform: uppercase;
      letter-spacing: 1.4px;
      color: #0d3a17;
    }
    .subsection {
      font-size: 10pt;
      font-weight: bold;
      text-transform: uppercase;
      letter-spacing: 1px;
      color: #0d3a17;
      border-bottom: 1px solid #b4d4bf;
      padding: 3px 0 4px;
      margin: 8px 0 6px;
      page-break-after: avoid;
    }

    /* ── QUESTION BLOCK ── */
    .q {
      margin-bottom: 8px;
      page-break-inside: avoid;
    }
    .q-lbl {
      font-size: 10pt;
      color: #0d3a17;
      font-weight: bold;
      margin-bottom: 4px;
    }
    .q-note {
      font-size: 8.5pt;
      color: #777;
      font-weight: normal;
      font-style: italic;
      margin-top: 2px;
    }
    .q-sub {
      font-size: 8.5pt;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      color: #0d3a17;
      font-weight: bold;
      margin: 10px 0 5px;
    }

    /* ── CHECKBOX (inline-table so box + label sit on one baseline) ── */
    .opt {
      display: inline-table;
      border-collapse: collapse;
      vertical-align: middle;
    }
    .opt-cell {
      display: table-cell;
      vertical-align: middle;
      padding: 0;
    }
    .cb {
      display: block;
      width: 12px;
      height: 12px;
      border: 1.3px solid #555;
      background: #fff;
      text-align: center;
      line-height: 10px;
      font-size: 10pt;
      font-weight: bold;
      color: #0d3a17;
      font-family: DejaVu Sans, sans-serif;
    }
    .cb-on { border-color: #0d3a17; background: #e8f3ec; }
    .opt-lbl {
      padding-left: 7px;
      font-size: 10pt;
      color: #1a1a1a;
      line-height: 1.3;
    }

    /* ── OPTION GRID — ONE CELL PER OPTION ── */
    .opts {
      width: 100%;
      border-collapse: collapse;
      table-layout: fixed;
    }
    .opts td {
      padding: 4px 10px 4px 0;
      font-size: 10pt;
      vertical-align: middle;
      color: #1a1a1a;
    }
    .opts tr + tr td { border-top: 1px solid #f4f6f5; }

    /* ── CHECKLIST — ONE OPTION PER ROW ── */
    .checklist { width: 100%; border-collapse: collapse; }
    .checklist td {
      padding: 4px 0;
      font-size: 10pt;
      vertical-align: middle;
      border-bottom: 1px solid #f4f6f5;
      color: #1a1a1a;
    }
    .checklist tr:last-child td { border-bottom: 0; }
    .checklist td.indent { padding-left: 24px; }

    /* ── TWO-COL CESSPOOL SECTION ── */
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
    .comments-wrap { margin-top: 8px; page-break-inside: avoid; }
    .comments-box {
      border: 1px solid #c4cac6;
      min-height: 36px;
      padding: 6px 10px;
      font-size: 10pt;
      color: #1a1a1a;
      background: #fafbfa;
      margin-top: 3px;
      line-height: 1.35;
    }
    .media-section { margin-top: 8px; page-break-inside: avoid; }
    .media-link { color: #0d3a17; word-break: break-all; text-decoration: underline; font-size: 9.5pt; }
    .media-note { font-size: 8.5pt; color: #777; margin-top: 2px; }

    /* ── FOOTER ── */
    .footer-rule { border-top: 1px solid #e0e0e0; margin: 10px 0 6px; }
    .disclaimer {
      font-size: 8.5pt;
      font-style: italic;
      color: #555;
      line-height: 1.4;
      padding: 5px 0;
      border-top: 1px solid #eee;
      border-bottom: 1px solid #eee;
      margin: 6px 0 4px;
    }
    .legend {
      width: 100%;
      border-collapse: collapse;
      font-size: 8.5pt;
      color: #555;
    }
    .legend td { padding: 2px 0; }

    /* ══════════ PAGE 2 ══════════ */
    .page2 {
      width: 700px;
      margin: 0 auto;
      padding: 26px 40px 30px;
      page-break-before: always;
    }
    .sketch-header { border-top: 3px solid #0d3a17; padding: 10px 0 6px; }
    .sketch-header-title {
      font-size: 12pt; font-weight: bold;
      text-transform: uppercase; letter-spacing: 0.5px;
      color: #0d3a17;
    }
    .sketch-header-sub { font-size: 8.5pt; color: #888; margin-top: 3px; }
    .sketch-rule { border-bottom: 1px solid #ddd; margin-bottom: 10px; }

    .sketch-wrap {
      width: 100%;
      height: 480px;
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
      height: 18px;
      background: transparent;
    }
    .knrow {
      width: 100%;
      border-collapse: collapse;
      margin-top: 14px;
      border-top: 1px solid #e6ede9;
      padding-top: 8px;
    }
    .knrow td { vertical-align: top; }
    .kn-key   { width: 200px; padding-right: 24px; padding-top: 10px; }
    .kn-notes { padding-left: 24px; padding-top: 10px; border-left: 1px solid #eee; }
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
      min-height: 22px;
      margin-bottom: 6px;
      font-size: 9.5pt;
      color: #1a1a1a;
      padding: 3px 0;
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
    <div class="header-title">Inspection Form for Operating Cesspool Systems</div>
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
        <td>{!! $opt($record->inspection_type, 'Home Inspector', 'Home Inspector') !!}</td>
        <td>{!! $opt($record->inspection_type, 'Realtor', 'Realtor') !!}</td>
        <td>{!! $opt($record->inspection_type, 'Routine Maintenance', 'Routine Maintenance') !!}</td>
      </tr>
    </table>
  </div>

  <!-- DATE / INSPECTOR -->
  <table class="frow">
    <tr>
      <td style="width:40%;">
        <div class="field-lbl">Date of Inspection</div>
        <div class="field-val">{{ $inspDate }}</div>
      </td>
      <td style="width:60%;">
        <div class="field-lbl">Inspector Name &amp; Company</div>
        <div class="field-val bold">{{ $record->inspector_name_company }}</div>
      </td>
    </tr>
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
        <td>{!! $opt($record->property_in_use, 'Yes', 'Yes') !!}</td>
        <td>{!! $opt($record->property_in_use, 'No', 'No') !!}</td>
        <td>{!! $opt($record->property_in_use, 'Full time', 'Full Time') !!}</td>
        <td>{!! $opt($record->property_in_use, 'Vacation Rental', 'Vacation Rental') !!}</td>
      </tr>
      <tr>
        <td>{!! $opt($record->property_in_use, 'Vacant', 'Vacant') !!}</td>
        <td>{!! $opt($record->property_in_use, 'Other', 'Other') !!}</td>
        <td>{!! $opt($record->property_in_use, 'Unknown', 'Unknown') !!}</td>
        <td></td>
      </tr>
    </table>
  </div>

  <!-- General Site Conditions -->
  <div class="q">
    <div class="q-lbl">General Site Conditions</div>
    <table class="opts">
      <colgroup><col style="width:50%;"><col style="width:50%;"></colgroup>
      <tr>
        <td>{!! $opt($record->site_conditions, 'Grass cover/vegetation condition', 'Grass Cover / Vegetation Condition') !!}</td>
        <td>{!! $opt($record->site_conditions, 'Surface Ponding', 'Surface Ponding') !!}</td>
      </tr>
      <tr>
        <td>{!! $opt($record->site_conditions, 'System area', 'System Area') !!}</td>
        <td>{!! $opt($record->site_conditions, 'Other areas', 'Other Areas') !!}</td>
      </tr>
      <tr>
        <td>{!! $opt($record->site_conditions, 'Protective Barriers Present', 'Protective Barriers Present') !!}</td>
        <td>{!! $opt($record->site_conditions, 'Effective', 'Effective') !!}</td>
      </tr>
      <tr>
        <td>{!! $opt($record->site_conditions, 'Not effective', 'Not Effective') !!}</td>
        <td></td>
      </tr>
    </table>
  </div>

  <!-- Surface Runoff -->
  <div class="q">
    <div class="q-lbl">Surface Runoff / Gutters Directed Away from System</div>
    <table class="opts">
      <colgroup><col style="width:25%;"><col style="width:25%;"><col style="width:25%;"><col style="width:25%;"></colgroup>
      <tr>
        <td>{!! $opt($record->surface_runoff, 'Yes', 'Yes') !!}</td>
        <td>{!! $opt($record->surface_runoff, 'No', 'No') !!}</td>
        <td>{!! $opt($record->surface_runoff, 'N/A', 'N/A') !!}</td>
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
        <td>{!! $opt($record->malfunction, 'Yes', 'Yes') !!}</td>
        <td>{!! $opt($record->malfunction, 'No', 'No') !!}</td>
        <td></td>
        <td></td>
      </tr>
    </table>

    <div class="q-sub">Surface Discharge via Straight-Pipe or Damaged Plumbing</div>
    <table class="opts">
      <colgroup><col style="width:34%;"><col style="width:33%;"><col style="width:33%;"></colgroup>
      <tr>
        <td>{!! $opt($record->surface_discharge, 'Grey water', 'Grey Water') !!}</td>
        <td>{!! $opt($record->surface_discharge, 'Black water', 'Black Water') !!}</td>
        <td>{!! $opt($record->surface_discharge, 'Unknown', 'Unknown') !!}</td>
      </tr>
    </table>
    <table class="opts" style="margin-top: 4px;">
      <colgroup><col style="width:50%;"><col style="width:50%;"></colgroup>
      <tr>
        <td>{!! $opt($record->surface_discharge, 'Surface discharge in area of cesspool', 'Surface Discharge in Area of Cesspool') !!}</td>
        <td>{!! $opt($record->surface_discharge, 'Surface discharge at edge of cesspool area', 'Surface Discharge at Edge of Cesspool Area') !!}</td>
      </tr>
      <tr>
        <td>{!! $opt($record->surface_discharge, 'Surface discharge - bleed-out away', 'Bleed-Out Away from System Location') !!}</td>
        <td>{!! $opt($record->surface_discharge, 'Evidence of past failure', 'Evidence of Past Failure') !!}</td>
      </tr>
    </table>
  </div>


  <!-- ═══ SYSTEM EVALUATION ═══ -->
  <div class="section">
    <div class="section-title">System Evaluation</div>
  </div>

  <div class="subsection">Cesspool</div>

  <table class="tank">
    <tr>
      <!-- LEFT COLUMN -->
      <td class="tcol-left">

        <div class="q">
          <div class="q-lbl">Accessible Lids</div>
          <table class="opts">
            <colgroup><col style="width:50%;"><col style="width:50%;"></colgroup>
            <tr>
              <td>{!! $opt($record->accessible_lids, 'Yes', 'Yes') !!}</td>
              <td>{!! $opt($record->accessible_lids, 'No', 'No') !!}</td>
            </tr>
          </table>
        </div>

        <div class="q">
          <div class="q-lbl">Access Lid(s) Need Repair</div>
          <table class="opts">
            <colgroup><col style="width:50%;"><col style="width:50%;"></colgroup>
            <tr>
              <td>{!! $opt($record->access_lid_repair, 'Yes', 'Yes') !!}</td>
              <td>{!! $opt($record->access_lid_repair, 'No', 'No') !!}</td>
            </tr>
          </table>
        </div>

        <table class="frow">
          <tr><td>
            <div class="field-lbl">Cesspool Water Level Depth</div>
            <div class="field-val">{{ $record->cesspool_water_level_depth }}</div>
          </td></tr>
        </table>

        <div class="q">
          <div class="q-lbl">
            Cesspool Pumping Recommended
            <div class="q-note">Sludge, scum &amp; liquid occupy &ge; 50% of cesspool volume</div>
          </div>
          <table class="opts">
            <colgroup><col style="width:50%;"><col style="width:50%;"></colgroup>
            <tr>
              <td>{!! $opt($record->pumping_recommended, 'Yes', 'Yes') !!}</td>
              <td>{!! $opt($record->pumping_recommended, 'No', 'No') !!}</td>
            </tr>
          </table>
        </div>

        <table class="frow">
          <tr><td>
            <div class="field-lbl">Cesspool Pumped of All Liquids &amp; Solids</div>
            <div class="field-val">{{ $record->cesspool_pumped }}</div>
          </td></tr>
        </table>

      </td>

      <!-- RIGHT COLUMN -->
      <td class="tcol-right">

        <table class="frow">
          <tr><td>
            <div class="field-lbl">Water Stream Flowing into Cesspool from House</div>
            <div class="field-val">{{ $record->water_stream_from_house }}</div>
          </td></tr>
        </table>

        <table class="frow">
          <tr><td>
            <div class="field-lbl">Inlet Pipe Needs Repair</div>
            <div class="field-val">{{ $record->inlet_pipe_needs_repair }}</div>
          </td></tr>
        </table>

        <table class="frow">
          <tr><td>
            <div class="field-lbl">Cesspool Composition</div>
            <div class="field-val">{{ $record->cesspool_composition }}</div>
          </td></tr>
        </table>

        <table class="frow">
          <tr><td>
            <div class="field-lbl">Service Recommended</div>
            <div class="field-val">{{ $record->service_recommended }}</div>
          </td></tr>
        </table>

      </td>
    </tr>
  </table>

  <!-- COMMENTS -->
  <div class="comments-wrap">
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

  <!-- SIGNATURE + DISCLAIMER + LEGEND — kept together -->
  <div style="page-break-inside: avoid;">
    <div class="footer-rule"></div>
    <table class="frow">
      <tr>
        <td style="width:40%;">
          <div class="field-lbl">Inspector Signature</div>
          <div class="field-val">{{ $record->inspector_signature }}</div>
        </td>
        <td style="width:35%;">
          <div class="field-lbl">Print Name</div>
          <div class="field-val">{{ $record->print_name }}</div>
        </td>
        <td style="width:25%;">
          <div class="field-lbl">Date</div>
          <div class="field-val">{{ $sigDate }}</div>
        </td>
      </tr>
    </table>

    <div class="disclaimer">
      <strong>Disclaimer:</strong>&ensp;The above information indicates the condition of the cesspool system at the time of inspection.
      This is not a guarantee or warranty of future system performance.
    </div>

    <table class="legend">
      <tr>
        <td style="width:50%;"><strong>N/A</strong> &mdash; Not Applicable</td>
        <td><strong>N/D</strong> &mdash; Not Determined</td>
      </tr>
    </table>
  </div>

</div><!-- /page -->


<!-- ═══════ PAGE 2 ═══════ -->
<div class="page2">

  <div class="sketch-header">
    <div class="sketch-header-title">Site Diagram &mdash; Cesspool System Layout</div>
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
        @for($r=0; $r<22; $r++)
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
        <div class="key-row"><span class="key-name">Cesspool</span><strong>CP</strong></div>
        <div class="key-row"><span class="key-name">Well</span><strong>W</strong></div>
      </td>
      <td class="kn-notes">
        <div class="kn-title">Notes</div>
        <div class="note-line">{{ $record->notes }}</div>
        <div class="note-line"></div>
        <div class="note-line"></div>
      </td>
    </tr>
  </table>

</div><!-- /page2 -->

</body>
</html>
