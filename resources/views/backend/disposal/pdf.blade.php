<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <style>
        body {
            font-family: DejaVu Sans;
            font-size: 12px;
            color: #333;
            padding: 25px;
        }

        .header {
            border-bottom: 2px solid #3e3e3e;
            margin-bottom: 20px;
            padding-bottom: 12px;
        }

        .header-table {
            width: 100%;
        }

        .header-title {
            font-size: 20px;
            color: #4f5150;
            margin: 0;
        }

        .header small {
            color: #666;
            font-size: 11px;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .details-table th {
            width: 35%;
            background: #f4f6f9;
            text-align: left;
            padding: 8px 10px;
            border: 1px solid #dcdcdc;
            font-weight: 600;
        }

        .details-table td {
            width: 65%;
            padding: 8px 10px;
            border: 1px solid #dcdcdc;
        }

        .footer {
            position: fixed;
            bottom: 12px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #888;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div style="text-align: center; margin-bottom: 25px;">
        <img 
            src="{{ public_path('frontend/assets/images/logo.jpg') }}" 
            style="height: 140px;"
            alt="Logo"
        >
    </div>

    <!-- Title -->
    <div class="header" style="text-align: center;">
        <h2 class="header-title">Disposal Details</h2>
        <small>Generated on {{ date('d M Y') }}</small>
    </div>

    <!-- Details Table -->
    <table class="details-table">

        <tr>
            <th>IP Address</th>
            <td>{{ $disposal->ip_address ?? '-' }}</td>
        </tr>

        <tr>
            <th>Date of Pickup</th>
            <td>
                {{ $disposal->date_of_pickup
                    ? \Carbon\Carbon::parse($disposal->date_of_pickup)->format('d M Y')
                    : '-' }}
            </td>
        </tr>

        <tr>
            <th>Generator Name</th>
            <td>{{ $disposal->generator_name ?? '-' }}</td>
        </tr>

        <tr>
            <th>Waste Type</th>
            <td>{{ $disposal->waste_type ?? '-' }}</td>
        </tr>

        <tr>
            <th>Address</th>
            <td>{{ $disposal->address ?? '-' }}</td>
        </tr>

        <tr>
            <th>Volume Pumped</th>
            <td>
                {{ $disposal->volume_pumped ?? '-' }}
                {{ $disposal->unit ?? '' }}
            </td>
        </tr>

        <tr>
            <th>ZIP Code</th>
            <td>{{ $disposal->zip ?? '-' }}</td>
        </tr>

        <tr>
            <th>Date of Discharge</th>
            <td>
                {{ $disposal->date_of_discharge
                    ? \Carbon\Carbon::parse($disposal->date_of_discharge)->format('d M Y')
                    : '-' }}
            </td>
        </tr>

        <tr>
            <th>Discharge Site</th>
            <td>{{ $disposal->discharge_site ?? '-' }}</td>
        </tr>

        <tr>
            <th>Transporter Name</th>
            <td>{{ $disposal->transporter_name ?? '-' }}</td>
        </tr>

        <tr>
            <th>Vehicle License Number</th>
            <td>{{ $disposal->vehicle_license_number ?? '-' }}</td>
        </tr>

    </table>

    <!-- Footer -->
    <div class="footer">
        This is a system-generated document. No signature required.
    </div>



    
</body>
</html>
