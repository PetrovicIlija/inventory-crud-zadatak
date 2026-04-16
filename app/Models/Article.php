<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'quantity',
        'price',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
