<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'color',
        'status',
        'sort_order',
        'created_by',
    ];

    /**
     * Users assigned to this application.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_applications')
            ->withPivot('permission', 'assigned_by', 'assigned_at');
    }

    /**
     * Documents belonging to this application.
     */
    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    /**
     * User who created the application.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
