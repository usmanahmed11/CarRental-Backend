<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class skillSet extends Model
{
    use HasFactory;
    protected $table = 'skillSet';

    protected $fillable = [
        'skill_name',
    ];
}
