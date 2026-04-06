<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Application;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $status = $request->get('status');
        
        $query = Document::with(['application', 'user', 'updater']);

        // Handle Trashed items
        if ($status === 'trashed') {
            $query->onlyTrashed();
        } else if ($status) {
            $query->where('status', $status);
        }

        // Basic search
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        $documents = $query->latest()->paginate(10)->through(function ($doc) {
            return [
                'id' => $doc->id,
                'title' => $doc->title,
                'slug' => $doc->slug,
                'application' => $doc->application ? [
                    'name' => $doc->application->name,
                    'slug' => $doc->application->slug,
                    'color' => $doc->application->color ?? 'indigo',
                ] : null,
                'author' => $doc->user ? $doc->user->name : 'Unknown',
                'updater' => $doc->updater ? $doc->updater->name : null,
                'status' => $doc->status,
                'version' => $doc->version,
                'view_count' => $doc->view_count,
                'updated_at' => $doc->updated_at->diffForHumans(),
                'deleted_at' => $doc->deleted_at ? $doc->deleted_at->diffForHumans() : null,
            ];
        });

        return Inertia::render('Admin/Documents/Index', [
            'documents' => $documents,
            'stats' => [
                'total' => Document::count(),
                'published' => Document::where('status', 'published')->count(),
                'draft' => Document::where('status', 'draft')->count(),
                'archived' => Document::where('status', 'archived')->count(),
                'trashed' => Document::onlyTrashed()->count(),
            ],
            'filters' => $request->only(['search', 'status']),
        ]);
    }

    /**
     * Archive the specified document.
     */
    public function archive(Document $document)
    {
        $document->update(['status' => 'archived']);

        return redirect()->back()->with('success', 'Document archived successfully.');
    }

    /**
     * Restore a soft-deleted document.
     */
    public function restore($id)
    {
        $document = Document::withTrashed()->findOrFail($id);
        $document->restore();

        return redirect()->back()->with('success', 'Document restored successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document)
    {
        $document->delete();

        return redirect()->back()->with('success', 'Document moved to trash.');
    }

    /**
     * Permanently delete a document.
     */
    public function forceDelete($id)
    {
        $document = Document::withTrashed()->findOrFail($id);
        $document->forceDelete();

        return redirect()->back()->with('success', 'Document permanently deleted.');
    }
}
