<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::orderByDesc('published_at')->orderByDesc('id')->get();
        return view('backend.announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('backend.announcements.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateAnnouncement($request);

        Announcement::create([
            'title'        => $validated['title'],
            'slug'         => $this->uniqueSlug($validated['title']),
            'body'         => $validated['body'],
            'image_path'   => $request->hasFile('image') ? $this->storeImage($request->file('image')) : null,
            'is_active'    => $request->boolean('is_active'),
            'published_at' => ! empty($validated['published_at']) ? $validated['published_at'] : now(),
            'created_by'   => $request->user()->id,
        ]);

        return redirect()->route('admin.announcements.index')->with('message', 'Announcement created successfully.');
    }

    public function edit(Announcement $announcement)
    {
        return view('backend.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $validated = $this->validateAnnouncement($request);

        if ($request->boolean('remove_image') && ! $request->hasFile('image')) {
            $this->deleteImage($announcement->image_path);
            $announcement->image_path = null;
        }

        if ($request->hasFile('image')) {
            $this->deleteImage($announcement->image_path);
            $announcement->image_path = $this->storeImage($request->file('image'));
        }

        $announcement->title        = $validated['title'];
        $announcement->body         = $validated['body'];
        $announcement->is_active    = $request->boolean('is_active');
        $announcement->published_at = ! empty($validated['published_at']) ? $validated['published_at'] : ($announcement->published_at ?: now());
        $announcement->save();

        return redirect()->route('admin.announcements.index')->with('message', 'Announcement updated successfully.');
    }

    public function destroy(Announcement $announcement)
    {
        $this->deleteImage($announcement->image_path);
        $announcement->delete();

        return redirect()->route('admin.announcements.index')->with('message', 'Announcement deleted successfully.');
    }

    private function validateAnnouncement(Request $request): array
    {
        return $request->validate([
            'title'        => 'required|string|max:255',
            'body'         => 'required|string',
            'image'        => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:'.config('uploads.image_max_kb'),
            'is_active'    => 'nullable|boolean',
            'published_at' => 'nullable|date',
        ], [
            'image.max'   => 'The image may not be larger than '.round(config('uploads.image_max_kb') / 1024).' MB.',
            'image.image' => 'The file must be an image (JPG, PNG, GIF or WEBP).',
        ]);
    }

    /** Generate a URL-safe, unique slug from the title. */
    private function uniqueSlug(string $title): string
    {
        $base = Str::slug($title) ?: 'announcement';
        $slug = $base;
        $i    = 1;
        while (Announcement::withTrashed()->where('slug', $slug)->exists()) {
            $slug = $base.'-'.(++$i);
        }
        return $slug;
    }

    /** Move an image into public/uploads/announcements with a space-free name. */
    private function storeImage(UploadedFile $file): string
    {
        $dir = public_path('uploads/announcements');
        if (! is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }

        $base = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $base = preg_replace('/\s+/', '_', trim($base));
        $base = preg_replace('/[^A-Za-z0-9_\-]/', '', $base);
        $base = $base !== '' ? $base : 'announcement';

        $filename = $base.'_'.time().'.'.$file->getClientOriginalExtension();
        $file->move($dir, $filename);

        return 'uploads/announcements/'.$filename;
    }

    private function deleteImage(?string $path): void
    {
        if ($path && file_exists(public_path($path))) {
            @unlink(public_path($path));
        }
    }
}
