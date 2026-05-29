<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

use App\Models\CesspoolSystemDetails;


class CesspoolController extends Controller
{

    // ── GET: show form, purge expired drafts ─────────────────────────────────
    public function cesspool_systems()
    {
        CesspoolSystemDetails::where('is_draft', true)
            ->where('expires_at', '<', Carbon::now())
            ->delete();

        return view('frontend.cesspool_systems');
    }

    // ── POST: final submit ───────────────────────────────────────────────────
    public function store_cesspool(Request $request)
    {
        Log::info('Cesspool form submission started', [
            'ip'      => $request->ip(),
            'payload' => $request->except(['_token']),
        ]);

        $validator = Validator::make($request->all(), $this->validationRules(), $this->validationMessages());

        if ($validator->fails()) {
            Log::warning('Cesspool form validation failed', [
                'ip'     => $request->ip(),
                'errors' => $validator->errors()->toArray(),
            ]);
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // Remove any existing draft for this session
            $sessionKey = $request->session()->get('cesspool_draft_key');
            if ($sessionKey) {
                CesspoolSystemDetails::where('session_key', $sessionKey)->delete();
                $request->session()->forget('cesspool_draft_key');
            }

            $data = $this->buildFormData($request);
            $data += [
                'is_draft'    => false,
                'session_key' => null,
                'expires_at'  => null,
                'inserted_at' => Carbon::now(),
            ];

            $entry = CesspoolSystemDetails::create($data);

            Log::info('Cesspool form saved successfully', ['id' => $entry->id, 'ip' => $request->ip()]);

            return redirect()->route('frontend.thank_you')
                ->with('message', 'Cesspool inspection submitted successfully!');

        } catch (\Exception $e) {
            Log::error('Cesspool form save failed', ['ip' => $request->ip(), 'error' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Something went wrong. Please try again.')
                ->withInput();
        }
    }

    // ── POST: AJAX save draft ────────────────────────────────────────────────
    public function save_draft(Request $request)
    {
        try {
            // Purge expired drafts
            CesspoolSystemDetails::where('is_draft', true)
                ->where('expires_at', '<', Carbon::now())
                ->delete();

            // Delete previous draft from this session
            $sessionKey = $request->session()->get('cesspool_draft_key');
            if ($sessionKey) {
                CesspoolSystemDetails::where('session_key', $sessionKey)->delete();
            }

            $newKey = Str::uuid()->toString();
            $data   = $this->buildFormData($request);
            $data  += [
                'is_draft'    => true,
                'session_key' => $newKey,
                'expires_at'  => Carbon::now()->addHour(),
                'inserted_at' => Carbon::now(),
            ];

            CesspoolSystemDetails::create($data);
            $request->session()->put('cesspool_draft_key', $newKey);

            return response()->json([
                'success' => true,
                'message' => 'Your draft has been saved. It will be available for 1 hour.',
            ]);

        } catch (\Exception $e) {
            Log::error('Cesspool draft save failed', ['ip' => $request->ip(), 'error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed to save draft. Please try again.'], 500);
        }
    }

    // ── Shared: map request → DB data ────────────────────────────────────────
    private function buildFormData(Request $request): array
    {
        $ua          = $request->userAgent() ?? '';
        $browserInfo = $this->parseBrowserInfo($ua);
        $location    = $this->getLocation($request->ip());

        // Inspection type — comma-separated readable labels
        $inspectionTypes = [];
        if ($request->has('home_inspection')) $inspectionTypes[] = 'Home Inspector';
        if ($request->has('realtor'))         $inspectionTypes[] = 'Realtor';
        if ($request->has('routine'))         $inspectionTypes[] = 'Routine Maintenance';

        // Site conditions — comma-separated readable labels
        $siteConditions = [];
        if ($request->has('grass'))         $siteConditions[] = 'Grass cover/vegetation condition';
        if ($request->has('system_area'))   $siteConditions[] = 'System area';
        if ($request->has('other_area'))    $siteConditions[] = 'Other areas';
        if ($request->has('ponding'))       $siteConditions[] = 'Surface Ponding';
        if ($request->has('barriers'))      $siteConditions[] = 'Protective Barriers Present';
        if ($request->has('effective'))     $siteConditions[] = 'Effective';
        if ($request->has('not_effective')) $siteConditions[] = 'Not effective';

        // Surface discharge — comma-separated readable labels
        $discharge = [];
        if ($request->has('grey'))          $discharge[] = 'Grey water';
        if ($request->has('black'))         $discharge[] = 'Black water';
        if ($request->has('unknown'))       $discharge[] = 'Unknown';
        if ($request->has('cesspool_area')) $discharge[] = 'Surface discharge in area of cesspool';
        if ($request->has('cesspool_edge')) $discharge[] = 'Surface discharge at edge of cesspool area';
        if ($request->has('bleed_out'))     $discharge[] = 'Surface discharge - bleed-out away';
        if ($request->has('past_failure'))  $discharge[] = 'Evidence of past failure';

        // Yes/No helper
        $yesNo = fn($y, $n) => $request->has($y) ? 'Yes' : ($request->has($n) ? 'No' : null);

        // Yes/No/N/A helper
        $yesNoNa = fn($y, $n, $na) => $request->has($y) ? 'Yes'
            : ($request->has($n) ? 'No' : ($request->has($na) ? 'N/A' : null));

        // Safe date parse
        $parseDate = function (?string $val): ?string {
            if (!$val) return null;
            try { return date('Y-m-d', strtotime($val)); } catch (\Exception $e) { return null; }
        };

        return [
            // Client info
            'ip_address'       => $request->ip(),
            'user_agent'       => $ua,
            'browser'          => $browserInfo['browser'],
            'browser_version'  => $browserInfo['version'],
            'device_type'      => $browserInfo['device'],
            'operating_system' => $browserInfo['os'],
            'location_country' => $location['country'],
            'location_city'    => $location['city'],
            'location_region'  => $location['region'],
            'location_timezone'=> $location['timezone'],

            // Step 1
            'inspection_type'        => implode(', ', $inspectionTypes) ?: null,
            'date_of_pickup'         => $parseDate($request->date_of_pickup),
            'inspector_name_company' => $request->inspector_name_company,
            'site_address'           => $request->site_address,
            'tax_map_number'         => $request->tax_map_number,
            'type_of_system'         => $request->type_of_system,

            // Step 2
            'property_in_use'  => $yesNo('usee_yes', 'usee_no'),
            'site_conditions'  => implode(', ', $siteConditions) ?: null,
            'surface_runoff'   => $yesNoNa('runoff_yes', 'runoff_no', 'runoff_na'),
            'malfunction'      => $yesNo('mal_yes', 'mal_no'),
            'surface_discharge'=> implode(', ', $discharge) ?: null,

            // Step 3
            'accessible_lids'            => $yesNo('access_yes', 'access_no'),
            'access_lid_repair'          => $yesNo('accesslid_yes', 'accesslid_no'),
            'cesspool_water_level_depth' => $request->cesspool_water_level_depth,
            'pumping_recommended'        => $yesNo('pumping_yes', 'pumping_no'),
            'cesspool_pumped'            => $request->cesspool_pumped,
            'water_stream_from_house'    => $request->water_stream_from_house,
            'inlet_pipe_needs_repair'    => $request->inlet_pipe_needs_repair,
            'cesspool_composition'       => $request->cesspool_composition,
            'service_recommended'        => $request->service_recommended,
            'comments'                   => $request->comments,
            'notes'                      => $request->notes,
            'inspector_signature'        => $request->inspector_signature,
            'print_name'                 => $request->print_name,
            'date'                       => $parseDate($request->date),
        ];
    }

    // ── Validation ───────────────────────────────────────────────────────────
    private function validationRules(): array
    {
        return [
            'date_of_pickup'             => 'required|date_format:m/d/Y',
            'inspector_name_company'     => 'required|string|max:255|regex:/^[^0-9]+$/',
            'site_address'               => 'required|string|max:500',
            'tax_map_number'             => 'required|string|max:100',
            'type_of_system'             => 'required|string|max:255',
            'cesspool_water_level_depth' => 'required|string|max:255',
            'cesspool_pumped'            => 'required|string|max:255',
            'water_stream_from_house'    => 'required|string|max:255',
            'inlet_pipe_needs_repair'    => 'required|string|max:255',
            'cesspool_composition'       => 'required|string|max:255',
            'service_recommended'        => 'required|string|max:255',
            'comments'                   => 'required|string',
            'notes'                      => 'required|string',
            'inspector_signature'        => 'required|string|max:255|regex:/^[^0-9]+$/',
            'print_name'                 => 'required|string|max:255|regex:/^[^0-9]+$/',
            'date'                       => 'required|date_format:m/d/Y',
        ];
    }

    private function validationMessages(): array
    {
        return [
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
    }

    // ── Browser / OS / Device parser ─────────────────────────────────────────
    private function parseBrowserInfo(string $userAgent): array
    {
        $browser = 'Unknown';
        $version = 'Unknown';
        $os      = 'Unknown';
        $device  = 'Desktop';

        $osPatterns = [
            'Windows 11' => '/Windows NT 10\.0.*rv:(10[89]|[1-9]\d{2,})/i',
            'Windows 10' => '/Windows NT 10\.0/i',
            'Windows 8'  => '/Windows NT 6\.[23]/i',
            'Windows 7'  => '/Windows NT 6\.1/i',
            'macOS'      => '/Mac OS X/i',
            'iOS'        => '/iPhone OS|CPU OS/i',
            'Android'    => '/Android/i',
            'Linux'      => '/Linux/i',
        ];

        foreach ($osPatterns as $name => $pattern) {
            if (preg_match($pattern, $userAgent)) { $os = $name; break; }
        }

        if (preg_match('/iPad|Tablet/i', $userAgent)) {
            $device = 'Tablet';
        } elseif (preg_match('/Mobile|Android|iPhone/i', $userAgent)) {
            $device = 'Mobile';
        }

        // Order matters — Edge before Chrome, Opera before Chrome
        $browserPatterns = [
            'Edge'    => '/Edg\/([0-9.]+)/i',
            'Opera'   => '/OPR\/([0-9.]+)/i',
            'Chrome'  => '/Chrome\/([0-9.]+)/i',
            'Firefox' => '/Firefox\/([0-9.]+)/i',
            'Safari'  => '/Version\/([0-9.]+).*Safari/i',
            'IE'      => '/(?:MSIE ([0-9.]+)|Trident.*rv:([0-9.]+))/i',
        ];

        foreach ($browserPatterns as $name => $pattern) {
            if (preg_match($pattern, $userAgent, $m)) {
                $browser = $name;
                $version = $m[1] ?? ($m[2] ?? 'Unknown');
                break;
            }
        }

        return compact('browser', 'version', 'os', 'device');
    }

    // ── IP Geolocation (ip-api.com, free tier, no key needed) ────────────────
    private function getLocation(string $ip): array
    {
        $location = ['country' => null, 'city' => null, 'region' => null, 'timezone' => null];

        if (in_array($ip, ['127.0.0.1', '::1', 'localhost'])) {
            $location['country'] = 'Local (Development)';
            return $location;
        }

        try {
            $response = Http::timeout(3)
                ->get("http://ip-api.com/json/{$ip}?fields=status,country,regionName,city,timezone");

            if ($response->successful()) {
                $data = $response->json();
                if (($data['status'] ?? '') === 'success') {
                    $location['country']  = $data['country']    ?? null;
                    $location['city']     = $data['city']       ?? null;
                    $location['region']   = $data['regionName'] ?? null;
                    $location['timezone'] = $data['timezone']   ?? null;
                }
            }
        } catch (\Exception $e) {
            Log::debug('IP geolocation lookup failed', ['ip' => $ip, 'error' => $e->getMessage()]);
        }

        return $location;
    }


    public function cesspool_form()
    {
        // Sample data to pass to view
        $data = [
            'title' => 'Cesspool Form',
            'date' => date('d-m-Y'),
            'customer_name' => 'John Doe',
            'total' => 2500,
        ];
 
        // Load view and pass data
        $pdf = PDF::loadView('frontend.cesspool-form', $data);
 
        // Stream the PDF in the browser (instead of download)
        return $pdf->stream('cesspool_form.pdf');
    }
    
    
}
