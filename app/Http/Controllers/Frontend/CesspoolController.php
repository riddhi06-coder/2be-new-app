<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

use App\Models\CesspoolSystemDetails;


class CesspoolController extends Controller
{

    // === Cesspool Form
    public function cesspool_systems(Request $request)
    {
        return view('frontend.cesspool_systems');
    }

    // === Store Cesspool Form
    public function store_cesspool(Request $request)
    {
        Log::info('Cesspool form submission started', [
            'ip' => $request->ip(),
            'payload' => $request->except(['_token']),
        ]);

        $rules = [
            'date_of_pickup'          => 'required|date_format:m/d/Y',
            'inspector_name_company'  => 'required|string|max:255|regex:/^[^0-9]+$/',
            'site_address'            => 'required|string|max:500',
            'tax_map_number'          => 'required|string|max:100',
            'type_of_system'          => 'required|string|max:255',
            'cesspool_water_level_depth' => 'required|string|max:255',
            'cesspool_pumped'         => 'required|string|max:255',
            'water_stream_from_house' => 'required|string|max:255',
            'inlet_pipe_needs_repair' => 'required|string|max:255',
            'cesspool_composition'    => 'required|string|max:255',
            'service_recommended'     => 'required|string|max:255',
            'comments'                => 'required|string',
            'notes'                   => 'required|string',
            'inspector_signature'     => 'required|string|max:255|regex:/^[^0-9]+$/',
            'print_name'              => 'required|string|max:255|regex:/^[^0-9]+$/',
            'date'                    => 'required|date_format:m/d/Y',
        ];

        $messages = [
            'date_of_pickup.required'            => 'Date of Inspection is required.',
            'date_of_pickup.date_format'         => 'Date must be in MM/DD/YYYY format.',
            'inspector_name_company.required'    => 'Inspector Name & Company is required.',
            'inspector_name_company.regex'       => 'Inspector Name & Company cannot contain numbers.',
            'site_address.required'              => 'Site Address is required.',
            'tax_map_number.required'            => 'Tax Map Number is required.',
            'type_of_system.required'            => 'Type of System is required.',
            'cesspool_water_level_depth.required'=> 'Cesspool Water Level Depth is required.',
            'cesspool_pumped.required'           => 'Cesspool pumped field is required.',
            'water_stream_from_house.required'   => 'Water stream from house field is required.',
            'inlet_pipe_needs_repair.required'   => 'Inlet pipe needs repair field is required.',
            'cesspool_composition.required'      => 'Cesspool composition is required.',
            'service_recommended.required'       => 'Service recommended is required.',
            'comments.required'                  => 'Comments are required.',
            'notes.required'                     => 'Notes are required.',
            'inspector_signature.required'       => 'Inspector Signature is required.',
            'inspector_signature.regex'          => 'Inspector Signature cannot contain numbers.',
            'print_name.required'               => 'Print Name is required.',
            'print_name.regex'                  => 'Print Name cannot contain numbers.',
            'date.required'                     => 'Date is required.',
            'date.date_format'                  => 'Date must be in MM/DD/YYYY format.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            Log::warning('Cesspool form validation failed', [
                'ip'     => $request->ip(),
                'errors' => $validator->errors()->toArray(),
            ]);

            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $entry = CesspoolSystemDetails::create([
                'ip_address' => $request->ip(),

                // Step 1
                'home_inspection'            => $request->has('home_inspection') ? 1 : 0,
                'realtor'                    => $request->has('realtor') ? 1 : 0,
                'routine'                    => $request->has('routine') ? 1 : 0,
                'date_of_pickup'             => date('Y-m-d', strtotime($request->date_of_pickup)),
                'inspector_name_company'     => $request->inspector_name_company,
                'site_address'               => $request->site_address,
                'tax_map_number'             => $request->tax_map_number,
                'type_of_system'             => $request->type_of_system,

                // Step 2
                'property_in_use_yes'        => $request->has('usee_yes') ? 1 : 0,
                'property_in_use_no'         => $request->has('usee_no') ? 1 : 0,
                'site_condition_grass'       => $request->has('grass') ? 1 : 0,
                'site_condition_system_area' => $request->has('system_area') ? 1 : 0,
                'site_condition_other_area'  => $request->has('other_area') ? 1 : 0,
                'site_condition_ponding'     => $request->has('ponding') ? 1 : 0,
                'site_condition_barriers'    => $request->has('barriers') ? 1 : 0,
                'site_condition_effective'   => $request->has('effective') ? 1 : 0,
                'site_condition_not_effective' => $request->has('not_effective') ? 1 : 0,
                'runoff_yes'                 => $request->has('runoff_yes') ? 1 : 0,
                'runoff_no'                  => $request->has('runoff_no') ? 1 : 0,
                'runoff_na'                  => $request->has('runoff_na') ? 1 : 0,
                'malfunction_yes'            => $request->has('mal_yes') ? 1 : 0,
                'malfunction_no'             => $request->has('mal_no') ? 1 : 0,
                'discharge_grey'             => $request->has('grey') ? 1 : 0,
                'discharge_black'            => $request->has('black') ? 1 : 0,
                'discharge_unknown'          => $request->has('unknown') ? 1 : 0,
                'discharge_cesspool_area'    => $request->has('cesspool_area') ? 1 : 0,
                'discharge_cesspool_edge'    => $request->has('cesspool_edge') ? 1 : 0,
                'discharge_bleed_out'        => $request->has('bleed_out') ? 1 : 0,
                'discharge_past_failure'     => $request->has('past_failure') ? 1 : 0,

                // Step 3
                'access_lids_yes'            => $request->has('access_yes') ? 1 : 0,
                'access_lids_no'             => $request->has('access_no') ? 1 : 0,
                'access_lid_repair_yes'      => $request->has('accesslid_yes') ? 1 : 0,
                'access_lid_repair_no'       => $request->has('accesslid_no') ? 1 : 0,
                'cesspool_water_level_depth' => $request->cesspool_water_level_depth,
                'pumping_recommended_yes'    => $request->has('pumping_yes') ? 1 : 0,
                'pumping_recommended_no'     => $request->has('pumping_no') ? 1 : 0,
                'cesspool_pumped'            => $request->cesspool_pumped,
                'water_stream_from_house'    => $request->water_stream_from_house,
                'inlet_pipe_needs_repair'    => $request->inlet_pipe_needs_repair,
                'cesspool_composition'       => $request->cesspool_composition,
                'service_recommended'        => $request->service_recommended,
                'comments'                   => $request->comments,
                'notes'                      => $request->notes,
                'inspector_signature'        => $request->inspector_signature,
                'print_name'                 => $request->print_name,
                'date'                       => date('Y-m-d', strtotime($request->date)),
                'inserted_at'                => Carbon::now(),
            ]);

            Log::info('Cesspool form saved successfully', [
                'id' => $entry->id,
                'ip' => $request->ip(),
            ]);

            return redirect()->route('frontend.thank_you')->with('message', 'Cesspool inspection submitted successfully!');

        } catch (\Exception $e) {
            Log::error('Cesspool form save failed', [
                'ip'    => $request->ip(),
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'Something went wrong. Please try again.')
                ->withInput();
        }
    }
}
