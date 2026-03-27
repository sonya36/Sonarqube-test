<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentAttachment extends Model
{
    public $timestamps = false; // Using custom created_at timestamp in migration

    protected $fillable = [
        'document_id',
        'uploaded_by',
        'original_name',
        'file_path',
        'disk',
        'file_type',
        'mime_type',
        'file_size',
    ];

    /**
     * The document this attachment belongs to.
     */
    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    /**
     * The user who uploaded this attachment.
     */
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
