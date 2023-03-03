<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class jobTitle extends Model
{
    use HasFactory;

    protected $table = 'jobTitle';

    protected $fillable = [
        'job_title',
    ];
}
