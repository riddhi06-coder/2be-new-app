<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use PDF;

use App\Models\CesspoolSystemDetails;
use App\Models\EmailSettingsDetails;


class CesspoolRecordsController extends Controller
{

    // ── Index ────────────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = CesspoolSystemDetails::query();

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

        return view('backend.cesspool_records.index', compact('records'));
    }

    // ── Edit ─────────────────────────────────────────────────────────────────
    public function edit($id)
    {
        $record = CesspoolSystemDetails::findOrFail($id);
        return view('backend.cesspool_records.edit', compact('record'));
    }

    // ── Update ───────────────────────────────────────────────────────────────
    public function update(Request $request, $id)
    {
        $record = CesspoolSystemDetails::findOrFail($id);

        $request->validate([
            'image' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
            'video' => 'nullable|file|mimes:mp4,mov,avi,wmv,mkv|max:5120',
        ], [
            'image.mimes' => 'Image must be JPG, PNG, or WebP.',
            'image.max'   => 'Image size must not exceed 2MB.',
            'video.max'   => 'Video size must not exceed 5MB.',
        ]);

        $data = $request->except(['_token', '_method', 'image', 'video', 'remove_image', 'remove_video']);

        // Date formatting
        foreach (['date_of_pickup', 'date'] as $field) {
            if (!empty($data[$field])) {
                try {
                    $data[$field] = Carbon::parse($data[$field])->format('Y-m-d');
                } catch (\Exception) {
                    unset($data[$field]);
                }
            }
        }

        // Handle image
        if ($request->hasFile('image')) {
            if ($record->image_path) {
                Storage::disk('public')->delete($record->image_path);
            }
            $data['image_path'] = $request->file('image')->store('cesspool/images', 'public');
        } elseif ($request->boolean('remove_image') && $record->image_path) {
            Storage::disk('public')->delete($record->image_path);
            $data['image_path'] = null;
        }

        // Handle video — stored directly under public/cesspool/videos/
        if ($request->hasFile('video')) {
            if ($record->video_path && file_exists(public_path($record->video_path))) {
                @unlink(public_path($record->video_path));
            }
            $video    = $request->file('video');
            $filename = uniqid('vid_', true) . '.' . $video->getClientOriginalExtension();
            $destDir  = public_path('cesspool/videos');
            if (!is_dir($destDir)) {
                mkdir($destDir, 0755, true);
            }
            $video->move($destDir, $filename);
            $data['video_path'] = 'cesspool/videos/' . $filename;
        } elseif ($request->boolean('remove_video') && $record->video_path) {
            if (file_exists(public_path($record->video_path))) {
                @unlink(public_path($record->video_path));
            }
            $data['video_path'] = null;
        }

        $record->update($data);

        return redirect()->route('cesspool-records.index')
            ->with('success', 'Record updated successfully.');
    }

    // ── Export Single PDF ────────────────────────────────────────────────────
    public function exportPdf($id)
    {
        $record = CesspoolSystemDetails::findOrFail($id);
        $pdf    = PDF::loadView('backend.cesspool_records.pdf', compact('record'));
        $pdf->setPaper('a4', 'portrait');
        $fileName = 'Cesspool-Inspection-Report_' . Carbon::now()->format('m-d-Y') . '.pdf';
        return $pdf->download($fileName);
    }

    // ── Send Report via Email ────────────────────────────────────────────────
    public function sendReport(Request $request)
    {
        $request->validate([
            'record_id' => 'required|exists:cesspool_system_details,id',
            'to_email'  => 'required|email',
        ]);

        $record = CesspoolSystemDetails::findOrFail($request->record_id);

        try {
            $pdf        = PDF::loadView('backend.cesspool_records.pdf', compact('record'))->setPaper('a4', 'portrait');
            $pdfContent = $pdf->output();
            $toEmail    = $request->to_email;
            $subject    = 'Cesspool Inspection Report — ' . ($record->site_address ?? 'ID #' . $record->id);
            $fileName   = 'Cesspool-Inspection-Report_' . Carbon::now()->format('m-d-Y') . '.pdf';

            Mail::send('emails.inspection_report', ['record' => $record, 'type' => 'Cesspool'], function ($msg) use ($toEmail, $subject, $pdfContent, $fileName) {
                $msg->to($toEmail)
                    ->subject($subject)
                    ->attachData($pdfContent, $fileName, ['mime' => 'application/pdf']);
            });

            return response()->json(['success' => true, 'message' => 'Report sent to ' . $toEmail]);
        } catch (\Exception $e) {
            Log::error('Cesspool report email failed', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed to send email. Please try again.'], 500);
        }
    }

    // ── Delete ───────────────────────────────────────────────────────────────
    public function destroy($id)
    {
        $record = CesspoolSystemDetails::findOrFail($id);

        if ($record->image_path) {
            Storage::disk('public')->delete($record->image_path);
        }
        if ($record->video_path && file_exists(public_path($record->video_path))) {
            @unlink(public_path($record->video_path));
        }

        $record->delete();

        return redirect()->route('cesspool-records.index')
            ->with('success', 'Record deleted successfully.');
    }
}
