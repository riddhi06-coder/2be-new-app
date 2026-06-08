<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use PDF;

use App\Models\SepticSystemDetails;


class SepticRecordsController extends Controller
{

    // ── Index ────────────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = SepticSystemDetails::query();

        if ($request->filled('from_date')) {
            $query->whereDate('date_of_pickup', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('date_of_pickup', '<=', $request->to_date);
        }
        if ($request->filled('status')) {
            if ($request->status === 'draft') {
                $query->where('is_draft', true);
            } elseif ($request->status === 'submitted') {
                $query->where('is_draft', false);
            }
        }

        $records = $query->orderBy('inserted_at', 'desc')->get();

        // dd($records);

        return view('backend.septic_records.index', compact('records'));
    }

    // ── Edit ─────────────────────────────────────────────────────────────────
    public function edit($id)
    {
        $record = SepticSystemDetails::findOrFail($id);
        return view('backend.septic_records.edit', compact('record'));
    }

    // ── Update ───────────────────────────────────────────────────────────────
    public function update(Request $request, $id)
    {
        $record = SepticSystemDetails::findOrFail($id);

        $request->validate([
            'image' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
            'video' => 'nullable|file|mimes:mp4,mov,avi,wmv,mkv|max:5120',
            'inspector_signature' => ['nullable', 'string'],
        ], [
            'image.mimes' => 'Image must be JPG, PNG, or WebP.',
            'image.max'   => 'Image size must not exceed 2MB.',
            'video.max'   => 'Video size must not exceed 5MB.',
        ]);

        $data = $request->except(['_token', '_method', 'image', 'video', 'remove_image', 'remove_video', 'inspector_signature']);

        // Date formatting
        if (!empty($data['date_of_pickup'])) {
            try {
                $data['date_of_pickup'] = Carbon::parse($data['date_of_pickup'])->format('Y-m-d');
            } catch (\Exception) {
                unset($data['date_of_pickup']);
            }
        }

        // Handle image
        if ($request->hasFile('image')) {
            if ($record->image_path) {
                Storage::disk('public')->delete($record->image_path);
            }
            $data['image_path'] = $request->file('image')->store('septic/images', 'public');
        } elseif ($request->boolean('remove_image') && $record->image_path) {
            Storage::disk('public')->delete($record->image_path);
            $data['image_path'] = null;
        }

        // Handle video — stored directly under public/septic/videos/
        if ($request->hasFile('video')) {
            if ($record->video_path && file_exists(public_path($record->video_path))) {
                @unlink(public_path($record->video_path));
            }
            $video    = $request->file('video');
            $filename = uniqid('vid_', true) . '.' . $video->getClientOriginalExtension();
            $destDir  = public_path('septic/videos');
            if (!is_dir($destDir)) {
                mkdir($destDir, 0755, true);
            }
            $video->move($destDir, $filename);
            $data['video_path'] = 'septic/videos/' . $filename;
        } elseif ($request->boolean('remove_video') && $record->video_path) {
            if (file_exists(public_path($record->video_path))) {
                @unlink(public_path($record->video_path));
            }
            $data['video_path'] = null;
        }

        // Handle signature pad — base64 data URL → PNG file in public/septic/signatures/
        $sigRaw = $request->input('inspector_signature');
        if (is_string($sigRaw) && preg_match('/^data:image\/(\w+);base64,(.+)$/', $sigRaw, $m)) {
            if ($record->inspector_signature && file_exists(public_path($record->inspector_signature))) {
                @unlink(public_path($record->inspector_signature));
            }
            $ext      = strtolower($m[1]) === 'jpeg' ? 'jpg' : strtolower($m[1]);
            $imgBytes = base64_decode($m[2], true);
            if ($imgBytes !== false) {
                $filename = uniqid('sig_', true) . '.' . $ext;
                $destDir  = public_path('septic/signatures');
                if (!is_dir($destDir)) {
                    mkdir($destDir, 0755, true);
                }
                file_put_contents($destDir . DIRECTORY_SEPARATOR . $filename, $imgBytes);
                $data['inspector_signature'] = 'septic/signatures/' . $filename;
            }
        }

        $record->update($data);

        return redirect()->route('septic-records.index')
            ->with('success', 'Record updated successfully.');
    }

    // ── Export Single PDF ────────────────────────────────────────────────────
    public function exportPdf($id)
    {
        $record = SepticSystemDetails::findOrFail($id);
        $pdf    = PDF::loadView('backend.septic_records.pdf', compact('record'));
        $pdf->setPaper('a4', 'portrait');
        $fileName = 'Septic-Inspection-Report_' . Carbon::now()->format('m-d-Y') . '.pdf';
        return $pdf->download($fileName);
    }

    // ── Send Report via Email ────────────────────────────────────────────────
    public function sendReport(Request $request)
    {
        $request->validate([
            'record_id' => 'required|exists:septic_system_details,id',
            'to_email'  => 'required|email',
        ]);

        $record = SepticSystemDetails::findOrFail($request->record_id);

        try {
            $pdf        = PDF::loadView('backend.septic_records.pdf', compact('record'))->setPaper('a4', 'portrait');
            $pdfContent = $pdf->output();
            $toEmail    = $request->to_email;
            $subject    = 'Septic Inspection Report — ' . ($record->site_address ?? 'ID #' . $record->id);
            $fileName   = 'Septic-Inspection-Report_' . Carbon::now()->format('m-d-Y') . '.pdf';

            Mail::send('emails.inspection_report', ['record' => $record, 'type' => 'Septic'], function ($msg) use ($toEmail, $subject, $pdfContent, $fileName) {
                $msg->to($toEmail)
                    ->subject($subject)
                    ->attachData($pdfContent, $fileName, ['mime' => 'application/pdf']);
            });

            return response()->json(['success' => true, 'message' => 'Report sent to ' . $toEmail]);
        } catch (\Exception $e) {
            Log::error('Septic report email failed', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed to send email. Please try again.'], 500);
        }
    }

    // ── Delete ───────────────────────────────────────────────────────────────
    public function destroy($id)
    {
        $record = SepticSystemDetails::findOrFail($id);

        if ($record->image_path) {
            Storage::disk('public')->delete($record->image_path);
        }
        if ($record->video_path && file_exists(public_path($record->video_path))) {
            @unlink(public_path($record->video_path));
        }
        if ($record->inspector_signature && file_exists(public_path($record->inspector_signature))) {
            @unlink(public_path($record->inspector_signature));
        }

        $record->delete();

        return redirect()->route('septic-records.index')
            ->with('success', 'Record deleted successfully.');
    }
}
