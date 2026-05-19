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
use App\Models\EmailSettingsDetails;


class EmailSettingsController extends Controller
{

    public function index(Request $request)
    {
        $emails = EmailSettingsDetails::orderBy('id', 'asc')->wherenull('deleted_by')->get();
        return view('backend.email.index', compact('emails'));
    }
    
    public function create(Request $request)
    { 
        return view('backend.email.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'default_email' => 'required|email|max:255',
            'email1'        => 'required|email|max:255',
            'email2'        => 'nullable|email|max:255',
            'email3'        => 'nullable|email|max:255',
        ], [
            'default_email.required' => 'Default email is required.',
            'default_email.email'    => 'Please enter a valid default email address.',

            'email1.required'        => 'Email 1 is required.',
            'email1.email'           => 'Please enter a valid Email 1 address.',

            'email2.email'           => 'Please enter a valid Email 2 address.',
            'email3.email'           => 'Please enter a valid Email 3 address.',
        ]);

        // Save Data
        EmailSettingsDetails::create([
            'default_email' => $request->default_email,
            'email1'        => $request->email1,
            'email2'        => $request->email2,
            'email3'        => $request->email3,
            'inserted_at'    => Carbon::now(),
            'inserted_by'    => Auth::id(),
        ]);

        return redirect()->route('manage-email-settings.index')->with('message', 'Email settings saved successfully.');
    }

    public function edit($id)
    {
        $email = EmailSettingsDetails::findOrFail($id);
        return view('backend.email.edit', compact('email'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'default_email' => 'required|email|max:255',
            'email1'        => 'required|email|max:255',
            'email2'        => 'nullable|email|max:255',
            'email3'        => 'nullable|email|max:255',
        ]);

        $email = EmailSettingsDetails::findOrFail($id);

        $email->update([
            'default_email' => $request->default_email,
            'email1'        => $request->email1,
            'email2'        => $request->email2,
            'email3'        => $request->email3,
            'modified_at'    => Carbon::now(),
            'modified_by'    => Auth::id(),
        ]);

        return redirect()
            ->route('manage-email-settings.index')
            ->with('message', 'Email settings updated successfully.');
    }

    public function destroy(string $id)
    {
        $data['deleted_by'] =  Auth::user()->id;
        $data['deleted_at'] =  Carbon::now();
        try {
            $industries = EmailSettingsDetails::findOrFail($id);
            $industries->update($data);

            return redirect()->route('manage-email-settings.index')->with('message', 'Details deleted successfully!');
        } catch (Exception $ex) {
            return redirect()->back()->with('error', 'Something Went Wrong - ' . $ex->getMessage());
        }
    }
    

}