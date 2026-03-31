<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentSection extends Model
{
    protected $fillable = [
        'document_id',
        'user_id',
        'sub_title',
        'content',
        'sort_order',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
