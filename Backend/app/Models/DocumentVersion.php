<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentVersion extends Model
{
    public $timestamps = false; // Using custom created_at timestamp in migration

    protected $fillable = [
        'document_id',
        'saved_by',
        'title',
        'content',
        'version_number',
        'change_note',
    ];

    /**
     * The document this version belongs to.
     */
    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    /**
     * The user who saved this version.
     */
    public function saver()
    {
        return $this->belongsTo(User::class, 'saved_by');
    }
}
