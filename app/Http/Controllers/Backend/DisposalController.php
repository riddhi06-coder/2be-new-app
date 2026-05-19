<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Cookie;
use ZipArchive;
use PDF;

use Carbon\Carbon;
use App\Models\User;
use App\Models\DisposalDetails;
use App\Models\WasteDisposalDetails;


class DisposalController extends Controller
{

    public function index(Request $request)
    {
        $disposals = WasteDisposalDetails::orderBy('id', 'desc')->get();


        $query = WasteDisposalDetails::query();

        if ($request->filled('year')) {
            $query->whereYear('date_of_pickup', $request->year);
        }

        $disposals = $query->orderBy('date_of_pickup', 'desc')->get();

        // Years list for dropdown
        $years = WasteDisposalDetails::selectRaw('YEAR(date_of_pickup) as year')
                    ->distinct()
                    ->orderBy('year', 'desc')
                    ->pluck('year');


        $query = WasteDisposalDetails::query();

        if ($request->filled('year')) {
            $query->whereYear('date_of_pickup', $request->year);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('date_of_pickup', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('date_of_pickup', '<=', $request->to_date);
        }

        $disposals = $query->orderBy('date_of_pickup', 'desc')->get();


        return view('backend.disposal.index', compact('disposals','years'));
    }
    
    public function create(Request $request)
    { 
        return view('backend.disposal.create');
    }

    public function edit($id)
    {
        $disposal = WasteDisposalDetails::findOrFail($id);
        return view('backend.disposal.edit', compact('disposal'));
    }

    public function export(Request $request)
    {
        $year = $request->year;

        $query = WasteDisposalDetails::query();

        if ($year) {
            $query->whereYear('date_of_pickup', $year);
        }

        $disposals = $query->orderBy('date_of_pickup', 'desc')->get();

        $fileName = 'disposal_details_' . ($year ?? 'all') . '.csv';

        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
        ];

        $columns = [
            'IP Address',
            'Date of Pickup',
            'Generator Name',
            'Waste Type',
            'Address',
            'Volume Pumped',
            'Unit',
            'Zip',
            'Date of Discharge',
            'Discharge Site',
            'Transporter Name',
            'Vehicle License Number',
        ];

        return new StreamedResponse(function () use ($disposals, $columns) {

            $file = fopen('php://output', 'w');

            // CSV Header
            fputcsv($file, $columns);

            foreach ($disposals as $disposal) {
                fputcsv($file, [
                    $disposal->ip_address,
                    \Carbon\Carbon::parse($disposal->date_of_pickup)->format('d-m-Y'),
                    $disposal->generator_name,
                    $disposal->waste_type,
                    $disposal->address,
                    $disposal->volume_pumped,
                    $disposal->unit,
                    $disposal->zip,
                    $disposal->date_of_discharge,
                    $disposal->discharge_site,
                    $disposal->transporter_name,
                    $disposal->vehicle_license_number,
                ]);
            }

            fclose($file);
        }, 200, $headers);
    }

    public function exportSelectedPdf(Request $request)
    {
        $ids = explode(',', $request->selected_ids);

        $disposals = WasteDisposalDetails::whereIn('id', $ids)->get();

        if ($disposals->isEmpty()) {
            return back()->with('error', 'No records found');
        }


        // 🔹 If ONLY ONE record selected → download PDF directly
        if ($disposals->count() === 1) {

            $disposal = $disposals->first();

            $pdf = PDF::loadView(
                'backend.disposal.pdf',
                compact('disposal')
            );

            $fileName = 'disposal_details_' . $disposal->generator_name . '.pdf';

            return $pdf->download($fileName);
        }

        // 🔹 If MULTIPLE records → ZIP
        $zipFileName = 'disposal_pdfs_' . time() . '.zip';
        $zipPath = public_path('pdf/' . $zipFileName);

        // Ensure directory exists
        if (!file_exists(public_path('pdf'))) {
            mkdir(public_path('pdf'), 0755, true);
        }

        $zip = new ZipArchive;
        $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        foreach ($disposals as $disposal) {

            $pdf = PDF::loadView(
                'backend.disposal.pdf',
                compact('disposal')
            );

            $pdfName = 'disposal_details_' . $disposal->generator_name . '.pdf';

            $zip->addFromString($pdfName, $pdf->output());
        }

        $zip->close();

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    public function generate_monthly_report(Request $request)
    {
        // ✅ Strict Validation
        $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2000|max:' . date('Y'),
            'soh_doh_registration' => 'required|string',
            'coh_permit' => 'required|string',
            'signed_by' => 'required|string',
            'title' => 'required|string',
            'signed_date' => 'required|date',
        ]);

        // ✅ Convert to integer (IMPORTANT for Carbon)
        $month = (int) $request->month;
        $year  = (int) $request->year;

        // ✅ Fetch records based on month & year
        $records = DB::table('waste_disposal_details')
            ->whereMonth('date_of_pickup', $month)
            ->whereYear('date_of_pickup', $year)
            ->orderBy('date_of_pickup', 'asc')
            ->get();

        // ✅ Optional: Stop if no records found
        if ($records->isEmpty()) {
            return back()->with('error', 'No records found for selected month.');
        }

        // ✅ Safe month name generation (FIXED Carbon issue)
        $monthName = Carbon::createFromDate($year, $month, 1)->format('F');

        $data = [
            'records' => $records,
            'month_name' => $monthName,
            'year' => $year,
            'soh_doh_registration' => $request->soh_doh_registration,
            'coh_permit' => $request->coh_permit,
            'signed_by' => $request->signed_by,
            'title' => $request->title,
            'signed_date' => Carbon::parse($request->signed_date)->format('m/d/Y'),
        ];

        // ✅ Generate PDF
        $pdf = Pdf::loadView('backend.disposal.generate_monthly_report', $data)
            ->setPaper('a4', 'landscape');

        // ✅ Clean filename
        $fileName = 'monthly_report_' . strtolower($monthName) . '_' . $year . '.pdf';

        return $pdf->download($fileName);
    }

}