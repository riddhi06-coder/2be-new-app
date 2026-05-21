<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1a1a1a; line-height: 1.5; }
    .page { padding: 30px 35px; }
    .report-header { border-bottom: 3px solid #155724; padding-bottom: 12px; margin-bottom: 20px; }
    .report-header h1 { font-size: 18px; color: #155724; font-weight: 700; }
    .report-header p  { font-size: 10px; color: #555; margin-top: 2px; }
    .report-meta { float: right; text-align: right; font-size: 10px; color: #555; }
    .section-title { background: #155724; color: #fff; padding: 5px 10px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-top: 18px; margin-bottom: 8px; border-radius: 3px; }
    table.data-table { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
    table.data-table td { padding: 5px 8px; border: 1px solid #dde4ec; font-size: 10.5px; vertical-align: top; }
    table.data-table td.label { background: #e9f5ee; font-weight: 600; width: 38%; color: #155724; }
    .badge-draft     { background: #ffc107; color: #333; padding: 2px 8px; border-radius: 3px; font-size: 10px; font-weight: 700; }
    .badge-submitted { background: #198754; color: #fff; padding: 2px 8px; border-radius: 3px; font-size: 10px; font-weight: 700; }
    .report-footer { margin-top: 28px; border-top: 1px solid #ccc; padding-top: 8px; font-size: 9px; color: #888; text-align: center; }
    .clearfix::after { content: ''; display: table; clear: both; }
</style>
</head>
<body>
<div class="page">

    <div class="report-header clearfix">
        <div class="report-meta">
            Record ID: #{{ $record->id }}<br>
            Status: @if($record->is_draft)<span class="badge-draft">Draft</span>@else<span class="badge-submitted">Submitted</span>@endif<br>
            Generated: {{ \Carbon\Carbon::now()->format('m/d/Y H:i') }}
        </div>
        <h1>2B Environmental</h1>
        <p>Septic System Inspection Report</p>
    </div>

    <div class="section-title">Basic Information</div>
    <table class="data-table">
        <tr>
            <td class="label">Type of Inspection</td>
            <td>{{ $record->inspection_type ?: '—' }}</td>
            <td class="label">Date of Inspection</td>
            <td>{{ $record->date_of_pickup ? \Carbon\Carbon::parse($record->date_of_pickup)->format('m/d/Y') : '—' }}</td>
        </tr>
        <tr>
            <td class="label">Inspector Name & Company</td>
            <td>{{ $record->inspector_name_company ?: '—' }}</td>
            <td class="label">Type of System</td>
            <td>{{ $record->type_of_system ?: '—' }}</td>
        </tr>
        <tr>
            <td class="label">Site Address</td>
            <td colspan="3">{{ $record->site_address ?: '—' }}</td>
        </tr>
        <tr>
            <td class="label">Tax Map Number</td>
            <td>{{ $record->tax_map_number ?: '—' }}</td>
            <td class="label">Time / Weather</td>
            <td>{{ $record->time ?: '—' }} / {{ $record->weather ?: '—' }}</td>
        </tr>
    </table>

    <div class="section-title">Site Observations</div>
    <table class="data-table">
        <tr>
            <td class="label">Property in Use</td>
            <td>{{ $record->property_in_use ?: '—' }}</td>
            <td class="label">Surface Runoff</td>
            <td>{{ $record->surface_runoff ?: '—' }}</td>
        </tr>
        <tr>
            <td class="label">General Site Conditions</td>
            <td colspan="3">{{ $record->site_conditions ?: '—' }}</td>
        </tr>
        <tr>
            <td class="label">Malfunction / Surface Discharge</td>
            <td colspan="3">{{ $record->malfunction ?: '—' }}</td>
        </tr>
    </table>

    <div class="section-title">System Evaluation</div>
    <table class="data-table">
        <tr>
            <td class="label">Manhole Accessible</td>
            <td>{{ $record->manhole_accessible ?: '—' }}</td>
            <td class="label">Lid(s) Need Repair</td>
            <td>{{ $record->lid_needs_repair ?: '—' }}</td>
        </tr>
        <tr>
            <td class="label">Liquid Operating Level</td>
            <td colspan="3">{{ $record->liquid_operating_level ?: '—' }}</td>
        </tr>
        <tr>
            <td class="label">Scum Layer Thickness</td>
            <td>{{ $record->scum_layer_thickness ?: '—' }} in.</td>
            <td class="label">Sludge Layer Thickness</td>
            <td>{{ $record->sludge_layer_thickness ?: '—' }} in.</td>
        </tr>
        <tr>
            <td class="label">Tank Pumping Recommended</td>
            <td>{{ $record->tank_pumping_recommended ?: '—' }}</td>
            <td class="label">Tank Pumped</td>
            <td>{{ $record->tank_pumped ?: '—' }}</td>
        </tr>
        <tr>
            <td class="label">Approx. Volume Pumped</td>
            <td>{{ $record->approx_volume_pumped ?: '—' }} gals</td>
            <td class="label">Tank Composition</td>
            <td>{{ $record->tank_composition ?: '—' }}</td>
        </tr>
        <tr>
            <td class="label">Approx. Tank Size</td>
            <td>{{ $record->approx_tank_size ?: '—' }} gals</td>
            <td class="label">Service Recommended</td>
            <td>{{ $record->service_recommended ?: '—' }}</td>
        </tr>
        <tr>
            <td class="label">Water Stream from House</td>
            <td>{{ $record->water_stream_from_house ?: '—' }}</td>
            <td class="label">Water Stream from Drain Field</td>
            <td>{{ $record->water_stream_from_drain ?: '—' }}</td>
        </tr>
        <tr>
            <td class="label">Inlet Tee Needs Repair</td>
            <td>{{ $record->inlet_tee_needs_repair ?: '—' }}</td>
            <td class="label">Outlet Tee Needs Repair</td>
            <td>{{ $record->outlet_tee_needs_repair ?: '—' }}</td>
        </tr>
        <tr>
            <td class="label">Comments</td>
            <td colspan="3">{{ $record->comments ?: '—' }}</td>
        </tr>
        <tr>
            <td class="label">Notes</td>
            <td colspan="3">{{ $record->notes ?: '—' }}</td>
        </tr>
    </table>

    <div class="section-title">Signature</div>
    <table class="data-table">
        <tr>
            <td class="label">Inspector Signature</td>
            <td>{{ $record->inspector_signature ?: '—' }}</td>
        </tr>
    </table>

    <p style="margin-top:14px; font-size:9px; color:#666; font-style:italic;">
        <strong>Disclaimer:</strong> The above information indicates the conditions of the septic system at the time of inspection.
        This is not a guarantee or warranty of future system performance.
    </p>

    <div class="report-footer">
        2B Environmental &nbsp;|&nbsp; Septic Inspection Report &nbsp;|&nbsp; Record #{{ $record->id }} &nbsp;|&nbsp; {{ \Carbon\Carbon::now()->format('m/d/Y') }}
    </div>

</div>
</body>
</html>
