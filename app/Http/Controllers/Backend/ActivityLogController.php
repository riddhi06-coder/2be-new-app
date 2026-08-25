<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->latest();

        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }
        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('q')) {
            $query->where('description', 'like', '%'.$request->q.'%');
        }
        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $logs = $query->paginate(30)->withQueryString();

        $modules = ActivityLog::query()->select('module')->distinct()->orderBy('module')->pluck('module')->filter()->values();
        $events  = ActivityLog::query()->select('event')->distinct()->orderBy('event')->pluck('event')->filter()->values();
        $users   = User::orderBy('name')->get(['id', 'name']);

        return view('backend.activity_logs.index', compact('logs', 'modules', 'events', 'users'));
    }
}
