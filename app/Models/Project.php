<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'url_image',
        'url_video',
        'min_investment',
        'max_investment',
        'limit_date',
        'user_id',
        'status',
        'current_investment',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'comments' => 'array', // Indicar que comments es un array
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function investments()
    {
        return $this->hasMany(Investment::class);
    }

}
