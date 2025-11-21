<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }

    public function loans(): HasManyThrough
    {
        return $this->hasManyThrough(Loan::class, Book::class);
    }
}
