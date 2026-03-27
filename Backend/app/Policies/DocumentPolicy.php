<?php

namespace App\Policies;

use App\Models\Document;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DocumentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Controller handles filtering based on role/assignment
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Document $document): bool
    {
        if ($user->role === 'admin') return true;

        return $user->applications()->where('applications.id', $document->application_id)->exists();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, int $applicationId): bool
    {
        if ($user->role === 'admin') return true;

        $appAssignment = $user->applications()->where('applications.id', $applicationId)->first();
        
        return $appAssignment && in_array($appAssignment->pivot->permission, ['write', 'admin']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Document $document): bool
    {
        if ($user->role === 'admin') return true;

        // User can update their own document if they still have write/admin access to the application
        if ($document->user_id !== $user->id) return false;

        $appAssignment = $user->applications()->where('applications.id', $document->application_id)->first();
        
        return $appAssignment && in_array($appAssignment->pivot->permission, ['write', 'admin']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Document $document): bool
    {
        if ($user->role === 'admin') return true;

        return $document->user_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Document $document): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Document $document): bool
    {
        return false;
    }
}
