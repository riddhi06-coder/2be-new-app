<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

use App\Models\WasteDisposalDetails;


class HomeController extends Controller
{

    // === Home
    public function home(Request $request)
    { 
        return view('frontend.index');
    }

    // === Thankyouuu
    public function thank_you(Request $request)
    { 
        return view('frontend.thank_you');
    }

    // === Log Waste Disposal
    public function log_waste_disposal(Request $request)
    { 
        return view('frontend.log_waste_disposal');
    }
   
    // === Store Log Waste Disposal
    public function store_waste_entry(Request $request)
    {
        // 🔹 Log incoming request
        Log::info('Waste entry submission started', [
            'ip' => $request->ip(),
            'payload' => $request->except(['_token'])
        ]);

        // Validation rules
        $rules = [
            'date_of_pickup' => 'required|date_format:m/d/Y',
            'generator_name' => 'required|string|max:255',
            'waste_type' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'volume_pumped' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'zip' => 'required|string|max:10',
            'date_of_discharge' => 'required|date_format:m/d/Y',
            'discharge_site' => 'required|string|in:Hilo,Kona',
            'transporter_name' => 'required|string|max:100',
            'vehicle_license_number' => 'required|string|max:50',
        ];

        $messages = [
            'date_of_pickup.required' => 'Please enter the pickup date.',
            'date_of_pickup.date_format' => 'The pickup date must be in MM/DD/YYYY format.',
            'generator_name.required' => 'Please enter the facility/owner name.',
            'waste_type.required' => 'Please specify the waste type.',
            'address.required' => 'Please enter the address.',
            'volume_pumped.required' => 'Please enter the pumped volume.',
            'volume_pumped.numeric' => 'Volume must be a number.',
            'unit.required' => 'Please enter the unit.',
            'zip.required' => 'Please enter the ZIP code.',
            'date_of_discharge.required' => 'Please enter the discharge date.',
            'date_of_discharge.date_format' => 'The discharge date must be in MM/DD/YYYY format.',
            'discharge_site.required' => 'Please select the discharge site.',
            'discharge_site.in' => 'Selected discharge site is invalid.',
            'transporter_name.required' => 'Please enter the driver’s initials.',
            'vehicle_license_number.required' => 'Please enter the vehicle license number.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {

            // 🔹 Log validation failure
            Log::warning('Waste entry validation failed', [
                'ip' => $request->ip(),
                'errors' => $validator->errors()->toArray()
            ]);

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // 🔹 Save record
            $entry = WasteDisposalDetails::create([
                'ip_address' => $request->ip(),
                'date_of_pickup' => date('Y-m-d', strtotime($request->date_of_pickup)),
                'generator_name' => $request->generator_name,
                'waste_type' => $request->waste_type,
                'address' => $request->address,
                'volume_pumped' => $request->volume_pumped,
                'unit' => $request->unit,
                'zip' => $request->zip,
                'date_of_discharge' => date('Y-m-d', strtotime($request->date_of_discharge)),
                'discharge_site' => $request->discharge_site,
                'transporter_name' => $request->transporter_name,
                'vehicle_license_number' => $request->vehicle_license_number,
                'inserted_at' => Carbon::now(),
            ]);

            // 🔹 Log success
            Log::info('Waste entry saved successfully', [
                'id' => $entry->id,
                'ip' => $request->ip()
            ]);

            return redirect()->route('frontend.thank_you')->with('message', 'Waste entry submitted successfully!');

        } catch (\Exception $e) {

            // 🔹 Log exception
            Log::error('Waste entry save failed', [
                'ip' => $request->ip(),
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'Something went wrong. Please try again.')
                ->withInput();
        }
    }



}