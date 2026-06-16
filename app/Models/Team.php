<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;


class Team extends Model
{
    /** @use HasFactory<\Database\Factories\TeamFactory> */
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'acronym',
        'img',
    ];

     public function users(): HasMany
    {
        return $this->hasMany(User::class, 'team_id'); 
    }
}
