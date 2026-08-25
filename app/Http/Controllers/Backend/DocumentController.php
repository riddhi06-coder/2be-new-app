<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;

class DocumentController extends Controller
{
    /** Employees available as owners of personal documents. */
    private function employees()
    {
        return User::whereHas('role', fn ($q) => $q->where('slug', 'employee'))
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    public function index()
    {
        $documents = Document::with(['category', 'assignees'])->orderByDesc('id')->get();
        return view('backend.documents.index', compact('documents'));
    }

    public function create()
    {
        return view('backend.documents.create', [
            'categories' => DocumentCategory::where('is_active', true)->orderBy('name')->get(),
            'employees'  => $this->employees(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateDocument($request, true);

        $file = $request->file('file');
        // Read metadata BEFORE moving the file — after move() the temp file is gone.
        $originalName = $this->cleanName($file->getClientOriginalName());
        $fileSize     = $file->getSize();
        $mimeType     = $file->getClientMimeType();
        $path         = $this->storeFile($file);

        $document = Document::create([
            'document_category_id' => $validated['document_category_id'],
            'title'                => $validated['title'],
            'file_path'            => $path,
            'original_name'        => $originalName,
            'file_size'            => $fileSize,
            'mime_type'            => $mimeType,
            'is_public'            => $request->boolean('is_public'),
            'user_id'              => null, // assignments now live in the document_user pivot
            'uploaded_by'          => $request->user()->id,
        ]);

        // Personal docs can be assigned to one or more employees.
        if (! $request->boolean('is_public')) {
            $document->assignees()->sync($validated['user_ids'] ?? []);
        }

        return redirect()->route('admin.documents.index')->with('message', 'Document uploaded successfully.');
    }

    public function edit(Document $document)
    {
        $document->load('assignees');

        return view('backend.documents.edit', [
            'document'    => $document,
            'assignedIds' => $document->assignees->pluck('id')->all(),
            'categories'  => DocumentCategory::where('is_active', true)->orWhere('id', $document->document_category_id)->orderBy('name')->get(),
            'employees'   => $this->employees(),
        ]);
    }

    public function update(Request $request, Document $document)
    {
        $validated = $this->validateDocument($request, false);

        // Replace the file only if a new one was uploaded.
        if ($request->hasFile('file')) {
            $this->deleteFile($document->file_path);
            $file = $request->file('file');
            // Read metadata BEFORE moving the file — after move() the temp file is gone.
            $originalName = $this->cleanName($file->getClientOriginalName());
            $fileSize     = $file->getSize();
            $mimeType     = $file->getClientMimeType();
            $document->file_path     = $this->storeFile($file);
            $document->original_name = $originalName;
            $document->file_size     = $fileSize;
            $document->mime_type     = $mimeType;
        }

        $document->document_category_id = $validated['document_category_id'];
        $document->title                = $validated['title'];
        $document->is_public            = $request->boolean('is_public');
        $document->user_id              = null; // assignments live in the pivot now
        $document->save();

        // Sync employee assignments: none for public, the selected set for personal.
        $document->assignees()->sync($request->boolean('is_public') ? [] : ($validated['user_ids'] ?? []));

        return redirect()->route('admin.documents.index')->with('message', 'Document updated successfully.');
    }

    public function destroy(Document $document)
    {
        $this->deleteFile($document->file_path);
        $document->delete();

        return redirect()->route('admin.documents.index')->with('message', 'Document deleted successfully.');
    }

    /** Stream the stored file to the browser. */
    public function download(Document $document)
    {
        $full = public_path($document->file_path);
        if (! file_exists($full)) {
            abort(404, 'File not found.');
        }

        return response()->download($full, $document->original_name ?: basename($document->file_path));
    }

    /**
     * Move an uploaded file into public/uploads/documents and return its
     * relative path. NOTE: the folder is intentionally NOT "public/documents"
     * because that path would shadow the /documents route on the web server.
     */
    private function storeFile(UploadedFile $file): string
    {
        $dir = public_path('uploads/documents');
        if (! is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }

        // Build a space-free, filesystem-safe name from the original filename:
        // spaces -> underscores, drop anything that isn't a letter/number/_/-.
        $base = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $base = preg_replace('/\s+/', '_', trim($base));
        $base = preg_replace('/[^A-Za-z0-9_\-]/', '', $base);
        $base = $base !== '' ? $base : 'document';

        // Append a timestamp + random suffix so identical filenames never collide.
        $filename = $base.'_'.time().'_'.mt_rand(1000, 9999).'.'.$file->getClientOriginalExtension();
        $file->move($dir, $filename);

        return 'uploads/documents/'.$filename;
    }

    /** Clean a display filename: collapse whitespace to underscores (no spaces). */
    private function cleanName(string $name): string
    {
        return preg_replace('/\s+/', '_', trim($name));
    }

    /** Delete a stored file from the public folder if it exists. */
    private function deleteFile(?string $path): void
    {
        if ($path && file_exists(public_path($path))) {
            @unlink(public_path($path));
        }
    }

    /**
     * Shared validation. On create the file is required; on update it is optional
     * (keep the existing file). user_id is required only for personal documents.
     */
    private function validateDocument(Request $request, bool $fileRequired): array
    {
        return $request->validate([
            'document_category_id' => 'required|exists:document_categories,id',
            'title'                => 'required|string|max:255',
            'file'                 => [$fileRequired ? 'required' : 'nullable', 'file', 'mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png', 'max:'.config('uploads.document_max_kb')],
            'is_public'            => 'nullable|boolean',
            'user_ids'             => [Rule::requiredIf(fn () => ! $request->boolean('is_public')), 'nullable', 'array'],
            'user_ids.*'           => ['exists:users,id'],
        ], [
            'file.max'          => 'The file may not be larger than '.round(config('uploads.document_max_kb') / 1024).' MB.',
            'file.mimes'        => 'Allowed file types: PDF, Word, Excel, JPG, PNG.',
            'user_ids.required'  => 'Please choose at least one employee this personal document belongs to.',
        ]);
    }
}
