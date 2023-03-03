<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Growth extends Model
{
    use HasFactory;

    
    protected $table = 'growth';
    // protected $dates = ['deleted_at'];
    protected $fillable = [
        'title',
        
    ];
    public function candidateInfo()
    {
        return $this->hasMany(CandidateInfo::class);
    }
}
