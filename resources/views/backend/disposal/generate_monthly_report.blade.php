<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Wastewater Sludge Pumping Source Report</title>
    
    <style>
    @page {
        size: landscape;
        margin: 20px;
    }
</style>

</head>
<body style="font-family: Arial, Helvetica, sans-serif; font-size: 13px; margin: 20px;">

    <div style="text-align: center;">
        <strong>COUNTY OF HAWAI‘I - DEPT OF ENVIRONMENTAL MGMT - WASTEWATER DIVISION</strong><br>
        Admin Phone #: 961-8338 &nbsp;&nbsp; Admin Fax #: 961-8086<br>
        Hilo Plant Phone #: 961-8651 &nbsp;&nbsp; Kona Plant Phone #: 327-3508
    </div>

    <br>

    <div style="text-align: center; font-weight: bold;">
        WASTEWATER SLUDGE PUMPING AND HAULING SOURCE REPORT
    </div>

    <br>

     <table width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <!-- LEFT COLUMN -->
        <td style="width:60%; vertical-align:top;">
            <table cellpadding="0" cellspacing="0">
                <tr style="height:34px;">
                    <td>
                        <strong>Company:</strong> BOB'S SWEETWATER PUMPING SERVICE (2B ENVIRONMENTAL, INC.)
                    </td>
                </tr>
                <tr style="height:34px;">
                    <td style="padding-top:6px;">
                        <strong>SOH DOH Registration Number:</strong> {{ $soh_doh_registration }}
                    </td>
                </tr>
            </table>
        </td>

        <!-- RIGHT COLUMN -->
        <td style="width:40%; vertical-align:top;">
            <table cellpadding="0" cellspacing="0" style="float:right;">
                <tr style="height:34px;">
                    <td style="width:90px;">Month/Yr:</td>
                    <td style="width:140px; border-bottom:1px solid #000;">{{ $month_name }} {{ $year }}</td>
                </tr>
                <tr style="height:34px;">
                    <td style="width:90px;padding-top:6px;">COH Permit #:</td>
                    <td style="width:140px;padding-top:6px; border-bottom:1px solid #000; font-weight:bold;">
                        {{ $coh_permit }}
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>


    <br>

    <table width="100%" cellpadding="4" cellspacing="0" style="border-collapse: collapse; border: 1px solid #000;font-size: 11px;">
        <tr>
            <th style="border: 1px solid #000;">DATE OF PICKUP / PUMPING</th>
            <th style="border: 1px solid #000;">FACILITY OR OWNER'S NAME (SOURCE)</th>
            <th style="border: 1px solid #000;">STREET ADDRESS<br>(TMK # if no address assigned)</th>
            <th style="border: 1px solid #000;">UNIT #</th>
            <th style="border: 1px solid #000;">ZIP CODE</th>
            <th style="border: 1px solid #000;">VOLUME PUMPED<br>(PICK UP AMT)<br>(GALS)</th>
            <th style="border: 1px solid #000;">WASTE TYPE<br>(Cesspool, Septage, Sludge, Port Toilet, etc)</th>
            <th style="border: 1px solid #000;">DATE OF DISCHARGE</th>
            <th style="border: 1px solid #000;">DISCHARGE SITE<br>(Hilo / Kona)</th>
            <th style="border: 1px solid #000;">VEHICLE LICENSE NO.</th>
            <th style="border: 1px solid #000;">DRIVER INITIALS</th>
        </tr>

        <!-- Empty rows for writing -->
        <!-- Repeat as needed -->
        @foreach($records as $row)
            <tr>
                <td style="border: 1px solid #000;">
                    {{ \Carbon\Carbon::parse($row->date_of_pickup)->format('m/d/Y') }}
                </td>
                <td style="border: 1px solid #000;">{{ $row->generator_name }}</td>
                <td style="border: 1px solid #000;">{{ $row->address }}</td>
                <td style="border: 1px solid #000;">{{ $row->unit }}</td>
                <td style="border: 1px solid #000;">{{ $row->zip }}</td>
                <td style="border: 1px solid #000;">{{ $row->volume_pumped }}</td>
                <td style="border: 1px solid #000;">{{ $row->waste_type }}</td>
                <td style="border: 1px solid #000;">{{ $row->date_of_discharge }}</td>
                <td style="border: 1px solid #000;">{{ $row->discharge_site }}</td>
                <td style="border: 1px solid #000;">{{ $row->vehicle_license_number }}</td>
                <td style="border: 1px solid #000;">{{ $row->transporter_name }}</td>
            </tr>
        @endforeach
    </table>

    <br><br>

    <table width="100%" cellpadding="0" cellspacing="0" style="font-size:11px;">
        <tr>
            <td style="width:85%; vertical-align:top;font-size: 13px;">
                I certify that the information provided herein is true and accurate:
            </td>
            <td style="width:15%; text-align:right; vertical-align:top;font-size:10px;">
                Rev. 03/2013
            </td>
        </tr>
    </table>

    <br>
    
    <table width="100%" cellpadding="4" cellspacing="0" style="font-size:11px;">
        <tr>
            <td style="width:40%; vertical-align:top;">
                Signature:<br>
                <div style="border-bottom:1px solid #000; height:18px; width:100%;">{{ $signed_by }}</div>
            </td>
    
            <td style="width:30%; vertical-align:top;">
                Title:<br>
                <div style="border-bottom:1px solid #000; height:18px; width:100%;"> {{ $title }}</div>
            </td>
    
            <td style="width:30%; vertical-align:top;">
                Date:<br>
                <div style="border-bottom:1px solid #000; height:18px; width:100%;">{{ \Carbon\Carbon::parse($signed_date)->format('m/d/Y') }}</div>
            </td>
        </tr>
    </table>


    <br>

    <div style="font-size: 10px;">
        <strong>Note:</strong>Disposal information will be audited periodically with theState of Hawai"i Dept.of Health. Permit will be revoked if thisSource Report is falsified orif Septage Hauler Permit Conditions are violated
    </div>

</body>
</html>
