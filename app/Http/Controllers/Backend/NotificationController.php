<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /** JSON feed for the live toast poller: unread count + anything newer than `after`. */
    public function feed(Request $request)
    {
        $userId = $request->user()->id;
        $after  = (int) $request->query('after', 0);

        $unread = AdminNotification::where('user_id', $userId)->whereNull('read_at')->count();

        $items = AdminNotification::where('user_id', $userId)
            ->when($after > 0, fn ($q) => $q->where('id', '>', $after))
            ->latest()
            ->limit(8)
            ->get()
            ->map(fn ($n) => [
                'id'      => $n->id,
                'type'    => $n->type,
                'title'   => $n->title,
                'message' => $n->message,
                'url'     => $n->url ?: route('admin.notifications.index'),
                'icon'    => $n->icon ?: 'fa fa-bell',
            ]);

        // `latestId` lets the client advance its cursor even when nothing is new.
        $latestId = AdminNotification::where('user_id', $userId)->max('id') ?? 0;

        // The client seeds `after` with the newest id at page load, so only things
        // that arrive afterwards come back here — no replaying of old notifications.
        return response()->json([
            'unread'   => $unread,
            'items'    => $items->values(),
            'latestId' => $latestId,
        ]);
    }

    /** Full list of the current admin's notifications. */
    public function index(Request $request)
    {
        $notifications = AdminNotification::where('user_id', $request->user()->id)
            ->latest()
            ->paginate(30);

        return view('backend.notifications.index', compact('notifications'));
    }

    /** Mark one notification read, then go to its linked page. */
    public function read(Request $request, AdminNotification $notification)
    {
        abort_unless($notification->user_id === $request->user()->id, 403);

        if (! $notification->read_at) {
            $notification->update(['read_at' => now()]);
        }

        return redirect($notification->url ?: route('admin.notifications.index'));
    }

    /** Mark every notification read for the current admin. */
    public function readAll(Request $request)
    {
        AdminNotification::where('user_id', $request->user()->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return back()->with('message', 'All notifications marked as read.');
    }
}
