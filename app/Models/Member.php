<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'department_id',
        'student_id',
        'membership_type',
        'membership_date',
        'status',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }
}
