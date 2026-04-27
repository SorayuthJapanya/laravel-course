<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImageGeneration extends Model
{
    protected $fillable = [
        'user_id',
        'generate_prompt',
        'image_path',
        'file_size',
        'mime_type'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
