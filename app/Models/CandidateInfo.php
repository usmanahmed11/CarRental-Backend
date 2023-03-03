<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidateInfo extends Model
{
    use HasFactory;

    protected $table = 'candidate_info';
    // protected $dates = ['deleted_at'];

    protected $fillable = [
        'growth_id',
        'name',
        'experience',
        'skillSet',
        'jobTitle',
        'team',
        'location',
        'joiningDate',
        'status'
        
    ];
    public function growth()
    {
        return $this->belongsTo(Growth::class);
    }
}
