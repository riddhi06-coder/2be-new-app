<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<style>
    body { font-family: Arial, sans-serif; font-size: 14px; color: #333; margin: 0; padding: 0; background: #f4f4f4; }
    .wrapper { max-width: 600px; margin: 30px auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
    .email-header { background: #1a5276; padding: 24px 30px; }
    .email-header h1 { color: #fff; font-size: 20px; margin: 0; }
    .email-header p  { color: #aed6f1; font-size: 13px; margin: 4px 0 0; }
    .email-body { padding: 28px 30px; }
    .email-body p { line-height: 1.7; margin-bottom: 14px; }
    .info-box { background: #f0f4f8; border-left: 4px solid #1a5276; padding: 12px 16px; border-radius: 4px; margin: 18px 0; }
    .info-box .row { display: flex; justify-content: space-between; padding: 4px 0; border-bottom: 1px solid #e0e8f0; font-size: 13px; }
    .info-box .row:last-child { border-bottom: none; }
    .info-box .row .label { font-weight: 600; color: #555; width: 45%; }
    .info-box .row .value { color: #222; width: 55%; }
    .email-footer { background: #f8f9fa; padding: 16px 30px; text-align: center; font-size: 11px; color: #999; border-top: 1px solid #eee; }
</style>
</head>
<body>
<div class="wrapper">
    <div class="email-header">
        <h1>2B Environmental</h1>
        <p>{{ $type }} System Inspection Report</p>
    </div>
    <div class="email-body">
        <p>Hello,</p>
        <p>
            Please find attached the <strong>{{ $type }} Inspection Report</strong> for the following property.
            The full details are included in the attached PDF document.
        </p>
        <div class="info-box">
            <div class="row">
                <span class="label">Site Address</span>
                <span class="value">{{ $record->site_address ?? '—' }}</span>
            </div>
            <div class="row">
                <span class="label">Inspector</span>
                <span class="value">{{ $record->inspector_name_company ?? '—' }}</span>
            </div>
            <div class="row">
                <span class="label">Date of Inspection</span>
                <span class="value">{{ $record->date_of_pickup ? \Carbon\Carbon::parse($record->date_of_pickup)->format('m/d/Y') : '—' }}</span>
            </div>
            <div class="row">
                <span class="label">Type of System</span>
                <span class="value">{{ $record->type_of_system ?? '—' }}</span>
            </div>
        </div>
        <p>
            If you have any questions regarding this report, please do not hesitate to contact us.
        </p>
        <p>Thank you,<br><strong>2B Environmental Team</strong></p>
    </div>
    <div class="email-footer">
        This email was sent by 2B Environmental &nbsp;|&nbsp; {{ \Carbon\Carbon::now()->format('Y') }}<br>
        <em>Disclaimer: This report indicates the conditions at the time of inspection and is not a guarantee of future system performance.</em>
    </div>
</div>
</body>
</html>
