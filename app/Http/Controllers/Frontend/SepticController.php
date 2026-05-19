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


class SepticController extends Controller
{

    // === Septic Sysem Form
    public function septic_systems(Request $request)
    { 
        return view('frontend.septic_systems');
    }
}