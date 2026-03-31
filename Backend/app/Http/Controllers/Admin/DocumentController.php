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
        $query = Document::with(['application', 'user', 'updater']);

        // Basic search if needed later
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
        }

        $documents = $query->latest()->paginate(10)->through(function ($doc) {
            return [
                'id' => $doc->id,
                'title' => $doc->title,
                'slug' => $doc->slug,
                'application' => $doc->application ? [
                    'name' => $doc->application->name,
                    'slug' => $doc->application->slug,
                    'color' => $doc->application->color ?? 'bg-indigo-500',
                ] : null,
                'author' => $doc->user ? $doc->user->name : 'Unknown',
                'updater' => $doc->updater ? $doc->updater->name : null,
                'status' => $doc->status,
                'version' => $doc->version,
                'view_count' => $doc->view_count,
                'updated_at' => $doc->updated_at->diffForHumans(),
            ];
        });

        return Inertia::render('Admin/Documents/Index', [
            'documents' => $documents,
            'stats' => [
                'total' => Document::count(),
                'published' => Document::where('status', 'published')->count(),
                'draft' => Document::where('status', 'draft')->count(),
                'archived' => Document::where('status', 'archived')->count(),
            ],
            'filters' => $request->only(['search']),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document)
    {
        $document->delete();

        return redirect()->back()->with('success', 'Document deleted successfully.');
    }
}
