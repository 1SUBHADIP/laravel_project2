<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class Member extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'password',
        'date_of_birth',
        'department_id',
        'student_id',
        'membership_type',
        'membership_date',
        'status',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'membership_date' => 'date',
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
