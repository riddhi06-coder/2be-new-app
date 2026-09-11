<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /** HR activity — documents, employees, incident reports, calendar, etc. */
    public function index(Request $request)
    {
        return $this->render($request, 'hr');
    }

    /** Field forms activity — septic & cesspool inspection forms + disposal. */
    public function forms(Request $request)
    {
        return $this->render($request, 'forms');
    }

    /** Shared listing, scoped to a module group ('hr' or 'forms'). */
    private function render(Request $request, string $group)
    {
        $forms = ActivityLog::FORMS_MODULES;

        $query = ActivityLog::with('user')->latest();

        // Scope to the group: forms = only the form modules; hr = everything else.
        if ($group === 'forms') {
            $query->whereIn('module', $forms);
        } else {
            $query->whereNotIn('module', $forms);
        }

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

        // Module dropdown scoped to the current group.
        $modulesQuery = ActivityLog::query()->select('module')->distinct();
        $group === 'forms' ? $modulesQuery->whereIn('module', $forms) : $modulesQuery->whereNotIn('module', $forms);
        $modules = $modulesQuery->orderBy('module')->pluck('module')->filter()->values();

        $events = ActivityLog::query()->select('event')->distinct()->orderBy('event')->pluck('event')->filter()->values();
        $users  = User::orderBy('name')->get(['id', 'name']);

        return view('backend.activity_logs.index', [
            'logs'      => $logs,
            'modules'   => $modules,
            'events'    => $events,
            'users'     => $users,
            'group'     => $group,
            'title'     => $group === 'forms' ? 'Forms Activity Log' : 'HR Activity Log',
            'selfRoute' => $group === 'forms' ? 'admin.activity-logs.forms' : 'admin.activity-logs.index',
        ]);
    }
}
