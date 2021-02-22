<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory, Uuids;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'salary',
        'photo',
        'number_id',
        'joining_date',
    ];

    protected $casts = [
        'id' => 'string',
    ];
}
