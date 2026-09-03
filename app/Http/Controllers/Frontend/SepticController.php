<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use PDF;

use App\Models\SepticSystemDetails;


class SepticController extends Controller
{

    // ── GET: show form, purge expired drafts ─────────────────────────────────
    public function septic_systems()
    {
        SepticSystemDetails::where('is_draft', true)
            ->where('expires_at', '<', Carbon::now())
            ->forceDelete();

        return view('frontend.septic_systems');
    }

    // ── POST: final submit ───────────────────────────────────────────────────
    public function store_septic(Request $request)
    {
        Log::info('Septic form submission started', [
            'ip'      => $request->ip(),
            'payload' => $request->except(['_token']),
        ]);

        $validator = Validator::make($request->all(), $this->validationRules(), $this->validationMessages());

        if ($validator->fails()) {
            Log::warning('Septic form validation failed', [
                'ip'     => $request->ip(),
                'errors' => $validator->errors()->toArray(),
            ]);
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $sessionKey = $request->session()->get('septic_draft_key');
            if ($sessionKey) {
                SepticSystemDetails::where('session_key', $sessionKey)->forceDelete();
                $request->session()->forget('septic_draft_key');
            }

            $data = $this->buildFormData($request);
            $data += [
                'is_draft'    => false,
                'session_key' => null,
                'expires_at'  => null,
                'inserted_at' => Carbon::now(),
            ];

            $entry = SepticSystemDetails::create($data);

            Log::info('Septic form saved successfully', ['id' => $entry->id, 'ip' => $request->ip()]);

            return redirect()->route('frontend.thank_you')
                ->with('message', 'Your Septic Tank Inspection entry has been submitted.');

        } catch (\Exception $e) {
            Log::error('Septic form save failed', ['ip' => $request->ip(), 'error' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Something went wrong. Please try again.')
                ->withInput();
        }
    }

    // ── POST: AJAX save draft ────────────────────────────────────────────────
    public function save_draft(Request $request)
    {
        try {
            SepticSystemDetails::where('is_draft', true)
                ->where('expires_at', '<', Carbon::now())
                ->forceDelete();

            $sessionKey = $request->session()->get('septic_draft_key');
            if ($sessionKey) {
                SepticSystemDetails::where('session_key', $sessionKey)->forceDelete();
            }

            $newKey = Str::uuid()->toString();
            $data   = $this->buildFormData($request);
            $data  += [
                'is_draft'    => true,
                'session_key' => $newKey,
                'expires_at'  => Carbon::now()->addHour(),
                'inserted_at' => Carbon::now(),
            ];

            SepticSystemDetails::create($data);
            $request->session()->put('septic_draft_key', $newKey);

            return response()->json([
                'success' => true,
                'message' => 'Your draft has been saved. It will be available for 1 hour.',
            ]);

        } catch (\Exception $e) {
            Log::error('Septic draft save failed', ['ip' => $request->ip(), 'error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed to save draft. Please try again.'], 500);
        }
    }

    // ── Shared: map request → DB data ────────────────────────────────────────
    private function buildFormData(Request $request): array
    {
        $ua          = $request->userAgent() ?? '';
        $browserInfo = $this->parseBrowserInfo($ua);
        $location    = $this->getLocation($request->ip());

        // Inspection type
        $inspectionTypes = [];
        if ($request->has('home_inspection')) $inspectionTypes[] = 'Home Inspector';
        if ($request->has('realtor'))         $inspectionTypes[] = 'Realtor';
        if ($request->has('routine'))         $inspectionTypes[] = 'Routine Maintenance';

        // Property in use
        $propertyInUse = [];
        if ($request->has('use_yes'))      $propertyInUse[] = 'Yes';
        if ($request->has('use_no'))       $propertyInUse[] = 'No';
        if ($request->has('use_fulltime')) $propertyInUse[] = 'Full time';
        if ($request->has('use_vacation')) $propertyInUse[] = 'Vacation Rental';
        if ($request->has('use_vacant'))   $propertyInUse[] = 'Vacant';
        if ($request->has('use_other'))    $propertyInUse[] = 'Other';
        if ($request->has('use_unknown'))  $propertyInUse[] = 'Unknown';

        // Site conditions
        $siteConditions = [];
        if ($request->has('grass'))        $siteConditions[] = 'Grass cover/vegetation condition';
        if ($request->has('cinder'))       $siteConditions[] = 'Cinder/rocks';
        if ($request->has('ponding'))      $siteConditions[] = 'Surface Ponding';
        if ($request->has('system_area'))  $siteConditions[] = 'System area';
        if ($request->has('other_area'))   $siteConditions[] = 'Other areas';
        if ($request->has('barriers'))     $siteConditions[] = 'Protective Barriers Present';
        if ($request->has('effective'))    $siteConditions[] = 'Effective';
        if ($request->has('not_effective'))$siteConditions[] = 'Not effective';

        // Surface runoff
        $surfaceRunoff = $request->has('runoff_yes') ? 'Yes'
            : ($request->has('runoff_no') ? 'No'
            : ($request->has('runoff_na') ? 'N/A' : null));

        // Malfunction
        $malfunction = [];
        if ($request->has('mal_yes'))          $malfunction[] = 'Yes';
        if ($request->has('mal_no'))           $malfunction[] = 'No';
        if ($request->has('surface_plumbing')) $malfunction[] = 'Surface discharge via plumbing';
        if ($request->has('grey'))             $malfunction[] = 'Grey water';
        if ($request->has('black'))            $malfunction[] = 'Black water';
        if ($request->has('unknown'))          $malfunction[] = 'Unknown';
        if ($request->has('tank_area'))        $malfunction[] = 'Surface discharge in area of tank';
        if ($request->has('tile_field'))       $malfunction[] = 'Surface discharge within tile field area';
        if ($request->has('edge_field'))       $malfunction[] = 'Surface discharge at edge of tile field';
        if ($request->has('bleed_out'))        $malfunction[] = 'Surface discharge bleed-out away from system';
        if ($request->has('past_failure'))     $malfunction[] = 'Evidence of past failure';

        // Manhole accessible
        $manholeAccessible = $request->has('accessible_yes') ? 'Yes'
            : ($request->has('accessible_no') ? 'No' : null);

        // Lid needs repair
        $lidNeedsRepair = $request->has('lid_yes') ? 'Yes'
            : ($request->has('lid_no') ? 'No' : null);

        // Liquid operating level
        $liquidLevel = [];
        if ($request->has('level_outlet')) $liquidLevel[] = 'At outlet invert';
        if ($request->has('level_above'))  $liquidLevel[] = 'Above outlet invert';
        if ($request->has('level_below'))  $liquidLevel[] = 'Below outlet invert';

        // Tank pumping recommended
        $tankPumping = $request->has('pump_yes') ? 'Yes'
            : ($request->has('pump_no') ? 'No' : null);

        // Tank pumped
        $tankPumped = $request->has('pumped_yes') ? 'Yes'
            : ($request->has('pumped_no') ? 'No'
            : ($request->has('pumped_na') ? 'N/A' : null));

        // Water stream from house
        $houseStream = [];
        if ($request->has('house_yes'))    $houseStream[] = 'Yes';
        if ($request->has('house_trickle'))$houseStream[] = 'Trickle';
        if ($request->has('house_steady')) $houseStream[] = 'Steady flow';
        if ($request->has('house_no'))     $houseStream[] = 'No';
        if ($request->has('house_na'))     $houseStream[] = 'N/A';

        // Water stream from drain field
        $drainStream = [];
        if ($request->has('drain_yes'))    $drainStream[] = 'Yes';
        if ($request->has('drain_trickle'))$drainStream[] = 'Trickle';
        if ($request->has('drain_steady')) $drainStream[] = 'Steady flow';
        if ($request->has('drain_no'))     $drainStream[] = 'No';
        if ($request->has('drain_na'))     $drainStream[] = 'N/A';

        // Inlet/Outlet tee
        $inletTee  = $request->has('inlet_yes')  ? 'Yes' : ($request->has('inlet_nd')  ? 'N/D' : null);
        $outletTee = $request->has('outlet_yes') ? 'Yes' : ($request->has('outlet_nd') ? 'N/D' : null);

        // Service recommended
        $serviceRec = $request->has('service_yes') ? 'Yes'
            : ($request->has('service_no') ? 'No'
            : ($request->has('service_nd') ? 'N/D' : null));

        // Safe date parse
        $parseDate = function (?string $val): ?string {
            if (!$val) return null;
            try { return date('Y-m-d', strtotime($val)); } catch (\Exception) { return null; }
        };

        return [
            // Client info
            'ip_address'        => $request->ip(),
            'user_agent'        => $ua,
            'browser'           => $browserInfo['browser'],
            'browser_version'   => $browserInfo['version'],
            'device_type'       => $browserInfo['device'],
            'operating_system'  => $browserInfo['os'],
            'location_country'  => $location['country'],
            'location_city'     => $location['city'],
            'location_region'   => $location['region'],
            'location_timezone' => $location['timezone'],

            // Step 1
            'inspection_type'        => implode(', ', $inspectionTypes) ?: null,
            'date_of_pickup'         => $parseDate($request->date_of_pickup),
            'time'                   => $request->time ?: null,
            'weather'                => $request->weather ?: null,
            'inspector_name_company' => $request->inspector_name_company,
            'site_address'           => $request->site_address,
            'tax_map_number'         => $request->tax_map_number,
            'type_of_system'         => $request->type_of_system,

            // Step 2
            'property_in_use' => implode(', ', $propertyInUse) ?: null,
            'site_conditions' => implode(', ', $siteConditions) ?: null,
            'surface_runoff'  => $surfaceRunoff,
            'malfunction'     => implode(', ', $malfunction) ?: null,

            // Step 3
            'manhole_accessible'      => $manholeAccessible,
            'lid_needs_repair'        => $lidNeedsRepair,
            'liquid_operating_level'  => implode(', ', $liquidLevel) ?: null,
            'scum_layer_thickness'    => $request->scum_layer_thickness,
            'sludge_layer_thickness'  => $request->sludge_layer_thickness,
            'tank_pumping_recommended'=> $tankPumping,
            'tank_pumped'             => $tankPumped,
            'approx_volume_pumped'    => $request->approx_volume_pumped,
            'water_stream_from_house' => implode(', ', $houseStream) ?: null,
            'water_stream_from_drain' => implode(', ', $drainStream) ?: null,
            'inlet_tee_needs_repair'  => $inletTee,
            'outlet_tee_needs_repair' => $outletTee,
            'tank_composition'        => $request->tank_composition,
            'approx_tank_size'        => $request->approx_tank_size,
            'service_recommended'     => $serviceRec,
            'comments'                => $request->comments,
            'inspector_signature'     => $this->storeSignature($request),
            'notes'                   => $request->notes,
        ];
    }

    // ── Signature pad → decode base64 PNG → public/septic/signatures/ ────────
    private function storeSignature(Request $request): ?string
    {
        $raw = $request->input('inspector_signature');
        if (!is_string($raw) || !preg_match('/^data:image\/(\w+);base64,(.+)$/', $raw, $m)) {
            return null;
        }
        $ext      = strtolower($m[1]) === 'jpeg' ? 'jpg' : strtolower($m[1]);
        $imgBytes = base64_decode($m[2], true);
        if ($imgBytes === false) {
            return null;
        }
        $filename = uniqid('sig_', true) . '.' . $ext;
        $destDir  = public_path('septic/signatures');
        if (!is_dir($destDir)) {
            mkdir($destDir, 0755, true);
        }
        file_put_contents($destDir . DIRECTORY_SEPARATOR . $filename, $imgBytes);
        return 'septic/signatures/' . $filename;
    }

    // ── Validation ───────────────────────────────────────────────────────────
    private function validationRules(): array
    {
        return [
            'date_of_pickup'          => 'required|date_format:m/d/Y',
            'inspector_name_company'  => 'required|string|max:255',
            'site_address'            => 'required|string|max:1000',
            'tax_map_number'          => 'required|string|max:100',
            'type_of_system'          => 'required|string|max:255',
            'scum_layer_thickness'    => 'required|string|max:100',
            'sludge_layer_thickness'  => 'required|string|max:100',
            'approx_volume_pumped'    => 'required|string|max:100',
            'tank_composition'        => 'required|string|max:255',
            'approx_tank_size'        => 'required|string|max:100',
            'comments'                => 'required|string',
            'inspector_signature'     => ['required', 'string', 'regex:/^data:image\/(png|jpeg|jpg|webp);base64,/'],
            'notes'                   => 'required|string',
        ];
    }

    private function validationMessages(): array
    {
        return [
            'date_of_pickup.required'           => 'Date of Inspection is required.',
            'date_of_pickup.date_format'        => 'Date must be in MM/DD/YYYY format.',
            'inspector_name_company.required'   => 'Inspector Name & Company is required.',
            'site_address.required'             => 'Site Address is required.',
            'tax_map_number.required'           => 'Tax Map Number is required.',
            'type_of_system.required'           => 'Type of System is required.',
            'scum_layer_thickness.required'     => 'Scum layer thickness is required.',
            'sludge_layer_thickness.required'   => 'Sludge layer thickness is required.',
            'approx_volume_pumped.required'     => 'Approx. volume pumped is required.',
            'tank_composition.required'         => 'Tank composition is required.',
            'approx_tank_size.required'         => 'Approx. size of tank is required.',
            'comments.required'                 => 'Comments are required.',
            'inspector_signature.required'      => 'Please sign in the signature box.',
            'inspector_signature.regex'         => 'Invalid signature data — please draw your signature again.',
            'notes.required'                    => 'Notes are required.',
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

    // ── IP Geolocation ────────────────────────────────────────────────────────
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

}
