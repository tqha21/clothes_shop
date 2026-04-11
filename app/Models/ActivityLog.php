<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = ['description', 'causer_id', 'subject_type', 'subject_id'];
    
    public function causer()
    {
        return $this->belongsTo(User::class, 'causer_id');
    }
}
