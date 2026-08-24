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


class EmployeesController extends Controller
{

    public function employee_login()
    {
        return view('frontend.employee.login');
    }

}