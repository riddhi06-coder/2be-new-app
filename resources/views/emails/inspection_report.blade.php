@php
    $logoUrl     = asset('admin/assets/images/logo/logo.webp');
    $inspDate    = $record->date_of_pickup
        ? \Carbon\Carbon::parse($record->date_of_pickup)->format('m/d/Y')
        : '—';
    $brandGreen  = '#0d3a17';
    $brandTint   = '#e8f3ec';
    $brandBorder = '#b4d4bf';
    $brandSoft   = '#6b7d72';
    $bgSurface   = '#f7faf8';
    $bgPage      = '#f1f4f2';
    $rowBorder   = '#e6ede9';
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{ $type }} Inspection Report — 2B Environmental</title>
</head>
<body style="margin:0; padding:0; background:{{ $bgPage }}; font-family: Arial, Helvetica, sans-serif; -webkit-text-size-adjust:100%;">

<!-- Preheader (hidden preview text for inbox list) -->
<div style="display:none; max-height:0; overflow:hidden; mso-hide:all;">
  {{ $type }} inspection report for {{ $record->site_address ?? 'the property' }} — PDF attached.
</div>

<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background:{{ $bgPage }};">
  <tr>
    <td align="center" style="padding: 32px 14px;">

      <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="600" style="max-width:600px; width:100%; background:#ffffff; border:1px solid {{ $rowBorder }}; border-radius:6px;">

        <!-- ═══════════════ LOGO STRIP ═══════════════ -->
        <tr>
          <td align="center" style="padding: 28px 30px 18px; background:#ffffff; border-bottom: 3px solid {{ $brandGreen }};">
            <img src="{{ $logoUrl }}" alt="2B Environmental" height="62" style="display:block; height:62px; max-height:62px; border:0; outline:none; text-decoration:none;">
          </td>
        </tr>

        <!-- ═══════════════ HEADER ═══════════════ -->
        <tr>
          <td style="padding: 24px 36px 6px; background:#ffffff;">
            <h1 style="margin:0; color:{{ $brandGreen }}; font-size:20px; font-weight:bold; letter-spacing:0.6px; text-transform:uppercase; font-family: Arial, Helvetica, sans-serif; line-height:1.25;">
              {{ $type }} Inspection Report
            </h1>
            <p style="margin:6px 0 0; color:{{ $brandSoft }}; font-size:12px; font-family: Arial, Helvetica, sans-serif; letter-spacing:0.3px;">
              Official Condition Report &mdash; Licensed Inspector Use Only
            </p>
          </td>
        </tr>

        <!-- ═══════════════ BODY ═══════════════ -->
        <tr>
          <td style="padding: 22px 36px 8px; background:#ffffff;">

            <p style="margin:0 0 12px; font-size:14px; line-height:1.6; color:#1a1a1a; font-family: Arial, Helvetica, sans-serif;">
              Hello,
            </p>
            <p style="margin:0 0 20px; font-size:14px; line-height:1.6; color:#1a1a1a; font-family: Arial, Helvetica, sans-serif;">
              Please find attached the <strong style="color:{{ $brandGreen }};">{{ $type }} System Inspection Report</strong> for the property listed below. The attached PDF contains the full inspection record, including site observations, system evaluation, inspector signature, and site diagram.
            </p>

            <!-- ─── INFO CARD ─── -->
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background:{{ $bgSurface }}; border:1px solid {{ $rowBorder }}; border-left: 4px solid {{ $brandGreen }}; border-radius:3px; margin: 4px 0 22px;">
              <tr>
                <td style="padding: 6px 18px 10px;">
                  <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">

                    <tr>
                      <td style="padding:10px 0 4px; font-size:10.5px; font-weight:bold; text-transform:uppercase; letter-spacing:0.5px; color:{{ $brandGreen }}; font-family: Arial, Helvetica, sans-serif;">
                        Record ID
                      </td>
                    </tr>
                    <tr>
                      <td style="padding:0 0 10px; font-size:14px; color:#1a1a1a; font-family: Arial, Helvetica, sans-serif; border-bottom:1px solid {{ $rowBorder }};">
                        #{{ $record->id ?? '—' }}
                      </td>
                    </tr>

                    <tr>
                      <td style="padding:10px 0 4px; font-size:10.5px; font-weight:bold; text-transform:uppercase; letter-spacing:0.5px; color:{{ $brandGreen }}; font-family: Arial, Helvetica, sans-serif;">
                        Site Address
                      </td>
                    </tr>
                    <tr>
                      <td style="padding:0 0 10px; font-size:14px; color:#1a1a1a; font-family: Arial, Helvetica, sans-serif; border-bottom:1px solid {{ $rowBorder }};">
                        {{ $record->site_address ?? '—' }}
                      </td>
                    </tr>

                    <tr>
                      <td style="padding:10px 0 4px; font-size:10.5px; font-weight:bold; text-transform:uppercase; letter-spacing:0.5px; color:{{ $brandGreen }}; font-family: Arial, Helvetica, sans-serif;">
                        Inspector
                      </td>
                    </tr>
                    <tr>
                      <td style="padding:0 0 10px; font-size:14px; color:#1a1a1a; font-family: Arial, Helvetica, sans-serif; border-bottom:1px solid {{ $rowBorder }};">
                        {{ $record->inspector_name_company ?? '—' }}
                      </td>
                    </tr>

                    <tr>
                      <td style="padding:10px 0 4px; font-size:10.5px; font-weight:bold; text-transform:uppercase; letter-spacing:0.5px; color:{{ $brandGreen }}; font-family: Arial, Helvetica, sans-serif;">
                        Date of Inspection
                      </td>
                    </tr>
                    <tr>
                      <td style="padding:0 0 10px; font-size:14px; color:#1a1a1a; font-family: Arial, Helvetica, sans-serif; border-bottom:1px solid {{ $rowBorder }};">
                        {{ $inspDate }}
                      </td>
                    </tr>

                    <tr>
                      <td style="padding:10px 0 4px; font-size:10.5px; font-weight:bold; text-transform:uppercase; letter-spacing:0.5px; color:{{ $brandGreen }}; font-family: Arial, Helvetica, sans-serif;">
                        Type of System
                      </td>
                    </tr>
                    <tr>
                      <td style="padding:0 0 6px; font-size:14px; color:#1a1a1a; font-family: Arial, Helvetica, sans-serif;">
                        {{ $record->type_of_system ?? '—' }}
                      </td>
                    </tr>

                  </table>
                </td>
              </tr>
            </table>

            <!-- ─── ATTACHMENT NOTICE ─── -->
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background:{{ $brandTint }}; border:1px solid {{ $brandBorder }}; border-radius:3px; margin: 0 0 22px;">
              <tr>
                <td style="padding: 12px 18px; font-size:13px; color:{{ $brandGreen }}; font-family: Arial, Helvetica, sans-serif; line-height:1.5;">
                  <strong style="text-transform:uppercase; letter-spacing:0.5px; font-size:11px;">PDF Report Attached</strong><br>
                  <span style="color:#1a1a1a;">Open the attachment to view the complete inspection report with all observations, evaluation findings, media link, and site layout diagram.</span>
                </td>
              </tr>
            </table>

            <p style="margin:0 0 14px; font-size:14px; line-height:1.6; color:#1a1a1a; font-family: Arial, Helvetica, sans-serif;">
              If you have any questions about this report, please don't hesitate to reach out — we're happy to help.
            </p>

            <p style="margin:18px 0 4px; font-size:14px; line-height:1.6; color:#1a1a1a; font-family: Arial, Helvetica, sans-serif;">
              Thank you,
            </p>
            <p style="margin:0; font-size:14px; line-height:1.6; font-weight:bold; color:{{ $brandGreen }}; font-family: Arial, Helvetica, sans-serif;">
              2B Environmental Team
            </p>

          </td>
        </tr>

        <!-- ═══════════════ FOOTER ═══════════════ -->
        <tr>
          <td style="padding: 22px 36px; background:{{ $bgSurface }}; border-top:1px solid {{ $rowBorder }};">
            <p style="margin:0 0 10px; font-size:12px; color:{{ $brandGreen }}; font-weight:bold; font-family: Arial, Helvetica, sans-serif; text-align:center; letter-spacing:0.4px;">
              2B ENVIRONMENTAL, INC.
            </p>
            <p style="margin:0 0 14px; font-size:11.5px; color:{{ $brandSoft }}; font-family: Arial, Helvetica, sans-serif; text-align:center; line-height:1.6;">
              Septic Tanks &middot; Cesspools &middot; Sweetwater Pumping<br>
              <a href="tel:8088857159" style="color:{{ $brandGreen }}; text-decoration:none; font-weight:bold;">808-885-7159</a>
            </p>
            <p style="margin:0 0 8px; font-size:10.5px; color:#888; font-style:italic; line-height:1.55; font-family: Arial, Helvetica, sans-serif; text-align:center; border-top:1px solid {{ $rowBorder }}; padding-top:12px;">
              <strong style="font-style:normal;">Disclaimer:</strong> This report indicates the condition of the system at the time of inspection and is not a guarantee or warranty of future system performance.
            </p>
            <p style="margin:6px 0 0; font-size:10px; color:#aaa; font-family: Arial, Helvetica, sans-serif; text-align:center;">
              &copy; {{ \Carbon\Carbon::now()->format('Y') }} 2B Environmental, Inc. &nbsp;|&nbsp; All rights reserved.
            </p>
          </td>
        </tr>

      </table>

    </td>
  </tr>
</table>

</body>
</html>
