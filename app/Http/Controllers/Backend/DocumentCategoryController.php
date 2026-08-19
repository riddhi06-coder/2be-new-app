<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\DocumentCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Document folders (e.g. Employee Handbook, Safety Programs, Labor Law Posters).
 */
class DocumentCategoryController extends Controller
{
    public function index()
    {
        $categories = DocumentCategory::withCount('documents')->orderBy('name')->get();
        return view('backend.document_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('backend.document_categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
        ]);

        DocumentCategory::create([
            'name'        => $validated['name'],
            'slug'        => $this->uniqueSlug($validated['name']),
            'description' => $validated['description'] ?? null,
            'is_active'   => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.document-categories.index')->with('message', 'Folder created successfully.');
    }

    public function edit(DocumentCategory $document_category)
    {
        return view('backend.document_categories.edit', ['category' => $document_category]);
    }

    public function update(Request $request, DocumentCategory $document_category)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
        ]);

        $document_category->update([
            'name'        => $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_active'   => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.document-categories.index')->with('message', 'Folder updated successfully.');
    }

    public function destroy(DocumentCategory $document_category)
    {
        if ($document_category->documents()->exists()) {
            return back()->with('message', 'Cannot delete a folder that still contains documents. Remove its documents first.');
        }

        $document_category->delete();

        return redirect()->route('admin.document-categories.index')->with('message', 'Folder deleted successfully.');
    }

    private function uniqueSlug(string $name): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i    = 1;
        while (DocumentCategory::withTrashed()->where('slug', $slug)->exists()) {
            $slug = $base.'-'.(++$i);
        }
        return $slug;
    }
}
