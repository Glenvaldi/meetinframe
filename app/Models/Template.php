<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'image_path',
        'layout_type',
        'price',
        'is_active',
    ];

    // Relasi: Template milik satu User (Creator)
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}