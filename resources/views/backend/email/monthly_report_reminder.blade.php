<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Monthly Report Reminder</title>
</head>
<body style="margin:0; padding:0; font-family: Arial, Helvetica, sans-serif; background-color:#f4f6f9;">

    <table width="100%" cellpadding="0" cellspacing="0" style="padding:40px 0;">
        <tr>
            <td align="center">
                
                <table width="600" cellpadding="0" cellspacing="0" 
                       style="background:#ffffff; padding:30px; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,0.05);">

                    <tr>
                        <td style="font-size:20px; font-weight:bold; color:#333;">
                            Monthly Report Reminder
                        </td>
                    </tr>

                    <tr>
                        <td style="padding-top:20px; font-size:14px; color:#555;">
                            Hello,
                        </td>
                    </tr>

                    <tr>
                        <td style="padding-top:15px; font-size:14px; color:#555; line-height:22px;">
                            This is a friendly reminder to generate your 
                            <strong>Monthly Pumping and Hauling Source Report</strong>.
                        </td>
                    </tr>

                    <tr>
                        <td style="padding-top:15px; font-size:14px; color:#555; line-height:22px;">
                            Please click the button below to access the report generation page in the admin portal.
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding:30px 0;">
                            <a href="{{ route('admin.login') }}"
                               style="background-color:#0d6efd;
                                      color:#ffffff;
                                      padding:12px 25px;
                                      text-decoration:none;
                                      border-radius:5px;
                                      font-size:14px;
                                      font-weight:bold;
                                      display:inline-block;">
                                Generate Monthly Report
                            </a>
                        </td>
                    </tr>

                    <tr>
                        <td style="font-size:13px; color:#888; line-height:20px;">
                            If you have already generated this report, please disregard this message.
                        </td>
                    </tr>

                    <tr>
                        <td style="padding-top:25px; font-size:14px; color:#555;">
                            Thank you,<br>
                            <strong>Admin System</strong>
                        </td>
                    </tr>

                </table>

                <table width="600" cellpadding="0" cellspacing="0" style="margin-top:15px;">
                    <tr>
                        <td align="center" style="font-size:12px; color:#aaa;">
                            This is an automated reminder. Please do not reply to this email.
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>

</body>
</html>