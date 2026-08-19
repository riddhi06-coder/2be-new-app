@php
    // Black & white palette
    $ink      = '#111111';
    $dark     = '#333333';
    $mid      = '#666666';
    $soft      = '#999999';
    $border   = '#dddddd';
    $surface  = '#f7f7f7';
    $bgPage   = '#eeeeee';

    // Embed the logo inline (CID) so it travels with the email.
    $logoUrl = isset($message)
        ? $message->embed(public_path('admin/assets/images/logo/logo.png'))
        : asset('admin/assets/images/logo/logo.png');
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Welcome to the {{ config('app.name') }} Employee Portal</title>
</head>
<body style="margin:0; padding:0; background:{{ $bgPage }}; font-family: Arial, Helvetica, sans-serif; -webkit-text-size-adjust:100%;">

<!-- Preheader (hidden preview text) -->
<div style="display:none; max-height:0; overflow:hidden; mso-hide:all;">
  Your account for the {{ config('app.name') }} Employee Portal is ready — login details enclosed.
</div>

<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background:{{ $bgPage }};">
  <tr>
    <td align="center" style="padding: 32px 14px;">

      <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="600" style="max-width:600px; width:100%; background:#ffffff; border:1px solid {{ $border }};">

        <!-- ═══════════════ HEADER (logo) ═══════════════ -->
        <tr>
          <td align="center" style="padding: 26px 30px 22px; background:#ffffff; border-bottom: 3px solid {{ $ink }};">
            <img src="{{ $logoUrl }}" alt="2B Environmental, Inc." width="115" style="display:block; width:115px; max-width:115px; height:auto; border:0; outline:none; text-decoration:none;">
          </td>
        </tr>

        <!-- ═══════════════ TITLE ═══════════════ -->
        <tr>
          <td style="padding: 26px 36px 4px; background:#ffffff; border-bottom:1px solid {{ $border }};">
            <h1 style="margin:0 0 14px; color:{{ $ink }}; font-size:19px; font-weight:bold; letter-spacing:0.6px; text-transform:uppercase; font-family: Arial, Helvetica, sans-serif; line-height:1.3;">
              Welcome to the Employee Portal
            </h1>
          </td>
        </tr>

        <!-- ═══════════════ BODY ═══════════════ -->
        <tr>
          <td style="padding: 24px 36px 8px; background:#ffffff;">

            <p style="margin:0 0 12px; font-size:14px; line-height:1.6; color:{{ $dark }}; font-family: Arial, Helvetica, sans-serif;">
              Dear {{ $name }},
            </p>
            <p style="margin:0 0 22px; font-size:14px; line-height:1.6; color:{{ $dark }}; font-family: Arial, Helvetica, sans-serif;">
              An account has been created for you on the <strong style="color:{{ $ink }};">{{ config('app.name') }} Employee Portal</strong>.
              You can sign in using the credentials below to access your documents and company resources.
            </p>

            <!-- ─── CREDENTIALS CARD ─── -->
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background:{{ $surface }}; border:1px solid {{ $border }}; border-left: 4px solid {{ $ink }}; margin: 0 0 24px;">
              <tr>
                <td style="padding: 6px 20px 12px;">
                  <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
                    <tr>
                      <td style="padding:12px 0 4px; font-size:10.5px; font-weight:bold; text-transform:uppercase; letter-spacing:0.8px; color:{{ $mid }}; font-family: Arial, Helvetica, sans-serif;">
                        Email Address
                      </td>
                    </tr>
                    <tr>
                      <td style="padding:0 0 12px; font-size:14px; color:{{ $ink }}; font-family: Arial, Helvetica, sans-serif; border-bottom:1px solid {{ $border }};">
                        {{ $email }}
                      </td>
                    </tr>
                    <tr>
                      <td style="padding:12px 0 4px; font-size:10.5px; font-weight:bold; text-transform:uppercase; letter-spacing:0.8px; color:{{ $mid }}; font-family: Arial, Helvetica, sans-serif;">
                        Temporary Password
                      </td>
                    </tr>
                    <tr>
                      <td style="padding:0 0 6px; font-size:15px; color:{{ $ink }}; font-family: 'Courier New', Courier, monospace; letter-spacing:0.5px; font-weight:bold;">
                        {{ $password }}
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>

            <!-- ─── LOGIN BUTTON ─── -->
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="margin: 0 0 24px;">
              <tr>
                <td align="center">
                  <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                      <td align="center" style="background:{{ $ink }};">
                        <a href="{{ $loginUrl }}" target="_blank"
                           style="display:inline-block; padding:13px 36px; font-size:14px; font-weight:bold; color:#ffffff; text-decoration:none; font-family: Arial, Helvetica, sans-serif; letter-spacing:0.5px; text-transform:uppercase;">
                          Sign In to Your Account
                        </a>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>

            <!-- ─── SECURITY NOTICE ─── -->
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background:#ffffff; border:1px solid {{ $border }}; margin: 0 0 22px;">
              <tr>
                <td style="padding: 12px 18px; font-size:13px; color:{{ $dark }}; font-family: Arial, Helvetica, sans-serif; line-height:1.5;">
                  <strong style="text-transform:uppercase; letter-spacing:0.5px; font-size:11px; color:{{ $ink }};">Security Reminder</strong><br>
                  For your protection, please change your password after signing in for the first time, and keep these credentials confidential.
                </td>
              </tr>
            </table>

            <p style="margin:0 0 14px; font-size:14px; line-height:1.6; color:{{ $dark }}; font-family: Arial, Helvetica, sans-serif;">
              If you did not expect this email or believe it was sent in error, please contact your administrator.
            </p>

            <p style="margin:18px 0 4px; font-size:14px; line-height:1.6; color:{{ $dark }}; font-family: Arial, Helvetica, sans-serif;">
              Thank you,
            </p>
            <p style="margin:0; font-size:14px; line-height:1.6; font-weight:bold; color:{{ $ink }}; font-family: Arial, Helvetica, sans-serif;">
              2B Environmental Team
            </p>

          </td>
        </tr>

        <!-- ═══════════════ FOOTER ═══════════════ -->
        <tr>
          <td style="padding: 22px 36px; background:{{ $surface }}; border-top:1px solid {{ $border }};">
            <p style="margin:0 0 8px; font-size:12px; color:{{ $ink }}; font-weight:bold; font-family: Arial, Helvetica, sans-serif; text-align:center; letter-spacing:0.4px;">
              2B ENVIRONMENTAL, INC.
            </p>
            <p style="margin:0 0 14px; font-size:11.5px; color:{{ $mid }}; font-family: Arial, Helvetica, sans-serif; text-align:center; line-height:1.6;">
              Septic Tanks &middot; Cesspools &middot; Sweetwater Pumping<br>
              <a href="tel:8088857159" style="color:{{ $ink }}; text-decoration:none; font-weight:bold;">808-885-7159</a>
            </p>
            <p style="margin:0 0 8px; font-size:10.5px; color:{{ $soft }}; font-style:italic; line-height:1.55; font-family: Arial, Helvetica, sans-serif; text-align:center; border-top:1px solid {{ $border }}; padding-top:12px;">
              <strong style="font-style:normal; color:{{ $mid }};">Confidential:</strong> This message contains login credentials intended only for the named recipient. If you are not the intended recipient, please delete it and notify the sender.
            </p>
            <p style="margin:6px 0 0; font-size:10px; color:{{ $soft }}; font-family: Arial, Helvetica, sans-serif; text-align:center;">
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
